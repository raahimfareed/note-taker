@extends('layouts.settings')

@section('main')
<section>
    @if (session()->get("success"))
        <div class="alert bg-success text-light">
            Updated Settings
        </div>
    @endif
    <form action="{{ route('settings.profile') }}" method="POST">
        <h3>Profile</h3>
        <hr class="mt-0">
        @csrf
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" id="fullname" name="name" class="form-input" value="{{ auth()->user()->name }}" required autocomplete="off">
            @error("name")
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-input" value="{{ auth()->user()->username }}" required autocomplete="off">
            @error("username")
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" class="form-input" name="email" value="{{ auth()->user()->email }}" required autocomplete="off">
            @error("email")
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button class="btn btn-sm btn-success">Update Profile</button>
    </form>
</section>
@endsection