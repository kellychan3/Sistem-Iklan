<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'user_id',
        'action',
        'field',
        'old_value',
        'new_value'
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
