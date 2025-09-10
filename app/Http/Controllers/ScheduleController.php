<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'subject_code' => 'required|string|max:50',
            'subject' => 'required|string|max:255',
            'block' => 'required|string|max:50',
            'time' => 'required|string|max:50',
            'day' => 'required|string|max:50',
            'room' => 'required|string|max:5',
            'instructor_id' => 'required|exists:instructors,id',
            'assigned_checker_id' => 'required|exists:users,id',
        ]);

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
            'time' => 'sometimes|string|max:50',
            'day' => 'sometimes|string|max:50',
            'room' => 'sometimes|string|max:5',
            'instructor_id' => 'sometimes|exists:instructors,id',
            'assigned_checker_id' => 'sometimes|exists:users,id',
        ]);

        $schedule->update($validated);

        return response()->json($schedule->load(['instructor', 'checker']));
    }

    // Delete schedule
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Schedule deleted']);
    }
}