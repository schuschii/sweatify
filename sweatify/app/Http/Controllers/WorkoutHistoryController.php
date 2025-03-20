<?php

namespace App\Http\Controllers;

use App\Models\WorkoutExerciseHistory;
use App\Models\WorkoutHistory;
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

    public function show($userId)
    {
        // Fetch workout history for the given user ID with eager loading of the necessary relationships
        $workoutHistory = WorkoutHistory::with('workout', 'exercise.exercise') // Load workout and exercises
        ->where('user_id', $userId)
            ->get();

        // Check if the user has any workout history
        if ($workoutHistory->isEmpty()) {
            return response()->json(['message' => 'No workout history found for this user'], 404);
        }

        // Map the data to include necessary workout history and exercise details
        $workoutData = $workoutHistory->map(function ($history) {
            // Ensure exercise data is available
            $exercises = $history->exercise->map(function ($exerciseHistory) {
                return [
                    'exercise_name' => $exerciseHistory->exercise->name, // Exercise name
                    'reps' => $exerciseHistory->reps, // Reps
                    'weight' => $exerciseHistory->weight // Weight
                ];
            });

            return [
                'workout_name' => $history->workout->name, // Workout name
                'date' => $history->created_at->format('M d, Y'), // Date formatted
                'exercise_names' => $exercises, // List of exercises with details
                'duration' => $history->duration ?? 'N/A' // Duration
            ];
        });

        return response()->json($workoutData);
    }

}
