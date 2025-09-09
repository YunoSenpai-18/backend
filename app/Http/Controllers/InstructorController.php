<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    // Get all instructors
    public function index()
    {
        return response()->json(Instructor::all());
    }

    // Store new instructor
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'instructor_id' => 'required|string|max:20|unique:instructors',
            'course' => 'required|string|max:255',
            'email' => 'required|email|unique:instructors',
            'phone' => 'required|string|max:15|unique:instructors',
            'photo' => 'nullable|string', // expect base64 string
        ]);

        $instructor = Instructor::create($validated);

        return response()->json($instructor, 201);
    }

    // Show single instructor
    public function show($id)
    {
        $instructor = Instructor::findOrFail($id);
        return response()->json($instructor);
    }

    // Update instructor
    public function update(Request $request, $id)
    {
        $instructor = Instructor::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'instructor_id' => 'sometimes|string|max:20|unique:instructors,instructor_id,' . $instructor->id,
            'course' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:instructors,email,' . $instructor->id,
            'phone' => 'sometimes|string|max:15|unique:instructors,phone,' . $instructor->id,
            'photo' => 'nullable|string',
        ]);

        $instructor->update($validated);

        return response()->json($instructor);
    }

    // Delete instructor
    public function destroy($id)
    {
        $instructor = Instructor::findOrFail($id);
        $instructor->delete();

        return response()->json(['message' => 'Instructor deleted']);
    }
}
