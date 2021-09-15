@extends('layouts.settings')

@section('main')
<section>
    <form action="{{ route('settings.security.2fa.enable') }}" method="POST">
        <h3>Enable 2 Factor Authentication</h3>
        <hr class="mt-0">
        @error("otp")
            <div class="alert bg-danger text-light fw-bold">{{ $message }}</div>
        @enderror
        @csrf
        <div class="qr">
            {!! $qr !!}
            <ol class="mt-3">
                <li>Open your authenticator app</li>
                <li>Click on scan QR code</li>
                <li>Scan the above QR code or type <strong><em>{{ $secret }}</em></strong> manually in your authenticator</li>
                <li>Type the code generated for you</li>
            </ol>
        </div>
        <div class="mb-3">
            <label for="otp" class="form-label">Enter Code</label>
            <input type="text" id="otp" name="otp" class="form-input" required autocomplete="off">
        </div>
        <button class="btn btn-sm btn-success">Activate</button>
    </form>
</section>
@endsection