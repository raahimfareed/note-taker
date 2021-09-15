@extends('layouts.settings')

@section('main')
    <section>
        @if (session()->get("success"))
            <div class="alert bg-success text-light">
                Updated Settings
            </div>
        @endif
        @if (session()->get("2fa_enabled"))
            <div class="alert bg-success text-light">
                Enabled 2 Factor Authentication
            </div>
        @endif
        @if (session()->get("2fa_disabled"))
            <div class="alert bg-success text-light">
                Disabled 2 Factor Authentication
            </div>
        @endif
        <form action="{{ route('settings.security.update-password') }}" method="POST">
            <h3>Security</h3>
            <hr class="mt-0">
            @csrf
            <div class="mb-3">
                <label for="old-password" class="form-label">Old Password</label>
                <input type="password" name="old_password" id="old-password" class="form-input" required autocomplete="off">
                @error("old_password")
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" id="password" class="form-input" required autocomplete="off">
                @error("password")
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required autocomplete="off">
                @error("password_confirmation")
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-sm btn-success">Update Password</button>
        </form>
        <div class="mt-5">
            <h4>2 Factor Authentication</h4>
            <div>
                @if (auth()->user()->has2Fa())
                    <h5 class="text-success">Activated</h5>
                    <a class="btn btn-sm btn-danger mb-1" href="{{ route('settings.security.2fa.disable') }}">Disable</a>
                @else
                    <h5 class="text-danger">Not Set</h5>
                    <a class="btn btn-sm btn-success mb-1" href="{{ route('settings.security.2fa.enable') }}">Activate</a>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection