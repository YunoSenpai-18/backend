<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return response()->json(Room::with(['building', 'checker'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:20',
            'building_id' => 'required|exists:buildings,id',
            'checker_id'  => 'required|exists:users,id',
        ]);

        $room = Room::create($validated);
        return response()->json($room->load(['building', 'checker']), 201);
    }

    public function show($id)
    {
        return response()->json(Room::with(['building', 'checker'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'room_number' => 'sometimes|string|max:20',
            'building_id' => 'sometimes|exists:buildings,id',
            'checker_id'  => 'sometimes|exists:users,id',
        ]);

        $room->update($validated);
        return response()->json($room->load(['building', 'checker']));
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return response()->json(['message' => 'Room deleted']);
    }
}
