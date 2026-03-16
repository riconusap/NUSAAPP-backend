<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LeaveRequest\StoreLeaveRequestRequest;
use App\Http\Requests\LeaveRequest\ApproveLeaveRequestRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends ApiController
{
    /**
     * Display a listing of leave requests.
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee', 'approver']);

        // Filter by employee
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->has('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $leaveRequests = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'leave_requests' => LeaveRequestResource::collection($leaveRequests),
            'pagination' => [
                'current_page' => $leaveRequests->currentPage(),
                'last_page' => $leaveRequests->lastPage(),
                'per_page' => $leaveRequests->perPage(),
                'total' => $leaveRequests->total(),
            ],
        ]);
    }

    /**
     * Store a newly created leave request.
     */
    public function store(StoreLeaveRequestRequest $request)
    {
        try {
            $data = $request->validated();
            $data['status'] = 'Pending';

            $leaveRequest = LeaveRequest::create($data);

            return $this->success(
                new LeaveRequestResource($leaveRequest->load(['employee', 'approver'])),
                'Leave request created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create leave request: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified leave request.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        return $this->success(
            new LeaveRequestResource($leaveRequest->load(['employee', 'approver'])),
            'Leave request retrieved successfully'
        );
    }

    /**
     * Approve or reject a leave request.
     */
    public function approve(ApproveLeaveRequestRequest $request, LeaveRequest $leaveRequest)
    {
        try {
            if (!$leaveRequest->isPending()) {
                return $this->error('Leave request has already been processed', 400);
            }

            $leaveRequest->update([
                'status' => $request->status,
                'approved_by' => $request->user()->id,
            ]);

            return $this->success(
                new LeaveRequestResource($leaveRequest->load(['employee', 'approver'])),
                'Leave request ' . strtolower($request->status) . ' successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to process leave request: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified leave request.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        try {
            if (!$leaveRequest->isPending()) {
                return $this->error('Cannot delete processed leave request', 400);
            }

            $leaveRequest->delete();

            return $this->success(null, 'Leave request deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete leave request: ' . $e->getMessage(), 500);
        }
    }
}
