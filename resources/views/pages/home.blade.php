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
					<h1 class="title">Enter a long URL:</h1>
					<form action="{{ @action('UrlController@store') }}" method="post" class="field has-addons">
						{{--  Csrf protection  --}}
						{{ csrf_field() }}
						<p class="control">
							{{-- Protocol Select --}}
							<span class="select is-large">
								<select name="protocol_select" title="Select protocol">
									<option value="http://" style="color: red">http://</option>
									<option value="https://" style="color: green" selected>https://</option>
								</select>
							</span>
						</p>
						<p class="control">
							{{--  Url Input (without protocol)  --}}
							<input class="input is-large is-expanded"
								placeholder="{{ str_replace(['http://', 'https://'], '', route('home')) }}" name="url" autocomplete="off" autofocus>
						</p>
						{{--  Custom Alias Input  --}}
						<p class="control is-not-visible" id="custom-alias-prefix">
							<a class="button is-static is-large">/</a>
						</p>
						<p class="control" id="custom-alias-control">
							<input name="custom_alias" class="input is-large is-not-visible" placeholder="[a-z] [1-9] [-,_]" id="custom_alias_input">
							<button type="button" class="button is-info is-large" id="custom-alias-button">CUSTOM ALIAS</button>
						</p>
						{{--  is_private Hidden  --}}
						<input type="hidden" name="is_private" value="false">
						{{-- url_with_protocol Hidden --}}
						<input type="hidden" name="url_with_protocol" value="">
						<p class="control">
							{{--  Is Private button  --}}
							<button type="button" class="button is-info is-large" id="is-private"><i class="fa fa-user-secret"></i></button>
						</p>
						{{--  Submit  --}}
						<p class="control">
							<button class="button is-info is-large">SHORTEN&nbsp<i class="fa fa-angle-right"></i></button>
						</p>
					</form>

				</div>
			</div>
			<p class="is-not-visible" id="custom_alias_info" style="margin-bottom: 20px;">
				<strong>Custom alias</strong> may contain letters, numbers, dashes, and underscores
			</p>
			<p class="is-not-visible" id="is_private_info">
				<span class="icon"><i class="fa fa-user-secret"></i></span> - private shorten means it will be not visible in 'Most Recent' section at the Home
			</p>
		</div>
	</div>
</section>

{{-- Notification --}}
<div class="notifications">

</div>

@if(isset($myShortens))
	@if(count($myShortens) > 0)
		<div class="container" style="margin-top: 20px">
			{{-- Handle Message --}} 
			@if(session('message'))
			<div class="notification {{ session('message')['message_class'] }}">
				<button class="delete" onclick="disposeMessage()"></button> {{ session('message')['message_text'] }}
			</div>
			@endif

			<h1 class="title">Your shortens</h1>
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
						@foreach($myShortens as $singleUrl)
						<tr>
							{{-- URL Destination --}}
							<td><a href="{{ $singleUrl['url'] }}">{{ $singleUrl['url'] }}</a></td>
							{{-- Shorten URL --}}
							<td><a href="{{ url('/', $singleUrl['string_id']) }}">{{ url('/', $singleUrl['string_id']) }}</a></td>
							<td><a data-clipboard-text="{{ url('/', $singleUrl['string_id']) }}"
									title="Copy to clipboard!" class="button is-small clipboard">Copy</a></td>
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
			<nav class="pagination is-centered">
                <ul class="pagination-list">
                    @if($pagination['currentPage'] != 1 && $pagination['currentPage'] != NULL)
                        <a href="{{ action('HomeController@index', ['page' => $pagination['previousPage']]) }}" class="pagination-previous">Previous</a>
                    @endif
                    @if($pagination['currentPage'] < $pagination[ 'lastPage'])
                        <a class="pagination-next" href="{{ action('HomeController@index', ['page' => $pagination['nextPage']]) }}">Next</a>
                    @endif
                </ul>
			</nav>
		</div>
	@endif
@endif

@if(count($newestShortens) > 0)
	{{-- Most Recent Shortens --}}
	<div class="container" id="newest-shortens">
		<h1 class="title">Most Recent Shortens</h1>
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
					<td>{{ $newestShorten['redirects_count'] }}</td>
					{{-- [statistics_button] --}}
					<td>
						<a title="View statistics of this shorten!" href="{{ url('/', [ $newestShorten['string_id'], 'stats']) }}" class="button is-small"><i class="fa fa-bar-chart"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@endif
@endsection

@section('scripts')
<script type="text/javascript">
	function dispose() {
		let errorTag = document.getElementsByClassName('tag');
		errorTag[0].style.display = 'none';
	}

	function disposeMessage() {
		let message = document.getElementsByClassName('notification');
		message[0].style.display = 'none';
	}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.13/clipboard.min.js"></script>

<script type="text/javascript">
	let cb = new Clipboard('.clipboard');

	cb.on('success', function (e) {
        pushNotification(notificationGenerator('success', 'Shorten has been copied successfully!'), 3000, 'slow')
	});

    cb.on('error', function(e) {
        alert('Something went wrong when trying to copy :' + e);
    })
</script>
@endsection