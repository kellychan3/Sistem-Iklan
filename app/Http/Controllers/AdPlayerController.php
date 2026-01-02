<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class AdPlayerController extends Controller
{
    public function index()
    {
        $videos = Video::where('is_active', 1)
            ->orderBy('play_order')
            ->get()
            ->map(fn($v) => [
                'src' => asset('storage/' . $v->file_path),
            ]);

        return view('player', compact('videos'));
    }
}
