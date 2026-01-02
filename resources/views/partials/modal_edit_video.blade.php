<div class="modal-header">
    <h5 class="modal-title">Edit Video {{ $video->title }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

    <div class="modal-body">
        <form action="{{ route('videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
             @method('PUT')

            <div class="mb-3">
                <label class="form-label">Judul Video</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ $video->title }}" required>
            </div>
            
            <div class="mb-3">
                <label for="is_active" class="form-label">Status</label>
                <select class="form-select" name="is_active" id="is_active" required>
                <option value="">Pilih Status</option>
                    <option value="1" {{ $video->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$video->is_active ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Urutan</label>
                <select name="position" class="form-select" id="positionSelect" disabled>
                    <option value="" hidden></option>
                    <option value="first" {{ $video->play_order == 1 ? 'selected' : '' }}> Pertama</option>
                    <option value="last" {{ $video->play_order == $video->max('play_order') ? 'selected' : '' }}>Terakhir</option>
                    <option value="after" {{ $video->play_order > 1 && $video->play_order < $video->max('play_order') ? 'selected' : '' }}>Setelah video tertentu</option>
                </select>

                <select name="after_id" class="form-select mt-2 d-none" id="afterVideo" disabled>
                    @foreach($videos as $v)
                        @if ($v->id !== $video->id)
                            <option value="{{ $v->id }}">
                                {{ $v->play_order }} - {{ $v->title }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>
    </div>