<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $statusFilter = $request->input('status', 'active');

        $query = Order::with('user');

        if ($statusFilter === 'active') {
            $query->whereIn('status', ['pending', 'purchased', 'warehouse', 'shipped']);
        } elseif ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search !== '') {
            $searchId = null;
            if (preg_match('/^#?(\d+)$/', $search, $matches)) {
                $num = (int)$matches[1];
                $searchId = $num > 1000 ? ($num - 1000) : $num;
            } elseif (preg_match('/^MT(\d+)[A-Z]{2}$/i', $search, $matches)) {
                $num = (int)$matches[1];
                $searchId = $num > 1000 ? ($num - 1000) : $num;
            }

            if ($searchId) {
                $query->where('id', $searchId);
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        $orders = $query->orderBy('created_at', 'asc')->get();

        $stats = [
            'total' => Order::count(),
            'active' => Order::whereIn('status', ['pending', 'purchased', 'warehouse', 'shipped'])->count(),
            'pending' => Order::where('status', 'pending')->count(),
            'purchased' => Order::where('status', 'purchased')->count(),
            'warehouse' => Order::where('status', 'warehouse')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        return view('pages.admin.orders', compact('orders', 'stats', 'search', 'statusFilter'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,purchased,warehouse,shipped,delivered,cancelled',
        ]);

        $order = Order::with('user')->findOrFail($id);
        $newStatus = $validated['status'];
        $oldStatus = $order->status;

        if ($newStatus === $oldStatus) {
            return redirect()->back();
        }

        if ($oldStatus === 'delivered' || $oldStatus === 'cancelled') {
            return redirect()->back()->with('error', 'Заказ завершён, изменение статуса невозможно');
        }

        $statusRanks = [
            'pending' => 1,
            'purchased' => 2,
            'warehouse' => 3,
            'shipped' => 4,
            'delivered' => 5,
        ];

        if ($newStatus !== 'cancelled') {
            $oldRank = $statusRanks[$oldStatus] ?? 0;
            $newRank = $statusRanks[$newStatus] ?? 0;

            if ($newRank < $oldRank) {
                return redirect()->back()->with('error', 'Нельзя изменить статус заказа на предыдущий этап');
            }
        }

        if ($newStatus === 'purchased' && $oldStatus !== 'purchased') {
            $client = $order->user;
            if (!$client || (float)$client->money < (float)$order->rub_total) {
                return redirect()->back()->with('error', 'Невозможно перевести в статус «Выкуплен в магазине»: у заказчика недостаточно средств на счёте');
            }

            $client->money = max(0, (float)$client->money - (float)$order->rub_total);
            $client->save();
        }

        $order->status = $newStatus;
        $order->save();

        return redirect()->back()->with('success', 'Статус заказа #' . (1000 + $order->id) . ' успешно обновлён');
    }
}
