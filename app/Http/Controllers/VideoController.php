<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::where('is_active', 1)
            ->orderBy('play_order')
            ->get();

        return view('videos', compact('videos'));
    }

    public function createModal()
    {
        $html = view('partials.modal_tambah_video')->render();
        return response()->json(['html' => $html]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'video' => 'required|mimes:mp4,webm|max:51200',
            'duration' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'play_order' => 'required|integer',
        ]);

        $path = $request->file('video')->store('videos', 'public');

        Video::create([
            'title' => $validated['title'],
            'file_path' => $path,
            'duration' => $validated['duration'],
            'is_active' => $validated['is_active'],
            'play_order' => $validated['play_order'],
            'added_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Video berhasil ditambahkan');
    }

    public function editModal($id)
    {
        $video = Video::findOrFail($id);
        $html = view('partials.modal_edit_video', compact('video'))->render();
        return response()->json(['html' => $html]);
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'play_order' => 'required|integer|min:1',
        ]);

        foreach($validated as $field => $newValue) {
            $oldValue = $video->$field;
            if ($oldValue != $newValue) {
                VideoLog::create([
                    'video_id' => $video->id,
                    'user_id' => Auth::id(),
                    'action' => 'update',
                    'field' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue
                ]);
            }
        }
        $video->update($validated);

        return redirect()->back()->with('success', 'Video berhasil diperbarui');
    }
}
