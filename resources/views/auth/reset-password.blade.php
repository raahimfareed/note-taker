@extends("layouts.app")

@section("title")
Join {{ env("APP_NAME") }} today!
@endsection

@section("main")
<section class="banner auth guest">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 offset-md-3 offset-lg-4 text-light">
                <h1>Reset Password</h1>
                @if (session("status"))
                    <div class="alert bg-success text-light mt-3"><strong>{{ session("status") }}</strong></div>
                @endif
                @error("status")
                    <div class="alert bg-danger text-light mt-3"><strong>{{ $message }}</strong></div>
                @enderror
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-3">
                        <label for="form-email" class="form-label">Email*</label>
                        <input type="email" name="email" id="form-email" class="form-control @error("email") border-danger @enderror" required>
                        @error("email")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="form-password" class="form-label">Password*</label>
                        <input type="password" name="password" id="form-password" class="form-control @error("password") border-danger @enderror" required>
                        @error("password")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="form-password-confirmation" class="form-label">Confirm Password*</label>
                        <input type="password" name="password_confirmation" id="form-password-confirmation" class="form-control @error("password_confirmation") border-danger @enderror" required>
                        @error("password_confirmation")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-sm float-end">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
