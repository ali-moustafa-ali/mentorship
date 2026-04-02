<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'title',
        'course_url',
        'total_videos',
        'completed_videos',
    ];

    protected $casts = [
        'month' => 'date',
    ];

    protected $appends = ['progress_percentage'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_videos <= 0) {
            return 0;
        }
        return round(($this->completed_videos / $this->total_videos) * 100);
    }
}
