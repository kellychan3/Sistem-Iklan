<div class="modal-header">
    <h5 class="modal-title">Edit Video</h5>
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
                <label>Preview Video</label>
                <video width="100%" class="rounded" controls>
                    <source src="{{ asset('storage/'.$video->file_path) }}" type="video/mp4">
                </video>
            </div>
            
            <div class="row mb-3 g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Urutan</label>
                    <input type="number" class="form-control" name="play_order" id="play_order" min="1"  value="{{ $video->play_order }}" required>
                </div>

                <div class="col-md-6">
                    <label for="is_active" class="form-label">Status</label>
                    <select class="form-select" name="is_active" id="is_active" required>
                        <option value="">Pilih Status</option>
                        <option value="1" {{ $video->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$video->is_active ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>
    </div>