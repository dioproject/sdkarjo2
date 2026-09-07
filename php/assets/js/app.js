// Admin JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Confirm delete actions
    document.querySelectorAll('form[data-confirm]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm(form.dataset.confirm || 'Yakin ingin menghapus?')) {
                e.preventDefault();
            }
        });
    });
});
