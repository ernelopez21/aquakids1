        </div>
    </main>
</div>

<!-- Toasts -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11000;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Mostrar flash message automáticamente
    <?php if ($flash): ?>
    document.addEventListener('DOMContentLoaded', () => {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> border-0`;
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($flash['message']) ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>`;
        document.querySelector('.toast-container').appendChild(toast);
        new bootstrap.Toast(toast).show();
    });
    <?php endif; ?>
</script>
</body>
</html>