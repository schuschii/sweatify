<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutHistory extends Model
{
use hasFactory;


protected $fillable = [
    'user_id',
    'workout_id',
    'exercise_id',
    'reps',
    'weight'
];
public function user(){
    return $this->belongsTo(User::class);
}
public function exercise(){
    return $this->belongsTo(Exercise::class);
}
public function workout(){
    return $this->belongsTo(Workout::class);
}
}
