@extends('layouts.base')

@section('title', 'Your shorten URL is ready!')

@section('content-fluid')
	<div class="container">
		<div class="columns">
			<div class="column has-text-centered">
				<h1 class="title"><i class="fa fa-bar-chart"></i> STATISTICS</h1>
			</div>
		</div>
		<div class="columns">
			<div class="column">
				<article class="message">
					<div class="message-header has-text-centered">
						Select range
					</div>
					<div class="message-body">
						From: <input type="date" name="fromRange" value="{{ returnCurrentDate() }}">
						To: <input type="date" name="toRange" value="{{ returnCurrentDate() }}">
					</div>
				</article>
			</div>
		</div>
		<div class="columns">
			<div class="column">
			<canvas id="viewChart" width="400" height="150"></canvas>
			</div>
		</div>
		<div class="columns">
			<div class="column is-half">
				<canvas id="platformChart" width="400" height="300"></canvas>
			</div>
		</div>
	</div>
@endsection


@section('scripts')
	<!-- chart.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js" type="text/javascript"></script>

	<?php
		$labelsJson = json_encode($chartArray[0]);
		$toReplace = array('{', '}');
		$correctLabelsJosn = str_replace($toReplace, '', $labelsJson);
		
		$dataJson = json_encode($chartArray[1]);
		$correctDataJson = str_replace($toReplace, '', $dataJson);


		$monthLabelsJson = json_encode($monthArray[0]);
		$monthLabelsJson = str_replace($toReplace, '', $monthLabelsJson);

		$mothDataJson = json_encode($monthArray[1]);
		$mothDataJson = str_replace($toReplace, '', $mothDataJson);
	?>
	<!-- charts -->
	<script type="text/javascript">
		var backgroundColors = [
			'rgba(151,187,205,0.8)',
			'rgba(220,220,220,0.8)',
			'rgba(247,70,74,0.8)',
			'rgba(70,191,189,0.8)',
			'rgba(253,180,92,0.8)',
			'rgba(148,159,177,0.8)',
			'rgba(77,83,96,0.8)'
		];

		var borderColors = [
            'rgba(151,187,205,1)',
			'rgba(220,220,220,1)',
			'rgba(247,70,74,1)',
			'rgba(70,191,189,1)',
			'rgba(253,180,92,1)',
			'rgba(148,159,177,1)',
			'rgba(77,83,96,1)'
	    ];

		var ctx = document.getElementById('platformChart');
		var platformChart = new Chart(ctx, {
	    type: 'doughnut',
	    data: {
	        labels: {!! $correctLabelsJosn !!},
	        datasets: [{
	            label: '# of Views',
	            data: {!! $correctDataJson !!},
	            backgroundColor: backgroundColors,
	            borderColor: borderColors,
	            borderWidth: 3
	        }]
	    },
	    options: {
	        title: {
	        	display: true,
	        	text: 'by operating system',
	        	fontSize: 14
	        }
	    }
		});

		ctx = document.getElementById('viewChart');
		var viewChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: {!! $monthLabelsJson !!},
				datasets: [{
					label: '# of Views',
					data: {!! $mothDataJson !!},
					backgroundColor: backgroundColors,
					borderColor: borderColors,
					borderWidth: 3
				}]
			}
		});

	</script>
@endsection