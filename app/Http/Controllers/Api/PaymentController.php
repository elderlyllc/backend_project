<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => (int) ($request->amount * 100),
            'currency' => 'usd',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function savePaymentStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|integer',
            'cart_id' => 'nullable|integer',
            'payment_intent_id' => 'required|string',
            'amount' => 'nullable|numeric',
            'currency' => 'nullable|string',
            'status' => 'required|string',
            'failure_message' => 'nullable|string',
        ]);

        $payment = Payment::updateOrCreate(
            [
                'payment_intent_id' => $request->payment_intent_id,
            ],
            [
                'user_id' => $request->user_id,
                'cart_id' => $request->cart_id,
                'amount' => $request->amount,
                'currency' => $request->currency ?? 'usd',
                'status' => $request->status,
                'failure_message' => $request->failure_message,
                'created_at' => now(),
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Payment saved successfully',
            'data' => $payment,
        ]);
    }
}