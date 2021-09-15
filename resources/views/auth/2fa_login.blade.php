@extends("layouts.app")

@section("main")
<section class="banner auth guest">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 offset-md-3 offset-lg-4 text-light">
                <h1>Enter OTP</h1>
                @error("status")
                    <div class="alert bg-danger text-light mt-3"><strong>{{ $message }}</strong></div>
                @enderror
                <form action="{{ route('login.2fa') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="otp" class="form-label">It is found in your authentication app</label>
                        <input type="text" id="otp" name="otp" class="form-input @error("otp")border border-danger @enderror" required autocomplete="off">
                        @error("otp")
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-sm float-end">Authenticate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection