<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')
        <div class="form-group mb-3 row">
            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">
                Name
            </label>
            <div class="col-lg-9 col-xl-8">
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group mb-3 row">
            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">
                Email Address
            </label>
            <div class="col-lg-9 col-xl-8">
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-muted mb-1">
                            Your email address is unverified.
                        </p>
                        <button
                            type="submit"
                            form="send-verification"
                            class="btn btn-link p-0 text-primary"
                        >
                            Click here to re-send the verification email.
                        </button>
                        @if (session('status') === 'verification-link-sent')
                            <div class="text-success mt-1">
                                A new verification link has been sent to your email address.
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-9 col-xl-8 offset-lg-3">
                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</section>
