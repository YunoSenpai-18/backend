<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    // Get all instructors
    public function index()
    {
        // ensures photo_url appears even if $appends was not set
        $instructors = Instructor::all()->each->append('photo_url');
        return response()->json($instructors);
    }

    // Store new instructor
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'instructor_id' => 'required|string|max:20|unique:instructors',
            'department' => 'required|string|max:255',
            'email' => 'required|email|unique:instructors',
            'phone' => 'required|string|max:15|unique:instructors',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('instructors', 'public');
            $validated['photo'] = $path;
        }

        $instructor = Instructor::create($validated);

        return response()->json($instructor, 201);
    }

    // Show single instructor
    public function show($id)
    {
        $instructor = Instructor::findOrFail($id);
        $instructor->append('photo_url');
        return response()->json($instructor);
    }

    // Update instructor
    public function update(Request $request, $id)
    {
        $instructor = Instructor::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'instructor_id' => 'sometimes|string|max:20|unique:instructors,instructor_id,' . $instructor->id,
            'department' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:instructors,email,' . $instructor->id,
            'phone' => 'sometimes|string|max:15|unique:instructors,phone,' . $instructor->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($instructor->photo && Storage::disk('public')->exists($instructor->photo)) {
                Storage::disk('public')->delete($instructor->photo);
            }

            // Save new photo
            $path = $request->file('photo')->store('instructors', 'public');
            $validated['photo'] = $path;
        }

        $instructor->update($validated);

        return response()->json($instructor);
    }

    // Delete instructor
    public function destroy($id)
    {
        $instructor = Instructor::findOrFail($id);

        // Delete photo from storage if it exists
        if ($instructor->photo && Storage::disk('public')->exists($instructor->photo)) {
            Storage::disk('public')->delete($instructor->photo);
        }

        $instructor->delete();

        return response()->json(['message' => 'Instructor deleted successfully']);
        }
}
