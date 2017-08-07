@extends('layouts.base') @section('title', 'Home') @section('content-fluid')
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
					<h1 class="title">What do you want to short?</h1>
					<form action="{{ @action('UrlController@store') }}" method="post" class="field has-addons">
						{{ csrf_field() }}
						<p class="control">
							<span class="select is-large">
								<select>
									<option value="http" style="color: red">http://</option>
									<option value="https" style="color: green" selected>https://</option>
								</select>
							</span>
						</p>
						<p class="control">
							<input class="input is-large is-expanded" type="text" 
								placeholder="{{ str_replace(['http://', 'https://'], '', route('home')) }}" name="url" autocomplete="off">
						</p>
						<input type="hidden" name="is_private" value="false" id="is_private">
						<p class="control">
							{{--  <i class="fa fa-link"></i>  --}}
							<button class="button is-danger is-large" 
								value="Short me!" id="is-private"><i class="fa fa-user-secret"></i></button>
						</p>
						<p class="control">
							<input type="submit" class="button is-danger is-large" value="Short me!">
						</p>
					</form>

				</div>
			</div>
			<p class="is-visible" id="is_private_info">
				<span class="icon"><i class="fa fa-user-secret"></i></span> - your shorten will be private, it means it will be not in Newest section
			</p>
		</div>
	</div>
</section>

@if(isset($urlsData))
<div class="container" style="margin-top: 20px">
	{{-- Handle Message --}} 
	@if(session('message'))
	<div class="notification {{ session('message')['message_class'] }}">
		<button class="delete" onclick="disposeMessage()"></button> {{ session('message')['message_text'] }}
	</div>
	@endif

	<h1 class="title">Your history of shortens</h1>
	<table class="table is-striped is-fullwidth">
		<thead>
			<tr>
				<th>URL Destination</th>
				<th>Shorten URL</th>
				<th></th>
				<th>Created</th>
				<th>Total Redirects</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
				@foreach($urlsData as $singleUrl)
				<tr>
					{{-- URL Destination --}}
					<td><a href="{{ $singleUrl['url'] }}">{{ $singleUrl['url'] }}</a></td>
					{{-- Shorten URL --}}
					<td><a href="{{ url('/', $singleUrl['string_id']) }}">{{ url('/', $singleUrl['string_id']) }}</a></td>
					<td><a data-clipboard-text="{{ url('/', $singleUrl['string_id']) }}"
						    title="Copy to clipboard!" class="button is-small clipboard" href="#">Copy</a></td>
					{{-- Created --}}
					<td>{{ $singleUrl['ago_date'] }}</td>
					{{-- Total Redirects --}}
					<td>{{ $singleUrl['redirects_count'] }}</td>
					{{-- [statistics_button] --}}
					<td>
						<a title="View statistics of that shorten!" href="{{ url('/', [ $singleUrl['string_id'], 'stats']) }}" class="button is-small"><i class="fa fa-bar-chart"></i></a>
						<a title="Hide this shorten!" href="{{ action('HomeController@hideUrl', $singleUrl['string_id']) }}" class="button is-small"><i class="fa fa-eye-slash"></i></a>
					</td>
				</tr>
				@endforeach
		</tbody>
	</table>
	{{-- Pagination --}}
	<nav class="pagination">
		@if($urlPage['currentPage'] != 1 && $urlPage['currentPage'] != NULL)
			<a href="{{ action('HomeController@index', ['page' => $urlPage['previousPage']]) }}" class="button">Previous</a> 
		@endif
		
		@if($urlPage['currentPage'] < $urlPage[ 'lastPage']) <a class="button" href="{{ action('HomeController@index', ['page' => $urlPage['nextPage']]) }}">Next</a>
		@endif
	</nav>
</div>
@endif
{{-- Newest Shortens --}}
<div class="container">
	<h1 class="title">Newest Shortens</h1>
	<table class="table is-striped is-fullwidth">
		<thead>
			<tr>
				<th>URL Destination</th>
				<th>Shorten URL</th>
				<th>Created</th>
				<th>Total Redirects</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			@foreach($newestShortens as $newestShorten)
			<tr>
				<td>
					<a href="{{ $newestShorten->url }}">{{ $newestShorten->url }}</a>
				</td>
				<td>
					<a href="{{ route('home')}}/{{ $newestShorten->string_id }}">{{ route('home') }}/{{ $newestShorten->string_id }}</a>
				</td>
				<td>
					{{ $carbon->instance($newestShorten->created_at)->diffForHumans() }}
				</td>
				{{-- Total Redirects --}}
				<td>{{ $singleUrl['redirects_count'] }}</td>
				{{-- [statistics_button] --}}
				<td>
					<a title="View statistics of this shorten!" href="{{ url('/', [ $singleUrl['string_id'], 'stats']) }}" class="button is-small"><i class="fa fa-bar-chart"></i></a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endsection @section('scripts')
<script type="text/javascript">
	function dispose() {
		var errorTag = document.getElementsByClassName('tag');
		errorTag[0].style.display = 'none';
	}

	function disposeMessage() {
		var message = document.getElementsByClassName('notification');
		message[0].style.display = 'none';
	}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.13/clipboard.min.js"></script>

<script type="text/javascript">
	var cb = new Clipboard('.clipboard');

	cb.on('success', function (e) {
		alert('Copied!');
	});

	cb.on('error', function (e) {
		console.log(e);
		alert('It cannot be copy to clipboard :/');
	});
</script>
@endsection