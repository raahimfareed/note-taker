@extends('layouts.settings')

@section('main')
<section>
    <form action="{{ route('settings.profile') }}" method="POST">
        <h3>Security</h3>
        <hr class="mt-0">
        <div class="mb-3">
            <label for="old-password" class="form-label">Old Password</label>
            <input type="password" name="old_password" id="old-password" class="form-input" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" name="password" id="password" class="form-input" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required autocomplete="off">
        </div>
        <button class="btn btn-sm btn-success">Update Password</button>
    </form>
    <div class="mt-5">
        <h4>2 Factor Authentication</h4>
        <div>
            <span class="text-danger">Not Set</span>
            <form action="" class="d-inline-block ms-3">
                <button class="btn btn-sm btn-success mb-1">Activate</button>
            </form>
        </div>
    </div>
</section>
@endsection