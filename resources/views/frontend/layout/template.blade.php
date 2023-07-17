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
        <header class="menu d-flex flex-wrap justify-content-centerx navbar-light bg-transparent navbar-expand-md">
            <nav class="navbar container-fluid">
                <div class="col-md-5 col-2">
                    <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
                    <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
                    </button>
                    <div class="collapse navbar-collapse justify-content-start" id="navbarsExample01">
                    <ul class="nav nav-pills mr-auto navbar-nav ">
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link link-dark dropdown-toggle" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <div class="row">
                                <div class="col-md-3">
                                <li><a href="#" class="dropdown-item" type="button">Hoomans</a></li>
                                <li><a href="#" class="dropdown-item" type="button">Hat</a></li>
                                <li><a href="#" class="dropdown-item" type="button">Tote</a></li>
                                <li><a href="#" class="dropdown-item" type="button">T-Shirt</a></li>
                                </div>
                                <div class="col-md-3 dropdown-border-left">
                                <li><a href="#" class="dropdown-item" type="button">Doggos</a></li>
                                <li><a href="#" class="dropdown-item" type="button">Snoods (solid color, pattern)</a></li>
                                <li><a href="#" class="dropdown-item" type="button">Sweaters</a></li>
                                <li><a href="#" class="dropdown-item" type="button">Bandana</a></li>
                                <li><a href="#" class="dropdown-item" type="button">Scrunchie</a></li>
                                <li><a href="#" class="dropdown-item" type="button">Gift Card</a></li>
                                </div>
                                <div class="col-md-3">
                                <div class="container-image hide-shop-img">
                                    <div class="content">
                                    <img class="img-fluid" src="assets/images/product/dog1.webp">
                                    <div class="content-details-show">
                                        <p>Lorem ipsum dolor sit amet
                                        <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-3">
                                <div class="container-image hide-shop-img">
                                    <div class="content">
                                    <img class="img-fluid" src="assets/images/product/dog2.webp">
                                    <div class="content-details-show">
                                        <p>Lorem ipsum dolor sit amet
                                        <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="#" class="nav-link link-dark">Subscribe</a></li>
                        <li class="nav-item"><a href="#" class="nav-link link-dark">Sale</a></li>
                    </ul>
                </div></div>
                <!-- <div class="pseudo-col col-3"></div> -->
                <div class="col-md-2 col-8">
                    <center>
                    <a class="navbar-brand" href="index.html">Wild One</a>
                    </center>
                </div>
                <div class="col-md-5 col-2">
                    <div class="">
                    <div class="navbar-right nav-pills">
                    <div class="nav-item d-flex flex-wrap justify-content-end right-icon">
                        <a class="nav-link link-dark icon-shop-right" data-target="cart.html" href="#"><i class="fa fa-search"></i></a>
                        <a class="nav-link link-dark icon-shop-right" data-target="cart.html" href="#"><i class="fa fa-user"></i></a>
                        <a class="nav-link link-dark" data-target="cart.html" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa fa-shopping-cart"></i></a>
                    </div>
                    </div>
                    </div>
                </div>
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
