function bindStatusOrder(statusSelect, orderInput, afterVideo = null) {
    const update = () => {
        const isActive = statusSelect.value === '1';

        orderInput.disabled = !isActive;
        if (afterVideo) {
            afterVideo.disabled = !isActive;
        }

        if (isActive) {
            if (orderInput.tagName === 'SELECT') {
                if (!orderInput.value) orderInput.value = 'last';
            } else {
                orderInput.required = true;
            }
        } else {
            if (orderInput.tagName === 'SELECT') {
                orderInput.value = '';
                if (afterVideo) {
                    afterVideo.value = '';
                    afterVideo.classList.add('d-none');
                }
            } else {
                orderInput.required = false;
                orderInput.value = '';
            }
        }

        if (afterVideo) {
            afterVideo.classList.toggle('d-none', orderInput.value !== 'after');
        }
    };

    update(); 
    statusSelect.addEventListener('change', update);

    if (afterVideo) {
        orderInput.addEventListener('change', () => {
            afterVideo.classList.toggle('d-none', orderInput.value !== 'after');
        });
    }
}

function initTambahVideo() {

    const modal = document.getElementById('modalTambahVideo');
    
    modal.addEventListener('show.bs.modal', async function(e) {
        const btn = e.relatedTarget;
        if (!btn) return;

        const url = btn.dataset.url;

        const content = document.getElementById('modalTambahVideoContent');
        content.innerHTML = '<div class="p-3 text-center">Memuat...</div>';

        try {
            const res = await fetch(url);
            const data = await res.json();
            content.innerHTML = data.html;

            const statusSelect = content.querySelector('#is_active');
            const positionSelect = content.querySelector('#positionSelect');
            const afterVideo = content.querySelector('#afterVideo');

            if (statusSelect && positionSelect && afterVideo) {
                bindStatusOrder(statusSelect, positionSelect, afterVideo);
            }

            const inputVideo = content.querySelector('#video');
            const inputDuration = content.querySelector('#duration');

            if (inputVideo && inputDuration) {
                inputVideo.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if(!file) return;

                    const video = document.createElement('video');
                    video.preload = 'metadata';

                    video.onloadedmetadata = function () {
                        URL.revokeObjectURL(video.src);
                        inputDuration.value = Math.floor(video.duration);
                    };

                    video.src = URL.createObjectURL(file);
                });
            }
        } catch (error) {
            content.innerHTML = '<div class="p-3 text-danger text-center">Gagal memuat data.</div>';
        }
    });
}

function initEditVideo() {

    document.addEventListener('click', async function (e) {

        const btn = e.target.closest('.edit-video-btn');
        if (!btn) return;

        const modalContent = document.getElementById('modalEditVideoContent');
        modalContent.innerHTML = '<div class="p-3 text-center">Memuat...</div>';

        const url = btn.dataset.url;

        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('HTTP error ' + res.status);

            const data = await res.json();
            modalContent.innerHTML = data.html;

            const statusSelect = modalContent.querySelector('#is_active');
            const positionSelect = modalContent.querySelector('#positionSelect');
            const afterVideo = modalContent.querySelector('#afterVideo');

            if (statusSelect && positionSelect && afterVideo) {
                bindStatusOrder(statusSelect, positionSelect, afterVideo);
            }

            const modal = new bootstrap.Modal(
                document.getElementById('modalEditVideo')
            );
            modal.show();

        } catch (error) {
            console.error(error);
            modalContent.innerHTML =
                '<div class="p-3 text-danger text-center">Gagal memuat data.</div>';
        }
    });
}

function initHapusVideo() {

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.hapus-video-btn');
        if (!btn) return;

        e.preventDefault();

        const form = btn.closest('.delete-form');
        const name = btn.dataset.name ?? 'video ini';

        Swal.fire({
            title: `Hapus ${name}?`,
            text: 'Video akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
}

async function loadVideos(params = {}) {
    const url = new URL(window.routes.videos);

    Object.entries(params).forEach(([key, value]) => {
        if (value !== '' && value !== null && value !== undefined) {
            url.searchParams.set(key, value);
        }
    });

    try {
        const res = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await res.json();

        document.getElementById('videoTableBody').innerHTML = data.table;
        document.getElementById('videoPagination').innerHTML = data.pagination;

    } catch (err) {
        console.error('Load videos failed:', err);
    }
}

function initFilterStatus() {
    $('#lihatNonaktif').on('change', function () {
        loadVideos({
            show_inactive: this.checked ? 1 : 0,
            search: $('#searchTable').val(),
            page: 1
        });
    });
}

function initPagination() {
    document.addEventListener('click', function (e) {
        const link = e.target.closest('#videoPagination a');
        if (!link) return;

        e.preventDefault();

        const page = new URL(link.href).searchParams.get('page');

        loadVideos({
            page,
            show_inactive: $('#lihatNonaktif').is(':checked') ? 1 : 0,
            search: $('#searchTable').val()
        });
    });
}

function initSearch() {
    $('#searchTableBtn').on('click', function () {
        loadVideos({
            search: $('#searchTable').val().trim(),
            show_inactive: $('#lihatNonaktif').is(':checked') ? 1 : 0,
            page: 1
        });
    });

    $('#searchTable').on('keypress', function (e) {
        if(e.key == 'Enter') {
            $('#searchTableBtn').click();
        }
    });
}

export function initVideos() {
    initFilterStatus();
    initPagination();
    initSearch();

    initTambahVideo();
    initEditVideo();
    initHapusVideo();
    
}

