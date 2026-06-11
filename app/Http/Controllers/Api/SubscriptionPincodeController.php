<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerServicePincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionPincodeController extends Controller
{
    /**
     * List all subscription-pincode mappings
     */
    public function index()
    {
        $mappings = CustomerServicePincode::with(['customer', 'subscription', 'pincode'])->get();

        return response()->json([
            'status' => true,
            'data' => $mappings,
        ]);
    }

    /**
     * Create a new subscription-pincode mapping
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'     => 'required|integer|exists:users,id',
            'subscription_id' => 'nullable|integer|exists:subscriptions,id',
            'pincode_id'      => 'required|integer|exists:pincodes,id',
            'start_date'      => 'required|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $exists = CustomerServicePincode::where('customer_id', $request->customer_id)
            ->where('subscription_id', $request->subscription_id)
            ->where('pincode_id', $request->pincode_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => false,
                'message' => 'Mapping already exists for this customer, subscription and pincode',
            ], 409);
        }

        $mapping = CustomerServicePincode::create([
            'customer_id'     => $request->customer_id,
            'subscription_id' => $request->subscription_id,
            'pincode_id'      => $request->pincode_id,
            'start_date'      => $request->start_date,
            'end_date'        => $request->end_date,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Mapping created successfully',
            'data'    => $mapping->load(['customer', 'subscription', 'pincode']),
        ], 201);
    }

    /**
     * Get a single mapping
     */
    public function show($id)
    {
        $mapping = CustomerServicePincode::with(['customer', 'subscription', 'pincode'])->find($id);

        if (!$mapping) {
            return response()->json([
                'status'  => false,
                'message' => 'Mapping not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $mapping,
        ]);
    }

    /**
     * Update an existing mapping
     */
    public function update(Request $request, $id)
    {
        $mapping = CustomerServicePincode::find($id);

        if (!$mapping) {
            return response()->json([
                'status'  => false,
                'message' => 'Mapping not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'subscription_id' => 'nullable|integer|exists:subscriptions,id',
            'pincode_id'      => 'nullable|integer|exists:pincodes,id',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $mapping->update($request->only(['subscription_id', 'pincode_id', 'start_date', 'end_date']));

        return response()->json([
            'status'  => true,
            'message' => 'Mapping updated successfully',
            'data'    => $mapping->load(['customer', 'subscription', 'pincode']),
        ]);
    }

    /**
     * Delete a mapping
     */
    public function destroy($id)
    {
        $mapping = CustomerServicePincode::find($id);

        if (!$mapping) {
            return response()->json([
                'status'  => false,
                'message' => 'Mapping not found',
            ], 404);
        }

        $mapping->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Mapping deleted successfully',
        ]);
    }

    /**
     * Get all pincode mappings for a specific subscription
     */
    public function bySubscription($subscriptionId)
    {
        $mappings = CustomerServicePincode::with(['customer', 'pincode'])
            ->where('subscription_id', $subscriptionId)
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $mappings,
        ]);
    }

    /**
     * Get all subscription mappings for a specific pincode
     */
    public function byPincode($pincodeId)
    {
        $mappings = CustomerServicePincode::with(['customer', 'subscription'])
            ->where('pincode_id', $pincodeId)
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $mappings,
        ]);
    }

    /**
     * Get all mappings for a specific customer
     */
    public function byCustomer($customerId)
    {
        $mappings = CustomerServicePincode::with(['subscription', 'pincode'])
            ->where('customer_id', $customerId)
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $mappings,
        ]);
    }
}
