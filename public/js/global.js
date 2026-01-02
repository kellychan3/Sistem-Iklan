export function initLogout() {
    $('#btnLogout').on('click', function() {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Apakah anda yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#logoutForm').submit();
            }
        })
    })
}