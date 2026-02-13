<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicVersion extends Model
{
    protected $fillable = ['topic_id', 'title', 'body', 'version_number', 'change_note', 'created_at'];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
