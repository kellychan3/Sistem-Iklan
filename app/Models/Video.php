<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded=["id"];
    protected $dates = ["deleted_at"];

    public function addedBy()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::restored(function ($video) {

            $video->is_active = 1;

            $maxOrder = Video::where('is_active', 1)
                ->whereNull('deleted_at')
                ->max('play_order') ?? 0;

            $video->play_order = $maxOrder + 1;

            $video->save();
        });
    }

}
