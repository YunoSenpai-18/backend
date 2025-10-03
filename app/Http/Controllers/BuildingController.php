<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        return response()->json(Building::with('rooms')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:buildings',
        ]);
        $building = Building::create($validated);
        return response()->json($building, 201);
    }

    public function show($id)
    {
        return response()->json(Building::with('rooms')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $building = Building::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:buildings,name,' . $building->id,
        ]);
        $building->update($validated);
        return response()->json($building);
    }

    public function destroy($id)
    {
        $building = Building::findOrFail($id);
        $building->delete();
        return response()->json(['message' => 'Building deleted']);
    }
}
