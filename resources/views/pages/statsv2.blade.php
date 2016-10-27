@extends('layouts.base')

@section('title', 'Your shorten URL is ready!')

@section('stylesheets')
	{{-- Flag Icons --}}
	<link rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}">
@endsection

@section('content-fluid')
	<div class="container">
		<div class="columns">
			<div class="column has-text-centered">
				<h1 class="title"><i class="fa fa-bar-chart"></i> STATISTICS</h1>
			</div>
		</div>

		<div class="columns">
			<div class="column is-half">
				<h1 class="title"><a href="{{ $shortenUrl }}"><strong><i class="fa fa-link"></i> {{ $shortenUrl }}</strong></a></h1>
			</div>

			<div class="column has-text-right">
				<h1 class="title"><strong>{{ $redirects }} redirects!</strong></h1>
			</div>
		</div>	

		<div class="columns">
			<div class="column">
				<div class="tabs is-centered is-toggle">
				  <ul>
				    <li>
				      <a href="{{ action('UrlController@statsv2', $id) }}">
				        <span class="icon is-small"><i class="fa fa-calendar"></i></span>
				        <span>All the time</span>
				      </a>
				    </li>
				    <li>
				      <a href="{{ action('UrlController@statsv2', ['id' => $id, 'range' => '24h']) }}">
				        <span class="icon is-small"><i class="fa fa-circle-thin"></i></span>
				        <span>24 hours</span>
				      </a>
				    </li>
				    <li>
				      <a href="{{ action('UrlController@statsv2', ['id' => $id, 'range' => '48h']) }}">
				        <span class="icon is-small"><i class="fa fa-angle-left"></i></span>
				        <span>48 hours</span>
				      </a>
				    </li>
				    <li>
				      <a href="{{ action('UrlController@statsv2', ['id' => $id, 'range' => 'week']) }}">
				        <span class="icon is-small"><i class="fa fa-angle-double-left"></i></span>
				        <span>1 week</span>
				      </a>
				    </li>
				    <li>
				      <a href="{{ action('UrlController@statsv2', ['id' => $id, 'range' => 'month']) }}">
				        <span class="icon is-small"><i class="fa fa-angle-double-left"></i><i class="fa fa-angle-double-left"></i></span>
				        <span>1 month</span>
				      </a>
				    </li>
				    <li>
				    	<a href="#" onclick="hideShowCustomRange()" id="customRangeBtn">
					        <span class="icon is-small"><i class="fa fa-arrows-h"></i></span>
					        <span>Cusom range</span>
				    	</a>
				    </li>
				  </ul>
				</div>
			</div>
		</div>

		<div class="columns is-hidden" id="customRange">
			<div class="column">
				<div class="columns">
					<div class="column"></div>
					<form class="control is-horizontal">
					    <div class="control is-grouped">
					    <p class="control is-expanded">
					      <input class="input" type="date" placeholder="From">
					    </p>
					    <p class="control is-expanded">
					      <input class="input" type="date" placeholder="To">
					    </p>
					  	</div>
					  	<a class="button is-info" style="margin-left: 8px;">Submit</a>
					</form>
					<div class="column"></div>
				</div>
			</div>
		</div>

		<div class="columns">
			<div class="column">
				<div class="message is-info">
					<div class="message-header">
						<div class="columns">
							<div class="column is-2">
								<i class="fa fa-linux"></i> by OS
							</div>

							<div class="column is-2">
								<i class="fa fa-internet-explorer"></i> by Browser
							</div>

							<div class="column is-3">
								<i class="fa fa-globe"></i> by Country
							</div>

							<div class="column is-5">
								<i class="fa fa-external-link"></i> by Referer
							</div>
						</div>
					</div>

					<div class="message-body">
						<div class="columns">
							<div class="column is-2">
								@if($platformStats != null)
									@foreach($platformStats as $key=>$value)
										{{ $key }} - <strong>{{ $platformPercent[$key] }}%</strong>
										 ({{ $value }})<br>
									@endforeach
								@else
									<p class="has-text-centered">NO REDIRECTS!</p>
								@endif
							</div>

							<div class="column is-2">
								@if($browserStats != null)
									@foreach($browserStats as $key=>$value)
										{{ $key }} - <strong>{{ $browserPercent[$key] }}%</strong>
										 ({{ $value }})<br>
									@endforeach
								@else
									<p class="has-text-centered">NO REDIRECTS!</p>
								@endif
							</div>

							<div class="column is-3">
								@if($countryStats != null)
									@foreach($countryStats as $key=>$value)
										<span class="flag-icon flag-icon-{{ strtolower(convertCountryToIso($key)) }}"></span> {{ $key }} - <strong>{{ $countryPercent[$key] }}%</strong>
										 ({{ $value }})<br>
									@endforeach
								@else
									<p class="has-text-centered">NO REDIRECTS!</p>
								@endif
							</div>

							<div class="column is-5">
								@if($httpRefererStats != null)
									@foreach($httpRefererStats as $key=>$value)
										@if($key == '(directly)')
										{{ $key }}
										@else
										<a href="{{ $key }}">{{ $key }}</a>
										@endif
										 - <strong>
										{{ $httpRefererPercent[$key] }}%</strong>
										({{ $value }})<br>
									@endforeach
								@else
									<p class="has-text-centered">NO REDIRECTS!</p>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		 function hideShowCustomRange()
		 {
			 customRange = document.getElementById('customRange');
			 customRange.classList.toggle('is-hidden');
		 }
	</script>
@endsection