<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPincodeMapping;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * Get all users (role_id = 1) mapped to the same pincode(s) as the given manager.
     *
     * Flow:
     * 1. Check the given user is a manager (role_id = 3).
     * 2. Get the pincode(s) mapped to that manager.
     * 3. Get the users (role_id = 1) mapped to the same pincode(s).
     */
    public function getMappedUsers($managerId)
    {
        $manager = User::find($managerId);

        if (!$manager) {
            return response()->json([
                'status'  => false,
                'message' => 'Manager not found',
            ], 404);
        }

        if ((int) $manager->role_id !== 3) {
            return response()->json([
                'status'  => false,
                'message' => 'User is not a manager',
            ], 403);
        }

        $pincodeIds = UserPincodeMapping::where('user_id', $manager->id)
            ->pluck('pincode_id');

        if ($pincodeIds->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No pincode mapped to this manager',
            ], 404);
        }

        $users = User::where('role_id', 1)
            ->whereHas('pincodeMapping', function ($query) use ($pincodeIds) {
                $query->whereIn('pincode_id', $pincodeIds);
            })
            ->with(['pincodeMapping.pincode'])
            ->get();

        return response()->json([
            'status'      => true,
            'manager_id'  => $manager->id,
            'pincode_ids' => $pincodeIds,
            'data'        => $users,
        ]);
    }
}
