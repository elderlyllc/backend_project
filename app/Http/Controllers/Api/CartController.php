<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('details')->get();
        return response()->json($subscriptions);
    }
}