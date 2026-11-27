<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class OrderController extends Controller
{
    private function getExchangeRates(): array
    {
        return Cache::remember('cbr_exchange_rates', 3600, function () {
            try {
                $response = Http::timeout(3)->get('https://www.cbr-xml-daily.ru/daily_json.js');
                if ($response->successful()) {
                    $valute = $response->json()['Valute'] ?? [];
                    return [
                        'USD' => (float)($valute['USD']['Value'] ?? 92.50),
                        'EUR' => (float)($valute['EUR']['Value'] ?? 100.00),
                        'CNY' => (float)($valute['CNY']['Value'] ?? 12.80),
                        'INR' => isset($valute['INR'])
                            ? (float)($valute['INR']['Value'] / ($valute['INR']['Nominal'] ?? 100))
                            : 1.10,
                    ];
                }
            } catch (Throwable $e) {
            }

            return [
                'USD' => 92.50,
                'EUR' => 100.00,
                'CNY' => 12.80,
                'INR' => 1.10,
            ];
        });
    }

    public function create()
    {
        $rates = $this->getExchangeRates();
        return view('pages.order', compact('rates'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'product_url'  => 'required|url',
            'product_name' => 'required|string|max:255',
            'price'        => 'required|numeric|min:0.01',
            'currency'     => 'required|in:USD,CNY,INR,EUR',
            'quantity'     => 'required|integer|min:1',
            'comment'      => 'nullable|string|max:1000',
        ], [
            'product_url.required'  => 'Вставьте ссылку на товар',
            'product_url.url'       => 'Ссылка должна быть корректным URL-адресом',
            'product_name.required' => 'Укажите название и параметры товара (размер, цвет)',
            'price.required'        => 'Укажите цену товара в валюте',
            'price.numeric'         => 'Цена должна быть числом',
            'currency.required'     => 'Выберите валюту',
            'quantity.required'     => 'Укажите количество',
        ]);

        $rates = $this->getExchangeRates();
        $rate = $rates[$validated['currency']] ?? 92.50;
        $itemsCostRub = $validated['price'] * $validated['quantity'] * $rate;

        $commissionPercent = 0.10;
        $minCommissionRub = 500.00;
        $commissionRub = max($itemsCostRub * $commissionPercent, $minCommissionRub);

        $rubTotal = round($itemsCostRub + $commissionRub, 2);

        Order::create([
            'user_id'      => Auth::id(),
            'product_url'  => $validated['product_url'],
            'product_name' => $validated['product_name'],
            'price'        => $validated['price'],
            'currency'     => $validated['currency'],
            'quantity'     => $validated['quantity'],
            'comment'      => $validated['comment'] ?? null,
            'rub_total'    => $rubTotal,
            'status'       => 'pending',
        ]);

        return redirect()->route('profile');
    }

    public function cancel(Request $request, Order $order)
    {
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('profile')->with('error', 'Можно отменить только заказ со статусом «Ожидает выкупа»');
        }

        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('profile')->with('success', 'Заказ #' . (1000 + $order->id) . ' отменён');
    }
}
