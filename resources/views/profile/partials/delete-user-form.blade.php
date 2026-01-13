<!-- resources/views/profile/partials/delete-user-form.blade.php -->
<div class="delete-form-section">
    <div class="form-header">
        <h3 class="form-title text-red-600">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Delete Account
        </h3>
        <p class="form-subtitle">Permanently delete your account and all associated data</p>
    </div>

    <div class="danger-warning">
        <div class="warning-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="warning-content">
            <h4 class="warning-title">Warning: This action cannot be undone</h4>
            <p class="warning-description">
                Once your account is deleted, all of your personal data, bookings, and records will be permanently removed. 
                This includes:
            </p>
            <ul class="warning-list">
                <li>Your profile information and settings</li>
                <li>All booking history and records</li>
                <li>Payment history and receipts</li>
                <li>Any associated student records</li>
            </ul>
            <p class="warning-note">
                Please download any data you wish to keep before proceeding.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.profile.destroy') }}" class="delete-form">
        @csrf
        @method('delete')

        <div class="delete-actions">
            <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
                <i class="fas fa-trash"></i>
                Delete My Account
            </button>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal-overlay" id="delete-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        Confirm Account Deletion
                    </h3>
                    <button type="button" class="modal-close" onclick="closeDeleteModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-warning">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>This action is permanent and cannot be undone</strong>
                            <p>All your data will be permanently deleted from our systems.</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="delete_password" class="form-label">
                            To confirm, please enter your current password:
                        </label>
                        <input type="password" id="delete_password" name="password" 
                               class="form-control" placeholder="Enter your password" required>
                        @error('password', 'userDeletion')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Delete Account Permanently
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.delete-form-section {
    space-y-6;
}

.danger-warning {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    padding: 2rem;
    background: rgba(247, 37, 133, 0.05);
    border: 1px solid rgba(247, 37, 133, 0.2);
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
}

.warning-icon {
    color: var(--danger);
    font-size: 2rem;
    margin-top: 0.25rem;
}

.warning-content {
    flex: 1;
}

.warning-title {
    font-weight: 600;
    color: var(--danger);
    margin-bottom: 1rem;
    font-size: 1.125rem;
}

.warning-description {
    color: var(--gray-700);
    margin-bottom: 1rem;
    line-height: 1.5;
}

.warning-list {
    color: var(--gray-700);
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.warning-list li {
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.warning-note {
    color: var(--gray-600);
    font-style: italic;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(247, 37, 133, 0.2);
}

.delete-actions {
    text-align: center;
    padding: 2rem 0;
}

.modal-warning {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(247, 37, 133, 0.05);
    border: 1px solid rgba(247, 37, 133, 0.2);
    border-radius: var(--border-radius);
    margin-bottom: 1.5rem;
}

.modal-warning i {
    color: var(--danger);
    font-size: 1.5rem;
    margin-top: 0.125rem;
}

.modal-warning strong {
    color: var(--danger);
    display: block;
    margin-bottom: 0.5rem;
}

.modal-warning p {
    color: var(--gray-700);
    margin: 0;
}
</style>

<script>
function openDeleteModal() {
    document.getElementById('delete-modal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('delete-modal');
    if (e.target === modal) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>