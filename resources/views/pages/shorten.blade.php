@extends('layouts.base')

@section('title', 'Your shorten URL is ready!')

@section('content-fluid')
	<section class="hero is-medium is-dark is-bold">
	<div class="hero-body">
	    <div class="container has-text-centered">
		    <h2 class="subtitle">It's your shorten URL, just copy it!</h2>
		    <input type="text" class="input is-dark is-large" value="{{ $shorten->shortenUrl() }}" readonly onclick="this.select();" style="cursor: pointer; text-align: center;">
	    </div>

	    <div class="container">
	    <h2 class="subtitle">Share:</h2>
	    {{-- Copy --}}
	    <button class="button is-primary copyIt" data-clipboard-text="{{ $shorten->shortenUrl() }}"><i class="fa fa-copy"></i>&nbspCopy!</button>
	    {{-- Facebook --}}
	    <button class="button"><i class="fa fa-facebook"></i>&nbspShare to facebook!</button>
	    {{-- Google+ --}}
	    <button class="button"><i class="fa fa-google-plus"></i>&nbspShare to G+!</button>
	    </div>
    </div>
    </section>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.13/clipboard.min.js"></script>

<script type="text/javascript">
	var cb = new Clipboard('.copyIt')

	cb.on('success', function(e) {

	})

	cb.on('error', function(e) {
		alert('Something went wrong when try to copy :' + e);
	})
</script>

@endsection