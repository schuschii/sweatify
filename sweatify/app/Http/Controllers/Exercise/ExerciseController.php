<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Models\Exercise;

class ExerciseController extends Controller
{
    public function index()
    {
        return Exercise::all();
    }

    public function show($id)
    {
        return Exercise::findOrFail($id);
    }

    public function filterByBodyPart($bodyPart)
    {
        return Exercise::where('bodyPart', $bodyPart)->get();
    }
}
