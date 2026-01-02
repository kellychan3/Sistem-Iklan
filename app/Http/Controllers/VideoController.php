<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::query();

        if ($request->show_inactive == 1) {
            $query->where('is_active', 0);
        } else {
            $query->where('is_active', 1);
        }
        
        if ($request->filled('search')){
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $videos = $query->orderBy('play_order')->paginate(10);

        $search = $request->search;

        if ($request->ajax()){
            return response()->json([
                'table' => view('partials.table_video_rows', [
                    'videos' => $videos,
                    'search' => $search
                ])->render(),
                'pagination' => $videos->links('pagination::bootstrap-5')->render()
            ]);
        }

        return view('videos', compact('videos', 'search'));
    }

    public function createModal()
    {
        $videos = Video::where('is_active', 1)->orderBy('play_order')->get();
        $html = view('partials.modal_tambah_video', compact('videos'))->render();
        return response()->json(['html' => $html]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'video' => 'required|mimes:mp4,webm|max:51200',
            'duration' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'position' => 'required_if:is_active,1|in:first,last,after',
            'after_id' => 'required_if:position,after|exists:videos,id',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $playOrder = null;

            if ($validated['is_active']) {
                
                switch ($validated['position']) {
                    case 'first':
                        $playOrder = 1;
                        break;

                    case 'last':
                        $playOrder = Video::count() + 1;
                        break;

                    case 'after':
                        $after = Video::findOrFail($validated['after_id']);
                        $playOrder = $after->play_order + 1;
                        break;
                }

                Video::where('is_active', true)
                    ->where('play_order', '>=', $playOrder)->increment('play_order');
            }
        
            $path = $request->file('video')->store('videos', 'public');

            Video::create([
                'title' => $validated['title'],
                'file_path' => $path,
                'duration' => $validated['duration'],
                'is_active' => $validated['is_active'],
                'play_order' => $playOrder,
                'added_by' => Auth::id(),
            ]);
        });

        return redirect()->back()->with('success', 'Video berhasil ditambahkan');
    }

    public function editModal($id)
    {
        $video = Video::findOrFail($id);
        $videos = Video::where('is_active', 1)->orderBy('play_order')->get();

        $html = view('partials.modal_edit_video', compact('video','videos'))->render();
        return response()->json(['html' => $html]);
    }
    
    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'position'  => 'nullable|in:first,last,after',
            'after_id'  => 'required_if:position,after|exists:videos,id',
        ]);

        DB::transaction(function () use ($validated, $video) {

            $oldActive = $video->is_active;
            $oldOrder  = $video->play_order;

            $video->update([
                'title'     => $validated['title'],
                'is_active' => $validated['is_active'],
            ]);

            foreach (['title', 'is_active'] as $field) {
                if ($video->wasChanged($field)) {
                    VideoLog::create([
                        'video_id'  => $video->id,
                        'user_id'   => Auth::id(),
                        'action'    => 'update',
                        'field'     => $field,
                        'old_value' => $video->getOriginal($field),
                        'new_value' => $video->$field,
                    ]);
                }
            }

            if ($oldActive == 1 && $validated['is_active'] == 0) {

                Video::where('is_active', 1)
                    ->where('play_order', '>', $oldOrder)
                    ->decrement('play_order');

                $video->update(['play_order' => null]);

                VideoLog::create([
                    'video_id'  => $video->id,
                    'user_id'   => Auth::id(),
                    'action'    => 'deactivate',
                    'field'     => 'play_order',
                    'old_value' => $oldOrder,
                    'new_value' => null,
                ]);

                return;
            }

            if ($validated['is_active'] == 1 && !empty($validated['position'])) {

                if ($oldActive == 1 && $oldOrder !== null) {
                    Video::where('is_active', 1)
                        ->where('play_order', '>', $oldOrder)
                        ->decrement('play_order');
                }

                $newOrder = null;

                if ($validated['position'] === 'first') {

                    Video::where('is_active', 1)
                        ->increment('play_order');

                    $newOrder = 1;
                }

                elseif ($validated['position'] === 'last') {

                    $maxOrder = Video::where('is_active', 1)->max('play_order') ?? 0;
                    $newOrder = $maxOrder + 1;
                }

                elseif ($validated['position'] === 'after') {

                    $afterVideo = Video::where('is_active', 1)
                        ->where('id', '!=', $video->id)
                        ->findOrFail($validated['after_id']);

                    $newOrder = $afterVideo->play_order + 1;

                    Video::where('is_active', 1)
                        ->where('play_order', '>=', $newOrder)
                        ->increment('play_order');
                }

                $video->update(['play_order' => $newOrder]);

                VideoLog::create([
                    'video_id'  => $video->id,
                    'user_id'   => Auth::id(),
                    'action'    => 'reorder',
                    'field'     => 'play_order',
                    'old_value' => $oldOrder,
                    'new_value' => $newOrder,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Video berhasil diperbarui');
    }

    public function destroy(Video $video)
    {
        DB::transaction(function() use($video) {
            $oldOrder = $video->play_order;
            $wasActive = $video->is_active;

            if ($wasActive && $oldOrder !== null) {
                Video::where('is_active', 1)
                    ->where('play_order', '>', $oldOrder)
                    ->decrement('play_order');
            }

            $video->update([
                'is_active'  => 0,
                'play_order'=> null,
            ]);

            $video->delete();

            VideoLog::create([
                'video_id' => $video->id,
                'user_id' => Auth::id(),
                'action' => 'delete',
                'field' => null,
                'old_value' => json_encode($video),
                'new_value' => null
            ]);
        });

        return redirect()->route('videos')->with('success', 'Video berhasil dihapus');
    }
}
