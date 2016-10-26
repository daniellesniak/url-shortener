@extends('layouts.base')

@section('title', 'Your shorten URL is ready!')

@section('content-fluid')
	<section class="hero is-medium is-dark is-bold">
	<div class="hero-body">
	    <div class="container has-text-centered">
		    <h2 class="subtitle">It's your shorten URL, just copy it!</h2>
		    <input type="text" class="input is-dark is-large" value="{{ $shortenUrl }}" readonly onclick="this.select();" style="cursor: pointer; text-align: center;">
	    </div>
    </div>
    </section>
@endsection