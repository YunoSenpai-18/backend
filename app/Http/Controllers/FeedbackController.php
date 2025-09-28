<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // List all feedback (Admins only)
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'Admin') {
            // Admin can see ALL feedback
            return response()->json(
                Feedback::with('checker:id,full_name,school_id,email')
                    ->latest()
                    ->get()
            );
        }

        if ($user->role === 'Checker') {
            // Checker can only see their OWN feedback
            return response()->json(
                Feedback::where('checker_id', $user->id)
                    ->latest()
                    ->get()
            );
        }

        // Any other role → forbidden
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Store new feedback (Checker submits)
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Checker') {
            return response()->json(['message' => 'Only checkers can submit feedback'], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $feedback = Feedback::create([
            'checker_id' => $user->id,
            'message'    => $validated['message'],
        ]);

        return response()->json($feedback, 201);
    }

    // Show single feedback (Admins only)
    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'Admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(
            Feedback::with('checker:id,full_name,school_id,email')->findOrFail($id)
        );
    }

    // Update feedback status (Admins only)
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'Admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $feedback = Feedback::findOrFail($id);

        $validated = $request->validate([
            'status'         => 'required|in:Pending,Accepted,Declined',
            'admin_response' => 'nullable|string',
        ]);

        $feedback->update($validated);

        return response()->json($feedback);
    }

    // Delete feedback (Admins only, optional)
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role !== 'Admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json(['message' => 'Feedback deleted successfully']);
    }
}
