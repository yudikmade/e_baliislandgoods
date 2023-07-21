<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="img/fav.png">
<meta name="author" content="{{env('AUTHOR_SITE')}}">
<meta name="description" content="">
<meta name="keywords" content="">
<meta charset="UTF-8">
<link rel="shortcut icon" href="{{asset(env('URL_IMAGE').'favicon.ico')}}" type="image/x-icon">
<title>{{$title}}</title>

<link href="{{asset(env('URL_ASSETS').'bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" media="all" />
<link href="{{asset(env('URL_ASSETS').'font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'dist/css/AdminLTE.min.css')}}">
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'dist/css/skins/skin-blue.min.css')}}">
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'toastr/toastr.min.css')}}"/>
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'backstyle.css')}}">


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="{{asset(env('URL_ASSETS').'jquery.min.js')}}"></script>

<!-- Scripts -->
<script>
window.Laravel = <?php echo json_encode([
	'csrfToken' => csrf_token(),
]); ?>
</script>