<?php

namespace App\Http\Controllers;

use App\Models\WorkoutExerciseHistory;
use Illuminate\Http\Request;

class WorkoutHistoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'workout_id' => 'required',
            'exercise_data' => 'required | array',
            'exercise_data.*.exercise_name' => 'required|string|exists:exercises,name',
            'exercise_data.*.reps' => 'required|integer|min:1',
            'exercise_data.*.weight' => 'required|numeric|min:0',
        ]);
        $workoutHistory = WorkoutHistory::create([
            'user_id' => Auth::id(),
            'workout_id' => $request->workout_id,
        ]);
        foreach ($request->exercise_data as $exercise) {

            $exerciseModel = \App\Models\Exercise::where('name', $exercise['exercise_name'])->first();
            if (!$exerciseModel) {
                return response()->json(['error' => "Exercise '{$exercise['exercise_name']}' not found"], 400);
            }

            WorkoutExerciseHistory::create([
                'workout_history_id' => $workoutHistory->id,
                'exercise_id' => $exerciseModel->id,
                'reps' => $exercise['reps'],
                'weight' => $exercise['weight'],
                'extra_data' => json_encode($exercise['extra_data'] ?? null),
            ]);
        }

        return response()->json(['message' => 'Workout completed successfully!']);
    }
}
