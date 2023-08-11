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
        @php 
		$checkCart = \App\Helper\Common_helper::checkCart();
		$getSocialMedia = \App\Models\EmSocialMedia::get();
        $getProductCategory = \App\Models\EmProductCategory::where('status','1')->get();
        $getRandomProduct = \App\Models\EmProduct::getWithImage("",0,2,false,true);
        $global_currency = \App\Models\MCurrency::where('status','1')->get();
        $global_currency_selected = \App\Models\MCurrency::select('currency_id','symbol','code')->where('currency_id',Session::get(env('SES_GLOBAL_CURRENCY')))->first();
		@endphp
		<!-- header -->
        <header class="menu d-flex flex-wrap justify-content-centerx navbar-light bg-transparent navbar-expand-md">
            <nav class="navbar container-fluid">
                <div class="col-md-5 col-4">
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
                                    <!-- <li><a class="dropdown-item" type="button">Collections</a></li> -->
                                    <li><a href="{{route('shop_page')}}" class="dropdown-item" type="button">Show All</a></li>
                                </div>
                                <div class="col-md-3 dropdown-border-left">
                                    @foreach($getProductCategory as $key)
                                    <li><a href="{{route('shop_page')}}/{{str_replace(' ', '-', strtolower($key->category)).'-'.$key->category_id}}" class="dropdown-item" type="button">{{$key->category}}</a></li>
                                    @endforeach
                                </div>
                                @if(isset($getRandomProduct[0]))
                                <div class="col-md-3">
                                    <a href="{{route('shop_detail_page').'/'.str_replace(' ', '-', strtolower($getRandomProduct[0]->product_name)).'-'.$getRandomProduct[0]->product_id}}">
                                        <div class="container-image hide-shop-img">
                                            <div class="content">
                                                <img class="img-fluid" src="{{asset(env('URL_IMAGE').'product/thumb/'.$getRandomProduct[0]->image)}}">
                                                <div class="content-details-show">
                                                    @php 
                                                    $htmlDescription = '';
                                                    if(strlen(strip_tags($getRandomProduct[0]->description)) <= 100) {
                                                        $htmlDescription = strip_tags($getRandomProduct[0]->description);
                                                    } else {
                                                        $htmlDescription = substr(strip_tags($getRandomProduct[0]->description), 0, 100).'...';
                                                    }
                                                    @endphp
                                                    {{$htmlDescription}}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @if(isset($getRandomProduct[1]))
                                <div class="col-md-3">
                                <a href="{{route('shop_detail_page').'/'.str_replace(' ', '-', strtolower($getRandomProduct[1]->product_name)).'-'.$getRandomProduct[1]->product_id}}">
                                        <div class="container-image hide-shop-img">
                                            <div class="content">
                                                <img class="img-fluid" src="{{asset(env('URL_IMAGE').'product/thumb/'.$getRandomProduct[1]->image)}}">
                                                <div class="content-details-show">
                                                    @php 
                                                    $htmlDescription = '';
                                                    if(strlen(strip_tags($getRandomProduct[1]->description)) <= 100) {
                                                        $htmlDescription = strip_tags($getRandomProduct[1]->description);
                                                    } else {
                                                        $htmlDescription = substr(strip_tags($getRandomProduct[1]->description), 0, 100).'...';
                                                    }
                                                    @endphp
                                                    {{$htmlDescription}}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                            </div>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="{{url('/about-us')}}" class="nav-link link-dark">About</a></li>
                        <li class="nav-item"><a href="{{url('/contact-us')}}" class="nav-link link-dark">Contact</a></li>
                        <li class="nav-item dropdown">
							<a class="nav-link link-dark" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{isset($global_currency_selected->currency_id) ? $global_currency_selected->code.' ('.$global_currency_selected->symbol.')' : ''}}</a>
							
							<ul class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
								@foreach($global_currency as $key)
								<li><a class="dropdown-item" href="{{url('/currency/'.$key->code)}}">{{$key->code}}</a></li>
								@endforeach
							</ul>
							
						</li>
                        <li class="nav-item form-search-header-mobile">
                            <form id="form-search-header">
                                <div class="input-group has-search black">
                                <input name="category" id="category_search_header" type="hidden" value="all" />
                                <input name="search" id="keyword_search_header" type="text" value="" class="form-control" placeholder="Search products..." aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2" style="height:38px"><i class="pe-7s-search"></i></span>
                                </div>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div></div>
                <!-- <div class="pseudo-col col-3"></div> -->
                <div class="col-md-2 col-4">
                    <center>
                    <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset(env('URL_IMAGE').'logo.png')}}" alt=""/></a>
                    </center>
                </div>
                <div class="col-md-5 col-4">
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
                                <a class="nav-link link-dark icon-shop-right pointer" href="{{url('/profile')}}"><i class="pe-7s-users"></i> <p class="customer-name">Hi, {{Session::get(env('SES_FRONTEND_NAME'))}}</p></a>
                                @else 
                                <a class="nav-link link-dark icon-shop-right pointer" href="{{url('/login')}}"><i class="pe-7s-users"></i></a>
                                @endif
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
