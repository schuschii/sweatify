<?php

namespace App\Http\Controllers;


use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class WorkoutController extends Controller
{

    public function index(Request $request): JsonResponse
    {

        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $workouts = Workout::offset($offset)->limit($limit)->get();
        $total = Workout::count();

        return response()->json([
            'workouts' => $workouts,
            'total' => $total,
            'limit' => (int) $limit,
            'offset' => (int) $offset
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'exercise_ids' => 'required|array',
                'exercise_ids.*' => 'exists:exercises,id',
            ]);

            $workout = Workout::create($validated);

            return response()->json($workout, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function show($id)
    {
        $workout = Workout::findOrFail($id);

        $exercises = Exercise::whereIn('id', $workout->exercise_ids)->get(['id', 'name']);

        return response()->json([
            'id' => $workout->id,
            'name' => $workout->name,
            'description' => $workout->description,
            'exercises' => $exercises, // Include exercises with their names
            'created_at' => $workout->created_at,
            'updated_at' => $workout->updated_at,
        ]);
    }

    public function update(Request $request, Workout $workout): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'exercise_ids' => 'sometimes|array',
                'exercise_ids.*' => 'exists:exercises,id',
            ]);

            $workout->update($validated);

            return response()->json($workout);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function destroy($id): JsonResponse
    {
        $workout = Workout::findOrFail($id);
        $workout->delete();
        return response()->json(['message' => 'Workout deleted successfully']);
    }
}
