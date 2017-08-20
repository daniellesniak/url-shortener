@extends('layouts.base')

@section('title', "Statistics ($id)")

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
					<span class="tag is-dark is-large"><b>{{ $totalRedirects }}</b></span>
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
					<a href='{{ $shortenUrl }}' class="control is-expanded" target="_blank">
						<input class="input" readonly value="{{ $shortenUrl }}" title="Shorten Url">
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
					<a href="{{ $destinationUrl }}" class="control is-expanded" target="_blank">
						<input class="input" type="text" readonly value="{{ $destinationUrl }}" title="Destintation Url">
					</a>
				</div>
			</div>
		</div>

		{{-- Range Tabs --}}
		<div class="columns">
			<div class="column">
				<div class="tabs is-centered is-toggle">
				  <ul>
				    <li>
				      <a href="{{ action('UrlController@stats', $id) }}">
				        <span class="icon is-small"><i class="fa fa-calendar"></i></span>
				        <span>All the time</span>
				      </a>
				    </li>
				    <li>
				      <a href="{{ action('UrlController@stats', ['id' => $id, 'range' => '24h']) }}">
				        <span class="icon is-small"><i class="fa fa-circle-thin"></i></span>
				        <span>24 hours</span>
				      </a>
				    </li>
				    <li>
				      <a href="{{ action('UrlController@stats', ['id' => $id, 'range' => '48h']) }}">
				        <span class="icon is-small"><i class="fa fa-angle-left"></i></span>
				        <span>48 hours</span>
				      </a>
				    </li>
				    <li>
				      <a href="{{ action('UrlController@stats', ['id' => $id, 'range' => 'week']) }}">
				        <span class="icon is-small"><i class="fa fa-angle-double-left"></i></span>
				        <span>1 week</span>
				      </a>
				    </li>
				    <li>
				      <a href="{{ action('UrlController@stats', ['id' => $id, 'range' => 'month']) }}">
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

		{{-- Custom Range Form --}}
		<div class="columns is-hidden" id="customRange">
			<div class="column">
				<div class="columns">
					<div class="column"></div>
					<form class="control is-horizontal" action="{{ url()->current() }}">
					    <div class="control is-grouped">
					    <p class="control is-expanded">
					      <input class="input" type="datetime-local" placeholder="From" name="from">
					    </p>
					    <p class="control is-expanded">
					      <input class="input" type="datetime-local" placeholder="To" name="to">
					    </p>
					  	</div>
					  	<input type="submit" class="button is-info" style="margin-left: 8px;">
					</form>
					<div class="column"></div>
				</div>
			</div>
		</div>

		{{-- Redirects' Stats --}}
		{{--<div class="columns">
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
									@foreach($countryStats as $key => $value)
										<span class="flag-icon flag-icon-{{ strtolower(convertCountryToIso($key)) }}"></span> {{ $key }} - <strong>{{ $countryPercent[$key] }}%</strong>
										 ({{ $value }})<br>
									@endforeach
								@else
									<p class="has-text-centered">NO REDIRECTS!</p>
								@endif
							</div>

							<div class="column is-5">
								@if($httpRefererStats != null)
									@foreach($httpRefererStats as $key => $value)
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
		</div>--}}
	</div>

	<div class="container" style="margin-top: 30px;"> <!-- todo: remove -->
		{{--<table class="table">
			<thead>
				<tr>
					<th>Operating System</th>
					<th>Browser</th>
					<th>Country</th>
					<th>Referer</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<th></th>
				</tr>
			</tbody>
		</table>--}}

        <div class="columns">
            <div class="column is-2"><b>Operating System</b></div>
            <div class="column is-2"><b>Browser</b></div>
            <div class="column is-4"><b>Country</b></div>
            <div class="column is-4"><b>Referer</b></div>
        </div>

        <div class="columns">
            <div class="column is-2">
                {{-- Platform Stats --}}
                @if($platformStats != null)
                    @foreach($platformStats as $key => $value)
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <span class="tag is-black">{{ $key }}</span>
                                </div>
                                <div class="column">
                                    <span class="tag is-light">{{ $value }} [{{ $platformPercent[$key] }}%]</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            {{-- Browser Stats --}}
            <div class="column is-2">
                @if($browserStats != null)
                    @foreach($browserStats as $key => $value)
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <span class="tag is-black">{{ $key }}</span>
                                </div>
                                <div class="column">
                                    <span class="tag is-light">{{ $value }} [{{ $browserPercent[$key] }}%]</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            {{-- Country Stats --}}
            <div class="column is-4">
                @if($countryStats != null)
                    @foreach($countryStats as $key => $value)
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <span class="tag is-black"><span class="flag-icon flag-icon-{{ strtolower(convertCountryToIso($key)) }}"></span>&nbsp{{ $key }}</span>
                                </div>
                                <div class="column">
                                    <span class="tag is-light">{{ $value }} [{{ $countryPercent[$key] }}%]</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            {{-- Referer Stats --}}
            <div class="column is-4">
                @if($httpRefererStats != null)
                    @foreach($httpRefererStats as $key => $value)
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <span class="tag is-black">
                                    @if($key == false)
                                        (no referer)
                                    @else
                                        <abbr title="{{ $key }}"><a href="{{ $key }}" class="refererAnchor">{{ $key }}</a></abbr>
                                    @endif
                                    </span>
                                </div>
                                <div class="column">
                                    <span class="tag is-light">{{ $value }} [{{ $httpRefererPercent[$key] }}%]</span>
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
			 customRange = document.getElementById('customRange');
			 customRange.classList.toggle('is-hidden');
		 }
	</script>
@endsection