<?php

namespace App\Http\Controllers;

use App\Models\FaceRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FaceRegistrationController extends Controller
{
    /**
     * Register or update a face.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'face_id' => 'required|string',
            'name' => 'required|string|max:255',
            'signature' => 'required|array',
            'user_id' => 'required|exists:instructors,id',
            'facial_image' => 'required|string',
            'is_update' => 'boolean',
        ]);

        // 🖼️ Handle Base64 image saving
        $imageData = $validated['facial_image'];
        $imagePath = null;

        if (Str::startsWith($imageData, 'data:image')) {
            // Decode the base64 string
            [$meta, $encoded] = explode(',', $imageData, 2);
            $imageName = 'faces/' . uniqid('face_') . '.jpg';

            // Check if the instructor already has a registered face
            $existingRegistration = FaceRegistration::where('instructor_id', $validated['user_id'])->first();
            if ($existingRegistration) {
                // If there is an existing face, delete the old image
                Storage::disk('public')->delete($existingRegistration->facial_image);
                // Update the record
                $existingRegistration->update([
                    'name' => $validated['name'],
                    'signature' => $validated['signature'],
                    'facial_image' => $imageName,
                    'registered_at' => now(),
                ]);
                $record = $existingRegistration; // Use the updated record
            } else {
                // Save the new face registration if no existing record
                Storage::disk('public')->put($imageName, base64_decode($encoded));
                $imagePath = $imageName;

                $record = FaceRegistration::create([
                    'instructor_id' => $validated['user_id'],
                    'face_id' => $validated['face_id'],
                    'name' => $validated['name'],
                    'signature' => $validated['signature'],
                    'facial_image' => $imagePath,
                    'registered_at' => now(),
                ]);
            }
        }

        return response()->json([
            'message' => $validated['is_update']
                ? 'Face registration updated successfully.'
                : 'Face registration saved successfully.',
            'record' => $record->load('instructor'),
        ]);
    }

    /**
     * Optional: List all registered faces (for admin)
     */
    public function index()
    {
        return response()->json(
            FaceRegistration::with('instructor')->latest()->get()
        );
    }

    /**
     * Optional: View a single face record
     */
    public function show($id)
    {
        $record = FaceRegistration::with('instructor')->findOrFail($id);
        return response()->json($record);
    }
}
