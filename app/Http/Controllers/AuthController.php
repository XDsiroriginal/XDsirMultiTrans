<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('pages.registration');
    }
    public function showLogin()
    {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Введите email',
            'email.email'       => 'Введите корректный email',
            'password.required' => 'Введите пароль',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('profile'));
        }
        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'phone'      => [
                'required',
                'string',
                'regex:/^[78]\d{10}$/',
                'unique:users,phone',
            ],
            'password'   => ['required', 'string', 'min:8', 'regex:/^(?=.*\p{L})(?=.*\d).{8,}$/u'],
        ], [
            'first_name.required' => 'Укажите ваше имя',
            'email.required'      => 'Введите адрес электронной почты',
            'email.email'         => 'Введите корректный email',
            'email.unique'        => 'Пользователь с таким email уже зарегистрирован',
            'phone.required'      => 'Введите номер телефона',
            'phone.regex'         => 'Номер телефона должен состоять ровно из 11 цифр и начинаться с 7 или 8 (например: 79991234567)',
            'phone.unique'        => 'Этот номер телефона уже используется',
            'password.required'   => 'Придумайте пароль',
            'password.min'        => 'Пароль должен быть не короче 8 символов',
            'password.regex'      => 'Пароль должен содержать буквы и цифры',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
            'password'   => $validated['password'],
            'role_id'    => 1,
            'money'      => 0.00,
        ]);

        Auth::login($user);

        return redirect()->route('profile');
    }

    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $orders = $user->orders()->latest()->get();
        $payments = \App\Models\Payment::where('user_id', $user->id)->latest()->get();
        $transactions = collect();
        foreach ($payments as $payment) {
            $transactions->push([
                'type' => 'topup',
                'title' => 'Пополнение через СБП',
                'date' => $payment->created_at,
                'amount' => (float)$payment->amount,
                'is_income' => true,
                'status' => $payment->status === 'completed' ? 'Зачислено' : 'Ожидает оплаты',
                'status_key' => $payment->status,
                'reference' => 'Платёж #' . substr($payment->uuid, 0, 8),
            ]);
        }
        foreach ($orders as $order) {
            if (in_array($order->status, ['purchased', 'warehouse', 'shipped', 'delivered'])) {
                $transactions->push([
                    'type' => 'buyout',
                    'title' => 'Оплата выкупа: ' . $order->product_name,
                    'date' => $order->updated_at ?? $order->created_at,
                    'amount' => (float)$order->rub_total,
                    'is_income' => false,
                    'status' => 'Списано',
                    'status_key' => 'completed',
                    'reference' => 'Заказ #' . (1000 + $order->id),
                ]);
            }
        }
        $transactions = $transactions->sortByDesc('date')->values();
        return view('pages.profile', compact('orders', 'transactions'));
    }
    public function topup(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($request->has('amount')) {
            $rawAmount = str_replace([' ', ' ', ','], ['', '', '.'], (string)$request->input('amount'));
            $request->merge(['amount' => $rawAmount]);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10|max:100000000',
        ]);

        $user = Auth::user();
        $user->money = (float)$user->money + (float)$validated['amount'];
        $user->save();

        return redirect()->route('profile');
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => [
                'required',
                'string',
                'regex:/^[78]\d{10}$/',
                'unique:users,phone,' . $user->id,
            ],
            'adres' => 'nullable|string|max:255',
        ], [
            'first_name.required' => 'Укажите ваше имя',
            'email.required' => 'Введите адрес электронной почты',
            'email.email' => 'Введите корректный email',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован',
            'phone.required' => 'Введите номер телефона',
            'phone.regex' => 'Номер телефона должен состоять ровно из 11 цифр и начинаться с 7 или 8',
            'phone.unique' => 'Этот номер телефона уже используется',
        ]);

        $user->fill($validated);
        $user->save();

        return redirect()->route('profile')->with('success', 'Профиль обновлён');
    }

    public function updatePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*\p{L})(?=.*\d).{8,}$/u', 'confirmed'],
        ], [
            'current_password.required' => 'Введите текущий пароль',
            'password.required' => 'Введите новый пароль',
            'password.min' => 'Пароль должен быть не короче 8 символов',
            'password.regex' => 'Пароль должен содержать буквы и цифры',
            'password.confirmed' => 'Пароли не совпадают',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Текущий пароль указан неверно']);
        }

        $user->password = $validated['password'];
        $user->save();

        return redirect()->route('profile')->with('success', 'Пароль обновлён');
    }
}
