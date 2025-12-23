export function initTambahVideo() {

    const modal = document.getElementById('modalTambahVideo');
    
    modal.addEventListener('show.bs.modal', async function() {
        const content = document.getElementById('modalTambahVideoContent');
        content.innerHTML = '<div class="p-3 text-center">Memuat...</div>';

        try {
            const res = await fetch(window.routes.createVideoModal);
            const data = await res.json();
            content.innerHTML = data.html;

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

export function initEditVideo() {

    document.querySelectorAll('.edit-video-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
    
            const modalContent = document.getElementById('modalEditVideoContent');
            modalContent.innerHTML = '<div class="p-3 text-center">Memuat...</div>';

            const url = btn.dataset.url;

            try {
                const res = await fetch(url);
                if (!res.ok) throw new Error('HTTP error ' + res.status);
                const data = await res.json();
                modalContent.innerHTML = data.html;

                const modal = new bootstrap.Modal(document.getElementById('modalEditVideo'));
                modal.show();
            } catch (error) {
                console.error(error);
                modalContent.innerHTML = '<div class="p-3 text-danger text-center">Gagal memuat data.</div>'
            }
        });
    });
}

export function initVideos() {
    initTambahVideo();
    initEditVideo();
}
