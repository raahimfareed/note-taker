@extends("layouts.app")

@section("title")
Join {{ env("APP_NAME") }} today!
@endsection

@section("main")
<section class="banner auth guest">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 offset-md-3 offset-lg-4 text-light">
                <h1>Login</h1>
                @error("status")
                    <div class="alert bg-danger text-light mt-3"><strong>{{ $message }}</strong></div>
                @enderror
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="form-username" class="form-label">Username*</label>
                        <input type="text" name="username" id="form-username" class="form-control @error("username") border-danger @enderror" required>
                        @error("username")
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
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Stay logged in
                        </label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-sm float-end">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
