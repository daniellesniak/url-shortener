@extends('layouts.base')
@section('title', 'Home')

@section('content-fluid')
<section class="hero is-medium is-dark is-bold">
	<div class="hero-body">
		<div class="container">
			<div class="columns">
				<div class="column is-two-thirds">
					<h1 class="title">Enter a long URL:</h1>
					<form action="{{ @action('ShortenController@store') }}" method="post" class="field has-addons">
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
							<a class="button is-static is-large">|</a>
						</p>
						<p class="control" id="custom-alias-control">
							<input name="custom_alias" class="input is-large is-not-visible" placeholder="[a-z] [1-9] [-,_]" id="custom_alias_input">
							<button type="button" class="button is-info is-large" id="custom-alias-button">CUSTOM ALIAS</button>
						</p>
						{{--  is_private Hidden  --}}
						<input type="hidden" name="is_private" value="false">
						<p class="control">
							{{--  Is Private button  --}}
							<button type="button" class="button is-info is-large" id="is-private"><i class="fa fa-user-secret"></i></button>
						</p>
						{{--  Submit  --}}
						<p class="control">
                            <input type="submit" class="button is-info is-large" value="SHORTEN">
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

{{-- Notifications --}}
<div class="notifications">

</div>

{{-- My Shortens --}}
@include('partials.my-shortens', $myShortens)

{{-- Newest Shortens --}}
@include('partials.newest-shortens', $newestShortens)

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