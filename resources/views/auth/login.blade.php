<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mentrual Monitoring App :: Sign In ::</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/auth/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/auth/css/styles.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/izitoast/iziToast.min.css') }}">

    <style>
        .form-control { border-radius: 2px !important; }
    </style>
</head>
<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                @if(Route::has('login'))
                                    <p class="text-center fw-bolder mb-1 h4">Menstrual Monitoring App</p>
                                    @auth
                                        @if(Auth::user()->user_role_id == 1)
                                            <p class="text-center mb-4">Leaving already? click below to return to dashboard</p>
                                            <a href="{{ URL::to('admin/dashboard') }}" class="btn btn-primary w-100 py-2 fs-4 rounded-1">Return to Dashboard</a>
                                        @else
                                            <p class="text-center mb-4">Leaving already? click below to return to dashboard</p>
                                            <a href="{{ URL::to('user/dashboard') }}" class="btn btn-primary w-100 py-2 fs-4 rounded-1">Return to Dashboard</a>
                                        @endif
                                    @else
                                    <p class="text-center mb-4">Sign in to your account to proceed</p>
                                    <form method="POST" action="{{ route('login') }}" autocomplete="off">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="Enter email ex: juany@sample.com" required autofocus>

                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mb-4">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" id="password" name="password" class="form-control  {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="*******" required>

                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input primary" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label text-dark" for="remember">Remeber</label>
                                            </div>
                                            @if(Route::has('register'))
                                                <a class="text-primary fw-bold" href="{{ route('register') }}">Register an Account</a>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 py-2 fs-4 rounded-1">Sign In</button>
                                    </form>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/auth/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/auth/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/izitoast/iziToast.min.js') }}"></script>

    @include('auth.response')
</body>
</html>