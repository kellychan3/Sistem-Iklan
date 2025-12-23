@forelse ($videos as $index => $video)
<tr>
    <td class="text-center" width="60">{{ $index + 1 }}</td>
    <td width="240">
        <video width="220" muted preload="metadata" style="border-radius:6px">
            <source src="{{ asset('storage/'.$video->file_path) }}" type="video/mp4">
        </video>
    </td>
    
    <td>{{ $video->title }}</td>

    <td class="text-center" width="100">{{ gmdate('i:s', $video->duration) }}</td>

    <td class="text-center" width="100">
        <span class="badge {{ $video->is_active ? 'bg-success' : 'bg-secondary' }}">
            {{ $video->is_active? 'Aktif' : 'Nonaktif' }}
        </span>
    </td>

    <td class="text-center" width="100">{{ $video->play_order }}</td>

    <td width="160" class="text-center">
        <button class="btn btn-sm btn-warning py-2 edit-video-btn" data-url="{{ route('videos.edit-modal', $video->id) }}"><i class="bi bi-pencil"></i> Edit</button>
        <button class="btn btn-sm btn-danger py-2"><i class="bi bi-trash"></i> Hapus</button>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center text-muted">
        Belum ada video
    </td>
</tr>
@endforelse