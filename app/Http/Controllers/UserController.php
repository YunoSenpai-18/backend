<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        return response()->json(User::all());
    }

    // Get single user
    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }
}
