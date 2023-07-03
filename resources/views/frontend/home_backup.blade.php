@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/slick.css')}}" rel="stylesheet">
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/slick-theme.css')}}" rel="stylesheet">
<style>
    .carousel-caption h2 {
        font-size: 32px;
        color: #000;
    }
    .carousel-caption h5 {
        line-height: 24px;
        font-size: 22px;
        color: #000;
    }
    .carousel-caption h5.sub {
        line-height: 24px;
        font-size: 18px;
        color: #000;
    }
    .carousel-caption {
        top: 35%;
    }
    @media (max-width: 768px) {
        .carousel-caption h2 {
            font-size: 24px;
            color: #000;
        }
        .carousel-caption h5 {
            line-height: 20px;
            font-size: 18px;
            color: #000;
        }
        .carousel-caption h5.sub {
            line-height: 24px;
            font-size: 16px;
            color: #000;
        }
        .carousel-caption {
            top: 90px;
        }
    }
</style>
@stop

@section('content')
<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url('{{asset(env('URL_IMAGE').'slider/thumb/slider1.jpg')}}')">
        <div class="carousel-caption carousel-caption-left">
          <h5>PRODUCTS THAT GIVE BACK</h5>
          <h2>Your shopping dollars make a difference</h2>
          <h5 class="sub">Purchase any of our merchandise on our online store and know that your purchase will be going back to support our programs and conservation initiatives.</h5>
          <br>
          <a class="btn btn-shop-now" href="{{url('/contact-us')}}">CONTACT US</a>
        </div>
      </div>
    </div>

    <!-- <div class="container">
      <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div> -->
</div>
<div class="featurette-divider"></div>

<div class="container">
    <center>
    <h2>
        <br/>
        <a>Products</a>
        <div class="underline-title"></div>
    </h2>
    </center>
    <div class="featurette-divider"></div>
    <div class="row">
        @foreach($data_product as $key)
        <div class="col-md-3 mb-5">
            <div class="product-grid">
                <div class="product-image">
                    <a href="{{route('shop_detail_page')}}/{{strtolower(str_replace(' ', '-', $key->product_name)).'-'.$key->product_id}}" class="image">
                        <img class="pic-1" src="{{asset(env('URL_IMAGE').'product/thumb/'.$key->image)}}">
                        @php 
                        $getImage = \App\Models\EmProductImg::where('product_id',$key->product_id)->limit(1)->orderBy('img_id','DESC')->get();
                        @endphp
                        @if(count($getImage)>0)
                        <img class="pic-2" src="{{asset(env('URL_IMAGE').'product/thumb/'.$getImage[0]->image)}}">
                        @else 
                        <img class="pic-2" src="{{asset(env('URL_IMAGE').'product/thumb/'.$key->image)}}">
                        @endif
                    </a>
                    @if($key->stock > 0)
                        @if($key->discount > 0)
                            <span class="product-sale-label discount">-{{$key->discount}}%</span>
                        @endif
                    @endif
                    <ul class="product-links">
                        <!-- <li><a href="#"><i class="pe-7s-cart"></i></a></li>
                        <li><a href="#"><i class="pe-7s-like"></i></a></li> -->
                        <li><a href="{{route('shop_detail_page')}}/{{strtolower(str_replace(' ', '-', $key->product_name)).'-'.$key->product_id}}"><i class="pe-7s-look"></i></a></li>
                    </ul>
                </div>
                <div class="product-content">
                    <h3 class="title"><a>{{$key->product_name}}</a></h3>
                    <div class="price">
                        @php 
                        $setDiscount = \App\Helper\Common_helper::set_discount($key->price, $key->discount);
                        $priceAfterDisc = $setDiscount[0];
                        $discount = $setDiscount[1];

                        $priceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($priceAfterDisc);
                        $showPriceAfterDisc = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];

                        $priceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($key->price);
                        $showPriceNormal = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];
                        @endphp
                        @if($key->stock > 0)
                            @if(is_null($key->discount) ||  $key->discount == '0')
                                {{$showPriceAfterDisc}}
                            @else
                                <span>{{$showPriceNormal}}</span> {{$showPriceAfterDisc}}
                            @endif
                        @else 
                            Sold Out
                        @endif
                    </div>
                    <br/>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12 mb-5">
            <center>
                <a href="{{url('/shop')}}" class="btn btn-view-cart">SHOP ALL PRODUCTS</a>
            </center>
        </div>
    </div>
</div>

@if(count($data_category)>0)
<div class="featurette-divider"></div>
<div class="man-woman">
    <div class="row">
        <div class="col-md-4 middle-woman">
            <!-- <a href="#"> -->
                <img class="img-fluid zoom-thumbnail" src="{{asset(env('URL_IMAGE').'category/16695162204.jpg')}}">
            <!-- </a> -->
            <div class="woman-title">
                <div class="underline-title"></div>
                <h3>BCWF x MARK'S</h3>
            </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4 middle-man">
            <!-- <a href="#"> -->
                <img class="img-fluid zoom-thumbnail" src="{{asset(env('URL_IMAGE').'category/16695159903.jpg')}}">
            <!-- </a> -->
            <div class="man-title">
                <div class="underline-title"></div>
                <h3>BCWF</h3>
            </div>
        </div>
    </div>
</div>
@endif

