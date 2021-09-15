@extends('layouts.app')

@section("title")
Forgot Password | {{ env("APP_NAME") }}
@endsection

@section("main")
<section class="banner auth guest">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 offset-md-3 offset-lg-4 text-light">
                <h1>Forgot Password</h1>
                @if (session("status"))
                    <div class="alert bg-success text-light mt-3"><strong>{{ session("status") }}</strong></div>
                @endif
                @error("status")
                    <div class="alert bg-danger text-light mt-3"><strong>{{ $message }}</strong></div>
                @enderror
                <form action="{{ route('forgot-password') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="form-email" class="form-label">Email*</label>
                        <input type="email" name="email" id="form-email" class="form-control @error("email") border-danger @enderror" required>
                        @error("email")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-sm float-end">Request Password Reset Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection