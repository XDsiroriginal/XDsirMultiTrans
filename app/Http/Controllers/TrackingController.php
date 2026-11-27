<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('query', ''));
        $order = null;
        $searched = false;

        $userOrders = Auth::check()
            ? Auth::user()->orders()->latest()->take(5)->get()
            : collect();

        if ($query !== '') {
            $searched = true;
            $orderId = null;

            if (preg_match('/^#?(\d+)$/', $query, $matches)) {
                $num = (int)$matches[1];
                $orderId = $num > 1000 ? ($num - 1000) : $num;
            } elseif (preg_match('/^MT(\d+)[A-Z]{2}$/i', $query, $matches)) {
                $num = (int)$matches[1];
                $orderId = $num > 1000 ? ($num - 1000) : $num;
            }

            if ($orderId) {
                $order = Order::with('user')->find($orderId);
            }
        } elseif (Auth::check() && $userOrders->isNotEmpty()) {
            $order = $userOrders->first();
            $query = (string)(1000 + $order->id);
            $searched = true;
        }

        $stages = [
            'pending' => [
                'step' => 1,
                'fill' => '15%',
                'status_title' => 'Ожидает выкупа',
                'badge' => 'Заявка принята',
            ],
            'purchased' => [
                'step' => 2,
                'fill' => '35%',
                'status_title' => 'Выкуплен в магазине',
                'badge' => 'Выкуплен',
            ],
            'warehouse' => [
                'step' => 3,
                'fill' => '55%',
                'status_title' => 'Поступил на зарубежный склад',
                'badge' => 'На складе',
            ],
            'shipped' => [
                'step' => 4,
                'fill' => '75%',
                'status_title' => 'Таможенное оформление и авиарейс',
                'badge' => 'В пути в РФ',
            ],
            'delivered' => [
                'step' => 6,
                'fill' => '100%',
                'status_title' => 'Доставлен в пункт выдачи',
                'badge' => 'Вручено',
            ],
            'cancelled' => [
                'step' => 0,
                'fill' => '0%',
                'status_title' => 'Заказ отменён',
                'badge' => 'Отменён',
            ],
        ];

        $currentStage = $order ? ($stages[$order->status] ?? $stages['pending']) : null;

        $routesMap = [
            'USD' => 'Делавэр (США) → Москва',
            'CNY' => 'Гуанчжоу (Китай) → Москва',
            'INR' => 'Мумбаи (Индия) → Москва',
            'EUR' => 'Германия (ЕС) → Москва',
        ];

        $countryCode = match ($order?->currency) {
            'CNY' => 'CN',
            'INR' => 'IN',
            'EUR' => 'EU',
            default => 'US',
        };

        $trackNumber = $order ? ('MT' . (1000 + $order->id) . $countryCode) : null;
        $routeTitle = $order ? ($routesMap[$order->currency] ?? 'Зарубежный склад → РФ') : null;

        return view('pages.tracking', compact(
            'order',
            'query',
            'searched',
            'userOrders',
            'currentStage',
            'trackNumber',
            'routeTitle'
        ));
    }
}
