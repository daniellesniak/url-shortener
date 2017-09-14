@extends('layouts.base')

@section('title', $basicInfo['id'] . " - statistics")

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
					<span class="tag is-dark is-large"><b>{{ $basicInfo['totalRedirects'] }}</b></span>
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
					<a href='{{ $basicInfo['shortenUrl'] }}' class="control is-expanded" target="_blank">
						<input class="input" readonly value="{{ $basicInfo['shortenUrl'] }}" title="Shorten Url">
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
					<a href="{{ $basicInfo['destinationUrl'] }}" class="control is-expanded" target="_blank">
						<input class="input" type="text" readonly value="{{ $basicInfo['destinationUrl'] }}" title="Destintation Url">
					</a>
				</div>
			</div>
		</div>

		{{-- Range Tabs --}}
		<div class="columns">
			<div class="column">
				<div class="tabs is-centered is-toggle">
				  <ul>
				    <li @if($activeTab == 'all') class="is-active" @endif>
				      <a href="{{ action('ShortenController@stats', $basicInfo['id']) }}">
				        <span class="icon is-small"><i class="fa fa-calendar"></i></span>
				        <span>All the time</span>
				      </a>
				    </li>
				    <li @if($activeTab == '24h') class="is-active" @endif>
				      <a href="{{ action('ShortenController@stats', ['id' => $basicInfo['id'], 'range' => '24h']) }}">
				        <span class="icon is-small"><i class="fa fa-circle-thin"></i></span>
				        <span>24 hours</span>
				      </a>
				    </li>
				    <li @if($activeTab == '48h') class="is-active" @endif>
				      <a href="{{ action('ShortenController@stats', ['id' => $basicInfo['id'], 'range' => '48h']) }}">
				        <span class="icon is-small"><i class="fa fa-angle-left"></i></span>
				        <span>48 hours</span>
				      </a>
				    </li>
				    <li @if($activeTab == 'week') class="is-active" @endif>
				      <a href="{{ action('ShortenController@stats', ['id' => $basicInfo['id'], 'range' => 'week']) }}">
				        <span class="icon is-small"><i class="fa fa-angle-double-left"></i></span>
				        <span>1 week</span>
				      </a>
				    </li>
				    <li @if($activeTab == 'month') class="is-active" @endif>
				      <a href="{{ action('ShortenController@stats', ['id' => $basicInfo['id'], 'range' => 'month']) }}">
				        <span class="icon is-small"><i class="fa fa-angle-double-left"></i><i class="fa fa-angle-double-left"></i></span>
				        <span>1 month</span>
				      </a>
				    </li>
				    <li @if($activeTab == 'custom') class="is-active" @endif>
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

	<div class="container" style="margin-top: 30px;"> <!-- todo: remove -->
        <ul class="columns">
            <li class="column is-2"><b>Operating System</b></li>
            <li class="column is-2"><b>Browser</b></li>
            <li class="column is-4"><b>Country</b></li>
            <li class="column is-4"><b>Referer</b></li>
        </ul>

        <div class="columns">
            <div class="column is-2">
                {{-- Platform Stats --}}
                @if($statistics['platforms']['data'] != null)
                    @foreach($statistics['platforms']['data'] as $key => $value)
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <span class="tag is-black">{{ $key }}</span>
                                </div>
                                <div class="column">
                                    <span class="tag is-light">{{ $value }} [{{ $statistics['platforms']['percent'][$key] }}%]</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            {{-- Browser Stats --}}
            <div class="column is-2">
                @if($statistics['browsers']['data'] != null)
                    @foreach($statistics['browsers']['data'] as $key => $value)
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <span class="tag is-black">{{ $key }}</span>
                                </div>
                                <div class="column">
                                    <span class="tag is-light">{{ $value }} [{{ $statistics['browsers']['percent'][$key] }}%]</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            {{-- Country Stats --}}
            <div class="column is-4">
                @if($statistics['countries']['data'] != null)
                    @foreach($statistics['countries']['data'] as $key => $value)
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <span class="tag is-black"><span class="flag-icon flag-icon-{{ strtolower(convertCountryToIso($key)) }}"></span>&nbsp{{ $key }}</span>
                                </div>
                                <div class="column">
                                    <span class="tag is-light">{{ $value }} [{{ $statistics['countries']['percent'][$key] }}%]</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            {{-- Referer Stats --}}
            <div class="column is-4">
                @if($statistics['referers']['data'] != null)
                    @foreach($statistics['referers']['data'] as $key => $value)
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
                                    <span class="tag is-light">{{ $value }} [{{ $statistics['referers']['percent'][$key] }}%]</span>
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