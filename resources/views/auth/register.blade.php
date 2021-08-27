@extends("layouts.app")

@section("title")
Join {{ env("APP_NAME") }} today!
@endsection

@section("main")
<section class="banner auth guest">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 offset-md-3 offset-lg-4 text-light">
                <h1 class="">Register</h1>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="form-name" class="form-label">Name</label>
                        <input type="text" name="name" id="form-name" class="form-control @error("name") border-danger @enderror">
                        @error("name")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="form-username" class="form-label">Username*</label>
                        <input type="text" name="username" id="form-username" class="form-control @error("username") border-danger @enderror" required>
                        @error("username")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
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
                        <button type="submit" class="btn btn-sm float-end">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
