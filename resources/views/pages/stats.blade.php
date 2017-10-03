@extends('layouts.base')

@section('title', "Shorten $shorten->url - statistics")

@section('stylesheets')
	{{-- Flag Icons --}}
	<link rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}">
@endsection

@section('content-fluid')
	<div class="container" style="margin-top: 30px;">
		<div class="columns">
			<div class="column has-text-centered">
				<h1 class="title"><i class="fa fa-bar-chart"></i></h1>
			</div>
		</div>

		{{--  Total Redirects  --}}
		<div class="field is-horizontal">
			<div class="field-label">
				<label class="label">Total redirects: </label>
			</div>
			<div class="field-body">
				<div class="field">
					<span class="tag is-dark is-large"><b>{{ $stats->count() }}</b></span>
				</div>
			</div>
		</div>
		
		{{--  Shorten Url  --}}
		<div class="field is-horizontal">
			<div class="field-label">
				<label class="label">Shorten URL: </label>
			</div>
			<div class="field-body">
				<div class="field">
					<a href='{{ $shorten->shortenUrl() }}' class="control is-expanded" target="_blank">
						<input class="input" readonly value="{{ $shorten->shortenUrl() }}" title="Shorten Url">
					</a>
				</div>
			</div>
		</div>

		{{--  Destination Url  --}}
		<div class="field is-horizontal">
			<div class="field-label">
				<label class="label">Destination URL: </label>
			</div>
			<div class="field-body">
				<div class="field">
					<a href="{{ $shorten->url }}" class="control is-expanded" target="_blank">
						<input class="input" type="text" readonly value="{{ $shorten->url }}" title="Destination Url">
					</a>
				</div>
			</div>
		</div>
	</div>

	{{-- Range Tabs --}}
	<div class="columns" style="margin-top: 20px;"> {{-- todo: replace styling with something more 'human' xD --}}
        <div class="column"> {{-- todo: set active class --}}
            <div class="tabs is-centered is-toggle">
              <ul>
                <li @if(!isset($_GET['active'])) class="is-active" @endif>
                  <a href="{{ action('ShortenController@stats', $shorten->slug) }}">
                    <span class="icon is-small"><i class="fa fa-calendar"></i></span>
                    <span>All the time</span>
                  </a>
                </li>
                <li @if(isset($_GET['active']) && $_GET['active'] == '24h') class="is-active" @endif>
                  <a href="{{ action('ShortenController@stats',
                  [
                  'id' => $shorten->slug,
                  'from' => \Carbon\Carbon::now()->subDay()->toDateTimeString(),
                  'to' => \Carbon\Carbon::now()->toDateTimeString(),
                  'active' => '24h'
                  ]) }}">
                    <span class="icon is-small"><i class="fa fa-circle-thin"></i></span>
                    <span>24 hours</span>
                  </a>
                </li>
                <li @if(isset($_GET['active']) && $_GET['active'] == '48h') class="is-active" @endif>
                  <a href="{{ action('ShortenController@stats',
                  ['id' => $shorten->slug,
                   'from' => \Carbon\Carbon::now()->subDays('2')->toDateTimeString(),
                   'to' => \Carbon\Carbon::today()->toDateTimeString(),
                   'active' => '48h'
                  ]) }}">
                    <span class="icon is-small"><i class="fa fa-angle-left"></i></span>
                    <span>48 hours</span>
                  </a>
                </li>
                <li @if(isset($_GET['active']) && $_GET['active'] == '1week') class="is-active" @endif>
                  <a href="{{ action('ShortenController@stats',
                  ['id' => $shorten->slug,
                   'from' => \Carbon\Carbon::now()->subWeek()->toDateTimeString(),
                   'to' => \Carbon\Carbon::today()->toDateTimeString(),
                   'active' => '1week'
                  ]) }}">
                    <span class="icon is-small"><i class="fa fa-angle-double-left"></i></span>
                    <span>1 week</span>
                  </a>
                </li>
				  <li @if(isset($_GET['active']) && $_GET['active'] == '1month') class="is-active" @endif>
					  <a href="{{ action('ShortenController@stats',
					  ['id' => $shorten->slug,
					   'from' => \Carbon\Carbon::now()->subMonth()->toDateTimeString(),
					   'to' => \Carbon\Carbon::today()->toDateTimeString(),
					   'active' => '1month'
					  ]) }}">
                    <span class="icon is-small"><i class="fa fa-angle-double-left"></i><i class="fa fa-angle-double-left"></i></span>
                    <span>1 month</span>
                  </a>
                </li>
                <li @if(isset($_GET['active']) && $_GET['active'] == 'custom') class="is-active" @endif>
                    <a href="#" onclick="hideShowCustomRange()" id="customRangeBtn">
                        <span class="icon is-small"><i class="fa fa-arrows-h"></i></span>
                        <span>Cusom range</span>
                    </a>
                </li>
              </ul>
            </div>
        </div>
    </div>

	{{-- Custom Range Form --}}
	<div class="columns is-hidden" id="customRange">
        <div class="column is-offset-4">
            <form class="field has-addons" action="{{ url()->current() }}">
				<input type="hidden" value="custom" name="active">
                <div class="control">
                    <input class="input" placeholder="From" id="from-datepicker" name="from">
                </div>
                <div class="control">
                    <input class="input" placeholder="To" id="to-datepicker" name="to">
                </div>
                <div class="control">
                    <input type="hidden" name="range" value="custom">
                    <button class="button is-info">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

	<div class="container" style="margin-top: 30px;">
		<ul class="columns">
			<li class="column is-2"><b>Platform</b></li>
			<li class="column is-2"><b>Browser</b></li>
			<li class="column is-4"><b>Country</b></li>
			<li class="column is-4"><b>Referer</b></li>
		</ul>

		<div class="columns">
			<div class="column is-2">
				{{-- Platform --}}
                @if($stats->count() > 0)
                    @foreach($stats->groupBy('platform') as $key => $value)
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <span class="tag is-black">{{ $key }}</span>
                                </div>
                                <div class="column">
                                    <span class="tag is-light is-pulled-right">{{ $value->count() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

			<div class="column is-2">
				{{-- Browser --}}
				@if($stats->count() > 0)
					@foreach($stats->groupBy('browser') as $key => $value)
						<div>
							<div class="columns">
								<div class="column">
									<span class="tag is-black">{{ $key }}</span>
								</div>
								<div class="column">
									<span class="tag is-light is-pulled-right">{{ $value->count() }}</span>
								</div>
							</div>
						</div>
					@endforeach
				@endif
			</div>

			<div class="column is-4">
				{{-- Country --}}
				@if($stats->count() > 0)
					@foreach($stats->groupBy('country_name') as $key => $value)
						<div>
							<div class="columns">
								<div class="column">
									<span class="tag is-black">
										<span class="flag-icon flag-icon-{{ strtolower($shorten->getCountryCode($key)) }}"
										></span>&nbsp;{{ $key }}</span>
								</div>
								<div class="column">
									<span class="tag is-light">{{ $value->count() }}</span>
								</div>
							</div>
						</div>
					@endforeach
				@endif
			</div>

			<div class="column is-4">
				{{-- Http Referer --}}
				@if($stats->count() > 0)
					@foreach($stats->groupBy('http_referer') as $key => $value)
						<div>
							<div class="columns">
								<div class="column referer-col">
									<span class="tag is-black"><a href="{{ $key }}"><abbr title="{{ $key }}">{{ $key }}</abbr></a></span>
								</div>
								<div class="column">
									<span class="tag is-light is-pulled-right">{{ $value->count() }}</span>
								</div>
							</div>
						</div>
					@endforeach
				@endif
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		 function hideShowCustomRange()
		 {
			 let customRange = document.getElementById('customRange')
			 customRange.classList.toggle('is-hidden')
		 }

		 $('.referer-col span a abbr').each(function () {
			let value = $(this).text()
			let length = 50
			let trimmedValue = value.substring(0, length)
			$(this).text(trimmedValue + ' [...]')
		 })
	</script>
@endsection