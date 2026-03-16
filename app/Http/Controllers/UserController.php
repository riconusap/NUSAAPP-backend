<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('view_users');

        $query = User::with(['employee', 'roles']);

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter by employee
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // dd($query->toSql(), $query->getBindings());

        $users = $query->orderBy('name')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => new UserResource($user->load(['employee', 'roles'])),
        ], 201);
    }

    /**
     * Display the specified user.
     */
    public function show($user)
    {
        $user = User::with(['employee', 'roles'])->find($user);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        } else {
            return response()->json([
                'success' => true,
                'data' => new UserResource($user),
            ]);
        }
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => new UserResource($user->load(['employee', 'roles'])),
        ]);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete_users');

        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account',
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
