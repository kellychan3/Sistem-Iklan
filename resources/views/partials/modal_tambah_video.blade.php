<div class="modal-header">
    <h5 class="modal-title">Tambah Video</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

    <div class="modal-body">
        <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Judul Video</label>
                <input type="text" class="form-control" name="title" id="title" required>
            </div>

            <div class="mb-3">
                <label for="video_file" class="form-label">File Video (MP4)</label>
                <input type="file" class="form-control" id="video" name="video" accept="video/mp4,video/webm" required>
            </div>

            <input type="hidden" name="duration" id="duration">

            <div class="mb-3">
                <label for="is_active" class="form-label">Status</label>
                <select class="form-select" name="is_active" id="is_active" required>
                    <option value="">Pilih Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Urutan</label>
                    <select name="position" class="form-select" id="positionSelect" disabled>
                        <option value="" selected hidden></option>
                        <option value="last">Terakhir</option>
                        <option value="first">Pertama</option>
                        <option value="after">Setelah video tertentu</option>
                    </select>

                    <select name="after_id" class="form-select mt-2 d-none" id="afterVideo" disabled>
                        @foreach($videos as $video)
                            <option value="{{ $video->id }}">{{ $video->play_order }} - {{ $video->title }}</option>
                        @endforeach
                    </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>
    </div>