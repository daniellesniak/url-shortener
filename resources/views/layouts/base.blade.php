<!doctype html>
<html>
	<head>
		<title>{{ getenv('APP_TITLE') }} - @yield('title')</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- Bulma CSS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.5.1/css/bulma.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <!-- jquery-ui theme -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<!-- Custom CSS -->
		<link rel="stylesheet" href="{{ asset('/css/app.css') }}">

		<!-- Begin Cookie Consent (message about cookies policy) -->
		<script type="text/javascript">
		    window.cookieconsent_options = {"message":"This website uses cookies to ensure you get the best experience on our website","dismiss":"Got it!","learnMore":"More info","link":null,"theme":"dark-floating"};
		</script>

		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.10/cookieconsent.min.js"></script>

		@yield('stylesheets')
	</head>

	<body>
		<div class="container-fluid">
			<!-- Header -->
			@include('includes.header')
			
			@yield('content-fluid')

			@include('includes.footer')
		</div>
	</body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script
        src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
        integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
        crossorigin="anonymous"></script>
	<script src="{{ asset('/js/app.js') }}"></script>
    @yield('scripts')
</html>