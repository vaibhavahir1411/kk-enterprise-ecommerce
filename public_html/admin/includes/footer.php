    </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Confirm Action</h5>
        </div>
        <div class="modal-body">
            <p id="confirmMessage">Are you sure you want to proceed?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmBtn">Confirm</button>
        </div>
    </div>
</div>

<style>
    .custom-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }
    .modal-content {
        position: relative;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 90%;
        animation: modalSlideIn 0.3s ease-out;
    }
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    .modal-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #212529;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-body p {
        margin: 0;
        color: #6c757d;
        line-height: 1.6;
    }
    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }
</style>

<script>
    let confirmCallback = null;

    function showConfirm(message, callback) {
        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmModal').style.display = 'flex';
        confirmCallback = callback;
        
        document.getElementById('confirmBtn').onclick = function() {
            const cb = confirmCallback;
            closeConfirmModal();
            if (cb) cb(true);
        };
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
        confirmCallback = null;
    }

    // Close modal on overlay click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            closeConfirmModal();
        }
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
