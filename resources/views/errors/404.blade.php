@extends('layouts.base')
@section('title', '404 Not Found')

@section('content-fluid')
    <meta http-equiv="refresh" content="5; url={{ url('/') }}" />
    <style>
        .error-404 {
            font-size: 20em;
        }
    </style>
    <div class="container">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter" style="text-align: center;">
                <h1 class="error-404">404</h1>
                <p class="subtitle">Redirecting to <a href="{{ url('/') }}">HOMEPAGE</a> in 5 seconds...</p>
            </div>
        </div>
    </div>
@endsection