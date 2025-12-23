<div class="header d-flex justify-content-between align-items-center gap-3 p-3 bg-white rounded shadow-sm overflow-hidden">

    <div class="d-flex align-items-center flex-shrink-0">
        <img src="{{ asset('img/logo.png') }}" class="img-fluid logo">
        <div class="ms-3">
            <p class="mb-0 fw-bold">Sistem Iklan</p>
            <p class="mb-0 text-muted">RS Awal Bros A.Ayani</p>
        </div>
    </div>

    <div class="flex-shrink-0 d-flex align-items-center">
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="button" id="btnLogout" class="btn btn-danger px-2 py-2" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>

</div>