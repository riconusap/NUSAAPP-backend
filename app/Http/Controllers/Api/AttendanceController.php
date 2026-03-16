<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Attendance\ClockInRequest;
use App\Http\Requests\Attendance\ClockOutRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends ApiController
{
    /**
     * Display a listing of attendances.
     */
    public function index(Request $request)
    {
        $query = Attendance::query();

        // Filter by employee
        if ($request->has('employee_id')) {
            $query->forEmployee($request->employee_id);
        }

        // Filter by site
        if ($request->has('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filter by date
        if ($request->has('date')) {
            $query->forDate($request->date);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Load relationships
        $query->with(['employee', 'site']);

        $attendances = $query->latest('date')->paginate($request->get('per_page', 15));

        return $this->success([
            'attendances' => AttendanceResource::collection($attendances),
            'pagination' => [
                'current_page' => $attendances->currentPage(),
                'last_page' => $attendances->lastPage(),
                'per_page' => $attendances->perPage(),
                'total' => $attendances->total(),
            ],
        ]);
    }

    /**
     * Clock in - create new attendance.
     */
    public function clockIn(ClockInRequest $request)
    {
        try {
            // Check if already clocked in today
            $existingAttendance = Attendance::where('employee_id', $request->employee_id)
                ->where('site_id', $request->site_id)
                ->where('date', $request->date ?? now()->toDateString())
                ->first();

            if ($existingAttendance) {
                return $this->error('Already clocked in for this site today', 400);
            }

            $attendance = Attendance::create([
                'employee_id' => $request->employee_id,
                'site_id' => $request->site_id,
                'date' => $request->date ?? now()->toDateString(),
                'clock_in' => now(),
                'latitude_in' => $request->latitude,
                'longitude_in' => $request->longitude,
                'selfie_path_in' => $request->selfie_path,
                'status' => $this->determineStatus(now()),
            ]);

            // Check if within site radius
            if (!$attendance->isWithinSiteRadius()) {
                return $this->error(
                    'Clock-in location is outside the allowed site radius',
                    400,
                    ['attendance' => new AttendanceResource($attendance->load(['employee', 'site']))]
                );
            }

            return $this->success(
                new AttendanceResource($attendance->load(['employee', 'site'])),
                'Clocked in successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to clock in: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Clock out - update existing attendance.
     */
    public function clockOut(ClockOutRequest $request)
    {
        try {
            $attendance = Attendance::where('employee_id', $request->employee_id)
                ->where('site_id', $request->site_id)
                ->where('date', $request->date ?? now()->toDateString())
                ->first();

            if (!$attendance) {
                return $this->error('No clock-in record found for today', 404);
            }

            if ($attendance->clock_out) {
                return $this->error('Already clocked out', 400);
            }

            $attendance->update([
                'clock_out' => now(),
                'selfie_path_out' => $request->selfie_path,
            ]);

            return $this->success(
                new AttendanceResource($attendance->load(['employee', 'site'])),
                'Clocked out successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to clock out: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified attendance.
     */
    public function show(Attendance $attendance)
    {
        return $this->success(
            new AttendanceResource($attendance->load(['employee', 'site']))
        );
    }

    /**
     * Determine attendance status based on clock-in time.
     */
    private function determineStatus($clockIn)
    {
        $hour = $clockIn->format('H');

        // Late if after 8:00 AM
        if ($hour >= 8 && $hour < 12) {
            return 'Late';
        }

        // Half-day if after 12:00 PM
        if ($hour >= 12) {
            return 'Half-day';
        }

        return 'Present';
    }
}
