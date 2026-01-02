@forelse ($videos as $index => $video)
<tr>
    <td class="text-center" width="60">{{ $videos->firstItem() + $index }}</td>
    <td width="240">
        <video width="220" muted preload="metadata" style="border-radius:6px">
            <source src="{{ asset('storage/'.$video->file_path) }}" type="video/mp4">
        </video>
    </td>
    
    <td>{{ $video->title }}</td>

    <td class="text-center" width="100">{{ gmdate('i:s', $video->duration) }}</td>

    <td class="text-center" width="100">
        <span class="badge py-2 {{ $video->is_active ? 'bg-success' : 'bg-secondary' }}">
            {{ $video->is_active? 'Aktif' : 'Nonaktif' }}
        </span>
    </td>

    <td class="text-center" width="100">{{ $video->play_order ?? '-' }}</td>

    <td width="160" class="text-center">
        <button class="btn btn-sm btn-warning edit-video-btn" data-url="{{ route('videos.edit-modal', $video->id) }}"><i class="bi bi-pencil"></i> Edit</button>
        <form action="{{ route('videos.destroy', $video->id) }}" method="POST" class="delete-form d-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-sm btn-danger hapus-video-btn"
                data-title={{ $video->title }}>
                <i class="bi bi-trash"></i> Hapus
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center text-muted">
        @if(!empty($search))
            Video tidak ditemukan
        @else
            Belum ada video
        @endif
    </td>
</tr>
@endforelse