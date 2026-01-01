<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">
    <head>
    @include('admin.partials.head_css')
    </head>
    <body>
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0  auth-header-box rounded-top">
                                    <div class="text-center p-3">
                                        <a href="javascript:void(0);" class="logo logo-admin">
                                            <img src="{{asset('assets/images/blue.png')}}" height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold  fs-18">Let's Get Started!</h4>   
                                        <p class="text-muted fw-medium mb-0">Sign in to continue to Enable.</p>  
                                    </div>
                                </div>
                                <div class="card-body pt-0">                                    
                                    <form method="POST" action="{{ route('login') }}" class="my-4">
                                        @csrf
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="email">Email</label>
                                            <input
                                                type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                id="email"
                                                name="email"
                                                value="{{ old('email') }}"
                                                placeholder="Enter email"
                                                required
                                                autofocus
                                            >
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="password">Password</label>
                                            <input
                                                type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password"
                                                id="password"
                                                placeholder="Enter password"
                                                required
                                            >
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-sm-6">
                                                <div class="form-check form-switch form-switch-success">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="remember"
                                                        name="remember"
                                                    >
                                                    <label class="form-check-label" for="remember">
                                                        Remember me
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 text-end">
                                                @if (Route::has('password.request'))
                                                    <a href="{{ route('password.request') }}" class="text-muted font-13">
                                                        <i class="dripicons-lock"></i> Forgot password?
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="submit">
                                                        Log In <i class="fas fa-sign-in-alt ms-1"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                                       
    </div>
    </body>
</html>