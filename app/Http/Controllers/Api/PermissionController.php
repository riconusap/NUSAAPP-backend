<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;

class PermissionController extends ApiController
{
    /**
     * Display a listing of permissions.
     */
    public function index(Request $request)
    {
        $this->authorize('view_roles');

        $query = Permission::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('per_page')) {
            $permissions = $query->orderBy('name')->paginate($request->get('per_page', 15));

            return $this->success([
                'permissions' => PermissionResource::collection($permissions),
                'pagination' => [
                    'current_page' => $permissions->currentPage(),
                    'last_page' => $permissions->lastPage(),
                    'per_page' => $permissions->perPage(),
                    'total' => $permissions->total(),
                ],
            ]);
        }

        $permissions = $query->orderBy('name')->get();

        return $this->success([
            'permissions' => PermissionResource::collection($permissions),
        ]);
    }
}
