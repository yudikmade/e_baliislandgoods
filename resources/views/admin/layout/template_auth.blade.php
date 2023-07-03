<!doctype html>
<html class="no-js" lang="{{ Lang::getLocale() }}">
	<head>
		@include('admin.layout.head')
		@yield('style')
	</head>
	<body>
		@yield('content')
		@include('admin.layout.foot')
		@yield('script')
	</body>
</html>