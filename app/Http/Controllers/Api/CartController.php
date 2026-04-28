<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Fetch all carts with details
     */
    public function index()
    {
        $carts = Cart::with('details')->get();
        return response()->json($carts);
    }

    /**
     * Add new cart
     */
    public function addCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|integer',
            'createdBy' => 'required|integer',
            'subscription_id' => 'nullable|integer',
            'subscription_value' => 'nullable|numeric',
            'isactive' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $cardNumber = $this->generateCardFormat($request->customer_id);

        $cart = Cart::create([
            'cardnumber' => $cardNumber,
            'createddate' => now(),
            'isactive' => $request->has('isactive') ? $request->isactive : true,
            'is_default' => $request->has('is_default') ? $request->is_default : true,
            'createdBy' => $request->createdBy,
            'subscription_id' => $request->subscription_id,
            'subscription_value' => $request->subscription_value,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cart created successfully',
            'data' => $cart,
        ], 201);
    }

    /**
     * Fetch single cart with details
     */
    public function fetchCartDetails($id)
    {
        $cart = Cart::with('details')->find($id);

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $cart,
        ]);
    }

    /**
     * Generate card format:
     * CA-customerid-timestamp-counter
     */
    public function generateCardFormat($customerId)
    {
        $timestamp = now()->timestamp;

        $counter = Cart::whereDate('createddate', today())->count() + 1;
        $counter = str_pad($counter, 3, '0', STR_PAD_LEFT);

        return 'CA-' . $customerId . '-' . $timestamp . '-' . $counter;
    }
    public function updateCartStatus(Request $request, $id)
{
    $request->validate([
        'isactive' => 'required|boolean',
    ]);

    $cart = Cart::find($id);

    if (!$cart) {
        return response()->json([
            'status' => false,
            'message' => 'Cart not found',
        ], 404);
    }

    $cart->isactive = $request->isactive;
    $cart->save();

    return response()->json([
        'status' => true,
        'message' => 'Cart status updated successfully',
        'data' => $cart,
    ]);
}
}