<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('details')->get();
        return response()->json($subscriptions);
    }
    public function customer_subscription_pin_mapping()
    {
        
    }
}