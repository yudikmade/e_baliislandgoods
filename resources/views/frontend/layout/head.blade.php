<base href="{{url()->full()}}" />
<title>{{$title}}</title>

<meta name="format-detection" content="telephone=no">

<!-- share page ============================================= -->
<meta property="og:title" content="{{$share_page['title']}}"/>
<meta property="og:description" content="{{$share_page['description']}}"/>
<meta property="og:image" content="{{$share_page['image']}}"/>
<meta property="og:url" content="{{url()->full()}}"/>
<meta property="og:site_name" content="{{env('COMPANY_SITE')}}"/>
<meta property="og:type" content="website"/>
<!-- share page ============================================= -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#FFFFFF">
<meta name="description" content="{{$share_page['description']}}">
<meta name="keyword" content="{{$share_page['keyword']}}">
<meta name="author" content="{{env('COMPANY_SITE')}}">
<meta name="robots" content="index, follow" />
<meta name="lang" content="en" />

<!-- Favicons ============================================= -->
<link rel="shortcut icon" href="{{asset(env('URL_IMAGE').'favicon.ico')}}" type="image/x-icon">
<link rel="apple-touch-icon" sizes="57x57" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-57x57.png')}}">
<link rel="apple-touch-icon" sizes="60x60" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-60x60.png')}}">
<link rel="apple-touch-icon" sizes="72x72" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-72x72.png')}}">
<link rel="apple-touch-icon" sizes="76x76" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-76x76.png')}}">
<link rel="apple-touch-icon" sizes="114x114" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-114x114.png')}}">
<link rel="apple-touch-icon" sizes="120x120" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-120x120.png')}}">
<link rel="apple-touch-icon" sizes="144x144" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-144x144.png')}}">
<link rel="apple-touch-icon" sizes="152x152" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-152x152.png')}}">
<link rel="apple-touch-icon" sizes="180x180" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-180x180.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-144x144.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-114x114.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{asset(env('URL_IMAGE').'meta_site/apple-icon-72x72.png')}}">
<link rel="apple-touch-icon-precomposed" href="{{asset(env('URL_IMAGE').'meta_site/logo_vertical_bg_white.png')}}">
<link rel="apple-touch-icon" href="{{asset(env('URL_IMAGE').'meta_site/logo_vertical_bg_white.png')}}">

<link rel="icon" type="image/png" sizes="192x192"  href="{{asset(env('URL_IMAGE').'meta_site/android-icon-192x192.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{asset(env('URL_IMAGE').'meta_site/favicon-32x32.png')}}">
<link rel="icon" type="image/png" sizes="96x96" href="{{asset(env('URL_IMAGE').'meta_site/favicon-96x96.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{asset(env('URL_IMAGE').'meta_site/favicon-16x16.png')}}">
<!-- <link rel="manifest" href="{{asset(env('URL_IMAGE').'meta_site/manifest.json')}}"> -->
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{asset(env('URL_IMAGE').'meta_site/ms-icon-144x144.png')}}">
<meta name="theme-color" content="#ffffff">
<!-- Favicons ============================================= -->

<!-- Bootstrap core CSS -->
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/bootstrap.min.css')}}" rel="stylesheet">

<!-- pe 7 icon -->
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'pe-icon-7-stroke/css/pe-icon-7-stroke.css')}}">
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'pe-icon-7-stroke/css/helper.css')}}">

<!-- Custom styles for this template -->
<link href="{{asset(env('URL_ASSETS').'toastr/toastr.min.css')}}" rel="stylesheet"/>
<link href="{{asset(env('URL_ASSETS').'nprogress/nprogress.css')}}" rel="stylesheet" type="text/css" media="all">
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/style.css')}}" rel="stylesheet">

<script src="https://kit.fontawesome.com/6ade19a7ec.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Scripts -->
<script>
window.Laravel = <?php echo json_encode([
	'csrfToken' => csrf_token(),
]); ?>
</script>