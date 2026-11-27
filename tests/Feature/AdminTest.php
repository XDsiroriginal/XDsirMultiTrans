<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

class AdminTest extends TestCase
{
    public function test_guest_is_redirected_to_home()
    {
        $response = $this->get('/admin');
        $response->assertRedirect(route('home'));
    }

    public function test_regular_user_is_redirected_to_home()
    {
        $user = User::where('role_id', 1)->first();

        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect(route('home'));
    }

    public function test_admin_user_can_access_admin_panel()
    {
        $admin = User::where('email', 'qwe@qwe.qwe')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Управление заказами');
        $response->assertDontSee('Сортировка: от старых к новым');
        $response->assertDontSee('admin_badge"');
        $response->assertDontSee('Баланс:');
    }

    public function test_search_only_by_order_number()
    {
        $admin = User::where('email', 'qwe@qwe.qwe')->first();
        $order = Order::first();

        if ($order) {
            $num = (string)(1000 + $order->id);
            $res1 = $this->actingAs($admin)->get('/admin?status=all&search=' . $num);
            $res1->assertStatus(200);
            $res1->assertSee('Заказ #' . $num);

            $res2 = $this->actingAs($admin)->get('/admin?status=all&search=NonExistentText');
            $res2->assertStatus(200);
            $res2->assertSee('Заказы не найдены');
        }
    }

    public function test_validation_insufficient_funds_for_purchased()
    {
        $admin = User::where('email', 'qwe@qwe.qwe')->first();
        $client = User::where('role_id', 1)->first();

        $client->money = 0.00;
        $client->save();

        $order = Order::where('user_id', $client->id)->first();
        $order->status = 'pending';
        $order->save();

        $response = $this->actingAs($admin)->post('/admin/orders/' . $order->id . '/status', [
            'status' => 'purchased',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Невозможно перевести в статус «Выкуплен в магазине»: у заказчика недостаточно средств на счёте');

        $order->refresh();
        $this->assertEquals('pending', $order->status);

        $client->money = (float)$order->rub_total + 1000.00;
        $client->save();

        $responseSuccess = $this->actingAs($admin)->post('/admin/orders/' . $order->id . '/status', [
            'status' => 'purchased',
        ]);

        $responseSuccess->assertRedirect();
        $responseSuccess->assertSessionHas('success');

        $order->refresh();
        $client->refresh();

        $this->assertEquals('purchased', $order->status);
        $this->assertEquals(1000.00, (float)$client->money);
    }

    public function test_status_cannot_be_changed_backwards()
    {
        $admin = User::where('email', 'qwe@qwe.qwe')->first();
        $order = Order::first();

        $order->status = 'warehouse';
        $order->save();

        $responseBackwards = $this->actingAs($admin)->post('/admin/orders/' . $order->id . '/status', [
            'status' => 'pending',
        ]);

        $responseBackwards->assertRedirect();
        $responseBackwards->assertSessionHas('error', 'Нельзя изменить статус заказа на предыдущий этап');

        $order->refresh();
        $this->assertEquals('warehouse', $order->status);

        $responseForward = $this->actingAs($admin)->post('/admin/orders/' . $order->id . '/status', [
            'status' => 'shipped',
        ]);

        $responseForward->assertRedirect();
        $responseForward->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('shipped', $order->status);
    }
}
