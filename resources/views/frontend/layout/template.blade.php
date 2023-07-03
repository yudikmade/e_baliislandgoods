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
		@php 
		$checkCart = \App\Helper\Common_helper::checkCart();
		$getSocialMedia = \App\Models\EmSocialMedia::get();
		@endphp
        <header class="menu d-flex flex-wrap justify-content-centerx navbar-light bg-transparent navbar-expand-md fixed-top">
            <nav class="navbar">
                <!-- <div class="row"> -->
                <div class="col-md-5 col-12">
                    <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
                    <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
                    </button>
                    <div class="collapse navbar-collapse justify-content-start" id="navbarsExample01">
                    <ul class="nav nav-pills mr-auto navbar-nav ">
                        <li class="nav-item"><a href="https://bcwf.bc.ca/membership/" class="nav-link link-dark" target="_blank">JOIN</a></li>
                        <li class="nav-item"><a href="https://bcwf.bc.ca/donate/" class="nav-link link-dark" target="_blank">DONATE</a></li>
                        <li class="nav-item"><a href="{{url('/shop')}}" class="nav-link link-dark {{(isset($is_page) && $is_page == 'shop') ? 'active':''}}">SHOP</a></li>
                    </ul>
                </div></div>
                <div class="pseudo-col col-3"></div>
                <div class="col-md-2 col-6">
                    <center>
                        <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset(env('URL_IMAGE').'logo.png')}}" class="img-fluid"></a>
                    </center>
                </div>
                <div class="col-md-5 col-3">
                    <div class="">
                    <div class="navbar-right nav-pills">
                    <div class="nav-item d-flex flex-wrap justify-content-end right-icon">
                        <div class="nav-link" id="header-search-form">
                            <form id="form-search-header">
                                <div class="input-group has-search black">
                                <input name="category" id="category_search_header" type="hidden" value="all" />
                                <input name="search" id="keyword_search_header" type="text" value="" class="form-control" placeholder="Search products..." aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2" style="height:38px"><i class="pe-7s-search"></i></span>
                                </div>
                                </div>
                            </form>
                        </div>
                        <a class="nav-link link-dark pointer" id="header-search-btn">
                            <i class="pe-7s-search"></i>
                        </a>
                        <a class="nav-link link-dark pointer" data-bs-toggle="modal" data-bs-target="#myModalShop">
                            <i class="pe-7s-shopbag"></i>
                            <span class="position-absolute translate-middle badge rounded-pill bg-danger count-fill-cart {{$checkCart[1] == '0' ? 'none':''}}">{{$checkCart[1]}}</span>
                        </a>
                        @if(Session::get(env('SES_FRONTEND_ID')) != null)
                        <a class="nav-link link-dark icon-shop-right" href="{{url('/profile')}}"><i class="pe-7s-users"></i> <span class="customer-name">Hi, {{Session::get(env('SES_FRONTEND_NAME'))}}</span></a>
                        @else 
                        <a class="nav-link link-dark icon-shop-right" href="{{url('/login')}}"><i class="pe-7s-users"></i></a>
                        @endif
                    </div>
                    </div>
                    </div>
                </div>
                <!-- </div> -->
            </nav>
        </header>
		<!-- !header -->
		<main>
		@yield('content')
		@include('frontend.layout.global_footer')
	    @include('frontend.layout.foot')
		@yield('script')
	</body>
</html>
