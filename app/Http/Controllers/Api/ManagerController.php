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
            ->with(['pincodeMapping.pincode', 'serviceAssignments.subscription'])
            ->get();

        // Format users for UI consumption (name, service, area, avatar initial)
        $formatted = $users->map(function ($user) {
            $pincodes = $user->pincodeMapping->map(function ($mapping) {
                $p = $mapping->pincode;
                return [
                    'id' => $p->id ?? null,
                    'pincode' => $p->pincode ?? null,
                    'city' => $p->city ?? null,
                    'state' => $p->state ?? null,
                ];
            })->unique('pincode')->values();

            $activeAssignment = $user->serviceAssignments->first(function ($a) {
                return method_exists($a, 'isActive') ? $a->isActive() : true;
            });

            $serviceName = $activeAssignment && $activeAssignment->subscription
                ? $activeAssignment->subscription->name
                : null;

            $initial = '';
            if (!empty($user->first_name)) {
                $initial = strtoupper(substr($user->first_name, 0, 1));
            } elseif (!empty($user->last_name)) {
                $initial = strtoupper(substr($user->last_name, 0, 1));
            }

            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'name' => $user->full_name,
                'avatar_initial' => $initial,
                'service' => $serviceName,
                'areas' => $pincodes,
            ];
        });

        return response()->json([
            'status' => true,
            'manager_id' => $manager->id,
            'pincode_ids' => $pincodeIds,
            'data' => $formatted,
        ]);
    }
}
