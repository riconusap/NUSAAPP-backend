<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;

class RoleController extends ApiController
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        $this->authorize('view_roles');

        $query = Role::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Load permissions if requested
        if ($request->has('with_permissions') && $request->with_permissions) {
            $query->with('permissions');
        }

        // Check if pagination is requested
        if ($request->has('per_page')) {
            $roles = $query->orderBy('name')->paginate($request->get('per_page', 15));

            return $this->success([
                'roles' => RoleResource::collection($roles),
                'pagination' => [
                    'current_page' => $roles->currentPage(),
                    'last_page' => $roles->lastPage(),
                    'per_page' => $roles->perPage(),
                    'total' => $roles->total(),
                ],
            ]);
        }

        // Return all roles without pagination
        $roles = $query->orderBy('name')->get();

        return $this->success([
            'roles' => RoleResource::collection($roles),
        ]);
    }

    /**
     * Display access details for a specific role.
     */
    public function show(Role $role)
    {
        $this->authorize('view_roles');

        $role->load('permissions');

        return $this->success([
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'total_access' => $role->permissions->count(),
                'access_detail' => $role->permissions->map(function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'guard_name' => $permission->guard_name,
                    ];
                })->values(),
                'created_at' => $role->created_at?->toISOString(),
                'updated_at' => $role->updated_at?->toISOString(),
            ],
        ]);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $this->authorize('create_roles');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')],
            'guard_name' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return $this->success([
            'role' => new RoleResource($role->load('permissions')),
        ], 'Role created successfully', 201);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('edit_roles');

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'guard_name' => ['sometimes', 'required', 'string', 'max:255'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->update([
            'name' => $validated['name'] ?? $role->name,
            'guard_name' => $validated['guard_name'] ?? $role->guard_name,
        ]);

        if (array_key_exists('permissions', $validated)) {
            $role->syncPermissions($validated['permissions'] ?? []);
        }

        return $this->success([
            'role' => new RoleResource($role->load('permissions')),
        ], 'Role updated successfully');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete_roles');

        if ($role->users()->exists()) {
            return $this->error('Cannot delete role that is assigned to users', 422);
        }

        $role->delete();

        return $this->success(null, 'Role deleted successfully');
    }
}
