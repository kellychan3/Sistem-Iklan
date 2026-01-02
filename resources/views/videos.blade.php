@extends('layouts.main')

@section('container')
    
<div class="content mt-4">

    <h3 class="mb-4">Selamat Datang, {{ Auth::user()->name }}</h3>

    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">

        <button class="btn btn-primary custom-button-btn tambah-video-btn" 
            data-bs-toggle="modal" data-bs-target="#modalTambahVideo"
            data-url="{{ route('videos.create-modal') }}">+ Video</button>

        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" id="lihatNonaktif">
            <label class="form-check-label" for="lihatNonaktif">
                Lihat video non-aktif
            </label>
        </div>

        <div class="ms-auto">
            <div class="input-group" style="width: 400px;">
                <input type="text" id="searchTable" class="form-control" placeholder="Cari ...">
                <button class="btn btn-outline-secondary" id="searchTableBtn">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <table class="table table-hover table-bordered mt-3 shadow-sm rounded">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>Preview</th>
                <th>Judul</th>
                <th>Durasi</th>
                <th>Status</th>
                <th>Urutan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="videoTableBody">
            @include('partials.table_video_rows', ['videos' => $videos])
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4" id="videoPagination">
        {{ $videos->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection

<!-- Modal Tambah Video--> 
<div class="modal fade" id="modalTambahVideo" tabindex="-1"> 
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modalTambahVideoContent"> 
            <div class="p-3 text-center text-muted">Memuat...</div>
        </div>
    </div>
</div>

<!-- Modal Edit Video--> 
<div class="modal fade" id="modalEditVideo" tabindex="-1"> 
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modalEditVideoContent"> 
            <div class="p-3 text-center text-muted">Memuat...</div>
        </div>
    </div>
</div>
