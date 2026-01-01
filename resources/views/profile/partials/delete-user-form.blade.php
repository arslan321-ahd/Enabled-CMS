<section>
    <p class="text-muted mb-3">
        Once your account is deleted, all of its resources and data will be permanently deleted.
        Please download any data or information that you wish to retain.
    </p>
    <button
        type="button"
        class="btn btn-danger"
        data-bs-toggle="modal"
        data-bs-target="#deleteAccountModal"
    >
        Delete Account
    </button>
    <div
        class="modal fade"
        id="deleteAccountModal"
        tabindex="-1"
        aria-labelledby="deleteAccountModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-header bg-white">
                        <h5 class="modal-title text-dark" id="deleteAccountModalLabel">
                            Confirm Account Deletion
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Please enter your password to confirm.
                        </p>
                        <div class="form-group mt-3">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                placeholder="Enter password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                required
                            >
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
