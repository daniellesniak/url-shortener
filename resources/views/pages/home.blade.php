@extends('layouts.base')
@section('title', 'Home')

@section('content-fluid')
<section class="hero is-medium is-dark is-bold">
	<div class="hero-body">
		<div class="container">
			<div class="columns">
				<div class="column is-three-quarters">
					<h1 class="title">Enter a long URL:</h1>
					<form action="{{ @action('ShortenController@store') }}" method="post" class="field has-addons">
						{{--  Csrf protection  --}}
						{{ Form::token() }}
						<p class="control is-expanded">
							{{-- URL --}}
							{!! Form::url('url', old('url'),
								['class' => 'input is-large',
								'autocomplete' => 'off',
								'autofocus',
								'placeholder' => route('home')]
								) !!}
						</p>
						{{--  Custom Alias Button  --}}
						<p class="control is-not-visible" id="custom-alias-prefix">
							<a class="button is-static is-large">|</a>
						</p>
						{{-- Custom alias input --}}
						<p class="control" id="custom-alias-control">
							{!! Form::text('slug', '',
							['class' => 'input is-large is-not-visible',
							'placeholder' => '[a-z] [1-9] [-,_]',
							'id' => 'custom_alias_input',
							])
							!!}
							<button type="button" class="button is-info is-large" id="custom-alias-button">CUSTOM ALIAS</button>
						</p>
						<p class="control">
							{!! Form::hidden('is_private', 'false') !!}
							<button type="button" class="button is-info is-large" id="is-private"><i class="fa fa-user-secret"></i></button>
						</p>
						{{--  Submit  --}}
						<p class="control">
							{!! Form::submit('SHORTEN', ['class' => 'button is-info is-large']) !!}
						</p>
					</form>

					@if(count($errors) > 0)
						@foreach($errors->all() as $error)
							<span class="tag is-danger">
							  {{ $error }}
								<button class="delete is-small"></button>
							</span>
						@endforeach
					@endif
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
@if(isset($myShortens))
	@include('partials.my-shortens', $myShortens)
@endif

{{-- Newest Shortens --}}
@include('partials.newest-shortens', $newestShortens)

@endsection

@section('scripts')
<script type="text/javascript">
	$('.tag .delete').on('click', function () {
	    $(this).parent().css('display', 'none');
	})
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