<!-- <div class="featurette-divider"></div>
<div class="man-woman">
    <div class="row">
         <div class="col-md-12"><p>&nbsp;</p></div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3"><img class="img-fluid" src="{{asset(env('URL_IMAGE').'product/thumb/1.jpg')}}"></div>
        <div class="col-md-3">
            <div class="featurette-divider"></div>
            <h3>Our Story</h3>
            <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eleifend a lectus ac auctor. Nulla facilisi. Suspendisse potenti. Praesent nisl augue, porttitor ut tempor ac, consectetur ut loremLorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eleifend a lectus ac auctor. Nulla facilisi. Suspendisse potenti. Praesent nisl augue, porttitor ut tempor ac, consectetur ut lorem
            </p>
            <a href="{{url('/about-us')}}" class="read-more">Read more</a>
        </div>
        <div class="col-md-3"></div>
    </div>
</div> -->

<!-- <div class="featurette-divider"></div>
<div class="container special-offer">
    <div class="row">
        <div class="col-md-6 d-flex align-items-center">
            <div class="special-offer-text">
            <div class="btn btn-black">UP TO</div>
            <h2><b>50% off</b></h2>
            <h2>SUMMER</h2>
            <h2>COLLECTION</h2>
            </div>
        </div>
        <div class="col-md-6"><img class="img-fluid" src="{{asset(env('URL_IMAGE').'product/thumb/2.jpg')}}"></div>
    </div>
</div> -->

<div class="featurette-divider"></div>
<div class="tautan-terkait">
    <div class="container">
        <!-- <center>
            <h2>
            <a href="#">Just Arrived</a>
            <div class="underline-title"></div>
            </h2>
        </center> -->
        <div class="featurette-divider"></div>
        <section class="regular slider" style="padding: 0 50px;">
            @foreach($data_product_new as $key)
            <div>
                <div class="product-grid">
                    <div class="product-image">
                        <a href="{{route('shop_detail_page')}}/{{strtolower(str_replace(' ', '-', $key->product_name)).'-'.$key->product_id}}" class="image">
                            <img class="pic-1" src="{{asset(env('URL_IMAGE').'product/thumb/'.$key->image)}}">
                            @php 
                            $getImage = \App\Models\EmProductImg::where('product_id',$key->product_id)->limit(1)->orderBy('img_id','DESC')->get();
                            @endphp
                            @if(count($getImage)>0)
                            <img class="pic-2" src="{{asset(env('URL_IMAGE').'product/thumb/'.$getImage[0]->image)}}">
                            @else 
                            <img class="pic-2" src="{{asset(env('URL_IMAGE').'product/thumb/'.$key->image)}}">
                            @endif
                        </a>
                        @if($key->stock > 0)
                            @if($key->discount > 0)
                                <span class="product-sale-label discount">-{{$key->discount}}%</span>
                            @endif
                        @endif
                        <ul class="product-links">
                            <!-- <li><a href="#"><i class="pe-7s-cart"></i></a></li>
                            <li><a href="#"><i class="pe-7s-like"></i></a></li> -->
                            <li><a href="{{route('shop_detail_page')}}/{{strtolower(str_replace(' ', '-', $key->product_name)).'-'.$key->product_id}}"><i class="pe-7s-look"></i></a></li>
                        </ul>
                    </div>
                    <div class="product-content">
                        <h3 class="title"><a>{{$key->product_name}}</a></h3>
                        <div class="price">
                            @php 
                            $setDiscount = \App\Helper\Common_helper::set_discount($key->price, $key->discount);
                            $priceAfterDisc = $setDiscount[0];
                            $discount = $setDiscount[1];

                            $priceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($priceAfterDisc);
                            $showPriceAfterDisc = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];

                            $priceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($key->price);
                            $showPriceNormal = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];
                            @endphp
                            @if($key->stock > 0)
                                @if(is_null($key->discount) ||  $key->discount == '0')
                                    {{$showPriceAfterDisc}}
                                @else
                                    <span>{{$showPriceNormal}}</span> {{$showPriceAfterDisc}}
                                @endif
                            @else 
                                Sold Out
                            @endif
                        </div>
                        <br/>
                    </div>
                </div>
            </div>
            @endforeach
        </section>
    </div>
</div>

<div class="featurette-divider"></div>
<div class="black-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-4 black-bg-item">
            <center>
            <div class="round-icon d-flex align-items-center justify-content-center">
                <img src="assets/images/delivery.png" class="img-fluid">
            </div>
            <br>
                <h4>Free Shipping</h4>
                <p>Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s, when an unknown printer took.</p>
            </center>
            </div>
            <div class="col-md-4 black-bg-item">
            <center>
            <div class="round-icon d-flex align-items-center justify-content-center">
                <img src="assets/images/secure.png" class="img-fluid">
            </div>
            <br>
                <h4>Secure payment</h4>
                <p>Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s, when an unknown printer took.</p>
            </center>
            </div>
            <div class="col-md-4">
            <center>
            <div class="round-icon d-flex align-items-center justify-content-center">
                <img src="assets/images/delivery.png" class="img-fluid">
            </div>
            <br>
                <h4>30 Days Money Back</h4>
                <p>Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s, when an unknown printer took.</p>
            </center>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'frontend/dist/js/slick.js')}}"></script>
<script>
    $(document).ready(function () {
        $(".regular").slick({
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            prevArrow: "<div class='slick-prev' aria-label='Next' type='button'><i class='fas fa-angle-left'></i></div>",
            nextArrow: "<div class='slick-next' aria-label='Next' type='button'><i class='fas fa-angle-right'></i></div>",
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>
@stop