<!doctype html>
<html class="no-js" lang="{{ Lang::getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@include('frontend.layout.head')
		@include('frontend.layout.global_header')
		@yield('style')
	</head>
	<body>
		{{ csrf_field() }}
		<!-- header -->
        
		<!-- !header -->
		<main>
		@yield('content')
		@include('frontend.layout.global_footer')
	    @include('frontend.layout.foot')
		@yield('script')
	</body>
</html>
