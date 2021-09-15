@extends('layouts.settings')

@section('main')
<section>
    <form action="{{ route('settings.security.2fa.disable') }}" method="POST">
        <h3>Disable 2 Factor Authentication</h3>
        <hr class="mt-0">
        <div class="alert bg-warning text-dark fw-bold">Important: This will remove 2 factor authentication from your account. By doing so, your account's security is degraded severely!</div>
        @csrf
        <div class="mb-3">
            <label for="otp" class="form-label">Enter code found in your authentication app</label>
            <input type="text" id="otp" name="otp" class="form-input @error("otp")border border-danger @enderror" required autocomplete="off">
            @error("otp")
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button class="btn btn-sm btn-success">Disable</button>
    </form>
</section>
@endsection