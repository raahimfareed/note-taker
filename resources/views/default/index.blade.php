@extends('layouts.app')

@section('main')
    <section class="banner guest">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-light text-center">
                    <h1 class="display-4"><strong>{{ env('APP_NAME', 'Notes') }}</strong></h1>
                    <p class="lead">A place to organize and store all your notes.</p>
                    <span class="index-action-btns">
                        <a href="{{ route('register') }}" class="btn signup-btn">Get Started!</a>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="about guest p-5">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h1>About</h1>
                    <p class="lead">The perfect place to take your notes, permanent or otherwise. {{ env("APP_NAME") }} is an open source app for taking notes and making todo lists. You can access it securely anywhere on any device so long it has a browser. <a href="" class="text-secondary">Get started today!</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection