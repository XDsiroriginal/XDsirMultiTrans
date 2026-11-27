<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Необходима авторизация'
            ], 401);
        }

        if ($request->has('amount')) {
            $rawAmount = str_replace([' ', ' ', ','], ['', '', '.'], (string)$request->input('amount'));
            $request->merge(['amount' => $rawAmount]);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10|max:100000000',
        ], [
            'amount.required' => 'Укажите сумму пополнения',
            'amount.numeric' => 'Сумма должна быть числом',
            'amount.min' => 'Минимальная сумма — 10 ₽',
            'amount.max' => 'Превышена максимальная сумма пополнения',
        ]);

        $uuid = Str::uuid()->toString();

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'uuid' => $uuid,
            'amount' => (float)$validated['amount'],
            'status' => 'pending',
        ]);

        $confirmUrl = route('payment.confirm', ['uuid' => $uuid]);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' . urlencode($confirmUrl);

        return response()->json([
            'success' => true,
            'uuid' => $uuid,
            'amount' => number_format((float)$payment->amount, 2, '.', ' '),
            'confirm_url' => $confirmUrl,
            'qr_url' => $qrUrl,
        ]);
    }

    public function confirm(string $uuid)
    {
        $payment = Payment::with('user')->where('uuid', $uuid)->firstOrFail();

        $alreadyCompleted = ($payment->status === 'completed');

        if (!$alreadyCompleted) {
            $user = $payment->user;
            $user->money = (float)$user->money + (float)$payment->amount;
            $user->save();

            $payment->status = 'completed';
            $payment->save();
        }

        return view('pages.payment_success', compact('payment', 'alreadyCompleted'));
    }
}
