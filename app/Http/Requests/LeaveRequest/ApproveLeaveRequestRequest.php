<?php

namespace App\Http\Requests\LeaveRequest;

use Illuminate\Foundation\Http\FormRequest;

class ApproveLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('approve_leave_requests');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:Approved,Rejected'],
        ];
    }
}
