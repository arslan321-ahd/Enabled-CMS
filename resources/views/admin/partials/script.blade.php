    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/index.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
            fetch("{{ route('admin.logs.markAllRead') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'All notifications marked as read',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        document.querySelectorAll('.bg-danger-subtle').forEach(el => {
                            el.classList.remove('bg-danger-subtle');
                        });
                        document.querySelectorAll('.badge.bg-danger').forEach(el => el.remove());
                        const badge = document.querySelector('.alert-badge');
                        if (badge) badge.remove();
                    }
                });
        });
    </script>
