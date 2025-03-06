<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    // Get all exercises
    public function index()
    {
        return Exercise::all();
    }

    // Get a single exercise by ID
    public function show($id)
    {
        return Exercise::findOrFail($id);
    }

    // Get exercises filtered by bodyPart
    public function filterByBodyPart($bodyPart)
    {
        return Exercise::where('bodyPart', $bodyPart)->get();
    }
}
