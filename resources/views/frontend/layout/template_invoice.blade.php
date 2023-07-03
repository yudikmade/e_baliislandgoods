<!doctype html>
<html class="no-js" lang="{{ Lang::getLocale() }}">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="author" content="{{env('AUTHOR_SITE')}}">
		<meta name="description" content="{{$description}}">
		<meta name="keywords" content="{{env('META_KEYWORDS')}}">
		<meta name="robots" content="index, follow" />
		<meta name="lang" content="en" />
		<meta charset="UTF-8">
		<link rel="shortcut icon" href="{{asset(env('URL_IMAGE').'meta_site/ico.ico')}}" type="image/x-icon">
		<title>{{$title}}</title>

		<link href="{{asset(env('URL_ASSETS').'bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" media="all" />
		<link href="{{asset(env('URL_ASSETS').'font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" media="all" />
		<link href="{{asset(env('URL_ASSETS').'frontstyle.css')}}" rel="stylesheet" type="text/css" media="all">

		<script src="{{asset(env('URL_ASSETS').'jquery.min.js')}}"></script>

		<!-- Scripts -->
		<script>
		window.Laravel = <?php echo json_encode([
			'csrfToken' => csrf_token(),
		]); ?>
		</script>
		@yield('style')
	</head>
	<body>
		<div class="container">
			<div class="col-sm-12 container-page">
				<div class="col-sm-12 container-content no-padding">
					<div class="row">
						@yield('content')
					</div>
				</div>
			</div>
		</div>
	</body>
</html>