<section>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')
        <div class="form-group mb-3 row">
            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">
                Current Password
            </label>
            <div class="col-lg-9 col-xl-8">
                <input
                    type="password"
                    class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                    name="current_password"
                    autocomplete="current-password"
                    required
                >
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group mb-3 row">
            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">
                New Password
            </label>
            <div class="col-lg-9 col-xl-8">
                <input
                    type="password"
                    class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                    name="password"
                    autocomplete="new-password"
                    required
                >
                @error('password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group mb-3 row">
            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">
                Confirm Password
            </label>
            <div class="col-lg-9 col-xl-8">
                <input
                    type="password"
                    class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                    name="password_confirmation"
                    autocomplete="new-password"
                    required
                >
                @error('password_confirmation', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-9 col-xl-8 offset-lg-3">
                <button type="submit" class="btn btn-primary">
                    Change Password
                </button>
            </div>
        </div>
    </form>
</section>
