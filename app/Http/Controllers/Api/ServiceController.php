<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ServiceController extends Controller
{
    /**
     * Get all services
     */
    public function index()
    {
        try {
            $services = Service::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $services,
                'message' => 'Services retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch services: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get services by category
     */
    public function getByCategory($category)
    {
        try {
            $services = Service::where('category', $category)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $services,
                'message' => 'Services retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch services: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get services by type
     */
    public function getByType($serviceType)
    {
        try {
            $services = Service::where('service_type', $serviceType)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $services,
                'message' => 'Services retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch services: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single service by ID
     */
    public function show($id)
    {
        try {
            $service = Service::find($id);

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $service,
                'message' => 'Service retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch service: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new service
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
                'category' => 'nullable|string',
                'price' => 'nullable|numeric',
                'service_type' => 'nullable|string',
                'duration_minutes' => 'nullable|integer',
                'status' => 'nullable|string|in:active,inactive',
            ]);

            $validated['created_by'] = auth()->id() ?? null;
            $service = Service::create($validated);

            return response()->json([
                'success' => true,
                'data' => $service,
                'message' => 'Service created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create service: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update service
     */
    public function update(Request $request, $id)
    {
        try {
            $service = Service::find($id);

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'nullable|string',
                'description' => 'nullable|string',
                'category' => 'nullable|string',
                'price' => 'nullable|numeric',
                'service_type' => 'nullable|string',
                'duration_minutes' => 'nullable|integer',
                'status' => 'nullable|string|in:active,inactive',
            ]);

            $service->update($validated);

            return response()->json([
                'success' => true,
                'data' => $service,
                'message' => 'Service updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update service: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete service
     */
    public function destroy($id)
    {
        try {
            $service = Service::find($id);

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'Service deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete service: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get services by category with pagination
     */
    public function getCategoryList()
    {
        try {
            $categories = Service::where('status', 'active')
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category');

            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Categories retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories: ' . $e->getMessage()
            ], 500);
        }
    }
}
