<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    // List all schedules with relations
    public function index()
    {
        return response()->json(
            Schedule::with(['instructor', 'checker'])->get()
        );
    }

    // Store new schedule
    public function store(Request $request)
    {
        $request->merge([
            'start_time' => Carbon::parse($request->start_time)->format('H:i'),
            'end_time'   => Carbon::parse($request->end_time)->format('H:i'),
        ]);

        $validated = $request->validate([
            'subject_code' => 'required|string|max:50',
            'subject' => 'required|string|max:255',
            'block' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day' => 'required|string|max:50',
            'room' => 'required|string|max:5',
            'instructor_id' => 'required|exists:instructors,id',
            'assigned_checker_id' => 'required|exists:users,id',
        ]);

        // ✅ Check for exact duplicate first
        $duplicate = Schedule::where('subject_code', $validated['subject_code'])
            ->where('block', $validated['block'])
            ->where('day', $validated['day'])
            ->where('start_time', $validated['start_time'])
            ->where('end_time', $validated['end_time'])
            ->where('room', $validated['room'])
            ->where('instructor_id', $validated['instructor_id'])
            ->where('assigned_checker_id', $validated['assigned_checker_id'])
            ->first();

        if ($duplicate) {
            return response()->json([
                'message' => 'This exact schedule already exists.'
            ], 422);
        }

        // ✅ Check for conflicts (same day + overlapping time + same room or same instructor)
        $conflict = Schedule::where('day', $validated['day'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($query) use ($validated) {
                        $query->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->where(function ($query) use ($validated) {
                $query->where('room', $validated['room'])
                    ->orWhere('instructor_id', $validated['instructor_id']);
            })
            ->first();

        if ($conflict) {
            return response()->json([
                'message' => 'Schedule conflict: Either the room or instructor is already occupied at this time.'
            ], 422);
        }

        // ✅ Save schedule if no conflict
        $schedule = Schedule::create($validated);

        return response()->json($schedule->load(['instructor', 'checker']), 201);
    }

    // Show single schedule
    public function show($id)
    {
        $schedule = Schedule::with(['instructor', 'checker'])->findOrFail($id);
        return response()->json($schedule);
    }

    // Update schedule
    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'subject_code' => 'sometimes|string|max:50',
            'subject' => 'sometimes|string|max:255',
            'block' => 'sometimes|string|max:50',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'day' => 'sometimes|string|max:50',
            'room' => 'sometimes|string|max:5',
            'instructor_id' => 'sometimes|exists:instructors,id',
            'assigned_checker_id' => 'sometimes|exists:users,id',
        ]);

        // Merge existing values with new ones (for proper conflict checking)
        $data = array_merge($schedule->toArray(), $validated);

        // ✅ Check for exact duplicate (excluding itself)
        $duplicate = Schedule::where('id', '!=', $schedule->id)
            ->where('subject_code', $data['subject_code'])
            ->where('block', $data['block'])
            ->where('day', $data['day'])
            ->where('start_time', $data['start_time'])
            ->where('end_time', $data['end_time'])
            ->where('room', $data['room'])
            ->where('instructor_id', $data['instructor_id'])
            ->where('assigned_checker_id', $data['assigned_checker_id'])
            ->first();

        if ($duplicate) {
            return response()->json([
                'message' => 'This exact schedule already exists.'
            ], 422);
        }

        // ✅ Check for conflicts (same day + overlapping time + same room or instructor, excluding itself)
        $conflict = Schedule::where('id', '!=', $schedule->id)
            ->where('day', $data['day'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                    });
            })
            ->where(function ($query) use ($data) {
                $query->where('room', $data['room'])
                    ->orWhere('instructor_id', $data['instructor_id']);
            })
            ->first();

        if ($conflict) {
            return response()->json([
                'message' => 'Schedule conflict: Either the room or instructor is already occupied at this time.'
            ], 422);
        }

        // ✅ Update schedule if no conflict
        $schedule->update($validated);

        return response()->json($schedule);
    }

    // Delete schedule
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Schedule deleted']);
    }

    public function checkerSchedules(Request $request)
    {
        $user = $request->user(); // logged-in checker
        $today = now()->format('l'); // e.g., "Monday"

        $schedules = Schedule::with(['instructor', 'checker'])
            ->where('assigned_checker_id', $user->id)
            ->where('day', $today)
            ->get();

        return response()->json($schedules);
    }
}