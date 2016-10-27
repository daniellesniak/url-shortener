@extends('layouts.base')

@section('title', 'Home')

@section('content-fluid')
	<section class="hero is-medium is-dark is-bold">
	<div class="hero-body">
	    <div class="container">

	    	@if (count($errors) > 0)
	    		@foreach ($errors->all() as $error)
	    			<span class="tag is-danger is-small">
	    				<i class="fa fa-exclamation-circle"></i>
	    				&nbsp
	    				{{ $error }}
				  		<button onclick="dispose()" class="delete is-small"></button>
					</span>
	    		@endforeach
	    	@endif
	    	<div class="columns">
		      	<div class="column is-two-thirds">
				      <h1 class="title">
				        Just put URL below
				      </h1>
				      <h2 class="subtitle">
					      <form action="{{ @action('UrlController@store') }}" method="post">
					      {{ csrf_field() }}
					      	<p class="control has-icon has-addons">
					      		<input class="input is-danger is-medium is-expanded" type="text" value="http://google.com/" placeholder="https://google.com/" name="url" autocomplete="off">
					      		<i class="fa fa-link"></i>
					      		<input type="submit" class="button is-danger is-medium" value="Short me!">
					      	</p>
					      </form>
				      </h2>
			      </div>
	      	</div>
	      	and click the button!
	    </div>
    </div>
    </section>

    @if(isset($urlsData))
	    <div class="container">
	    	<table class="table">
			  <thead>
			    <tr>
			      <th>Original URL</th>
			      <th>Created</th>
			      <th>Short URL</th>
			      <th>All Redirects</th>
			      <th></th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr>
			    @foreach($urlsData as $singleUrl)
			    	<tr>
			    	{{-- Original URL --}}
			    	<td><a href="{{ $singleUrl['url'] }}">{{ $singleUrl['url'] }}</a></td>
			    	{{-- Created --}}
			    	<td>{{ $singleUrl['ago_date'] }}</td>
			    	{{-- Short URL --}}
			    	<td><a href="{{ url('/', $singleUrl['string_id']) }}">{{ url('/', $singleUrl['string_id']) }}</a> - <a data-clipboard-text="{{ url('/', $singleUrl['string_id']) }}" title="Copy to clipboard!" class="button is-small clipboard" href="#"><i class="fa fa-clipboard"></a></td>
			    	{{-- All Redirects --}}
			    	<td>{{ $singleUrl['redirects_count'] }}</td>
			    	{{-- [statistics_button] --}}
			    	<td><a title="Show statistics!" href="{{ url('/', [ $singleUrl['string_id'], 'stats']) }}" class="button is-small"><i class="fa fa-bar-chart"></i></a></td>
			    	</tr>
			    @endforeach
			  </tbody>
			</table>
	    </div>
    @endif
@endsection

@section('scripts')
	<script type="text/javascript">
		function dispose() 
		{
			var errorTag = document.getElementsByClassName('tag');
			errorTag[0].style.display = 'none';
		}
	</script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.13/clipboard.min.js"></script>

	<script type="text/javascript">
		var cb = new Clipboard('.clipboard');

		cb.on('success', function(e) {
			alert('Copied!');
		});

		cb.on('error', function(e) {
			console.log(e);
			alert('It cannot be copy to clipboard :/');
		});
	</script>
@endsection