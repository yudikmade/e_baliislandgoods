@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/carousel.css')}}" rel="stylesheet">
<style>
  .carousel-item{
    height: auto!important;
    max-height: 800px !important;
    background-size: cover;
  }
  .carousel-item img{
    width: 100%;
    height: auto;
  }
</style>
@stop

@section('content')
<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      @foreach($banner as $index => $key)
      <!-- <div class="carousel-item {{$index == '0' ? 'active':''}}" style="background-image: url('{{asset(env('URL_IMAGE').'slide/thumb/'.$key->image)}}')">
        <div class="carousel-caption">
          <div class="row">
            <div class="col-md-6">
                <h5>{{$key->title}}</h5>
                <h2>{{$key->subtitle}}</h2>
            </div>
            <div class="col-md-6"></div>
          </div>
        </div>
      </div> -->
      <div class="carousel-item {{$index == '0' ? 'active':''}}">
        <img src="{{asset(env('URL_IMAGE').'slide/thumb/'.$key->image)}}" />
      </div>
      @endforeach
    </div>
	<div class="container">
      <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
</div>

<div class="featurette-divider"></div>

<div class="container-fluid">
    <div class="row">
      <div class="col-md-4 col-12 order-md-1 order-2">
        <div class="opening-desc">
          <h1>Bali Island Goods</h1>
          {!! $home_text !!}
        </div>
      </div>
      <div class="col-md-8 order-md-2 order-1">
        <div class="row g-0">
          <div class="col-md-4 col-4 d-flex align-items-end">
            <div class="opening-img-1">
              @if($home_image_1 != '')
              <img class="img-fluid" src="{{asset(env('URL_IMAGE').'home/thumb/'.$home_image_1)}}">
              @endif
            </div>
          </div>
          <div class="col-md-5 col-6">
            <div class="opening-img-2">
              @if($home_image_2 != '')
              <img class="img-fluid" src="{{asset(env('URL_IMAGE').'home/thumb/'.$home_image_2)}}">
              @endif
            </div>
          </div>
          <div class="col-md-3 col-2 d-flex align-items-end">
            <div class="opening-img-3">
              @if($home_image_3 != '')
              <img class="img-fluid" src="{{asset(env('URL_IMAGE').'home/thumb/'.$home_image_3)}}">
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="featurette-divider"></div>
@if(count($best_seller)>0)
<div class="container-fluid">
      <h2>Best Sellers</h2>
      <div class="row">
        @foreach($best_seller as $key)
        @php 
        $detail = \App\Helper\Common_helper::generateProduct($key);
        @endphp
        <div class="col-md-3 col-6">
          <div class="product-grid">
            @if($detail['discount'] != '0')
            <div class="product-label product-label-save">Save {{$detail['discount']}}%</div>
            @endif
            <div id="bestseller{{$detail['id']}}" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
              <div class="carousel-indicators">
                @foreach($detail['image'] as $index => $value)
                <button type="button" data-bs-target="#bestseller{{$detail['id']}}" data-bs-slide-to="{{$index}}" aria-label="Slide {{$index}}" class="{{$index=='0' ? 'active':''}}" style="background-color:{{$value['color']}}"></button>
                @endforeach
              </div>
              <div class="carousel-inner">
                @foreach($detail['image'] as $index => $value)
                <div class="carousel-item {{$index=='0'?'active':''}}">
                  <div class="product-image">
                      <a href="{{$detail['link']}}" class="image">
                        @foreach($value['image'] as $idximg => $img)
                        <img class="pic-{{$idximg+1}}" src="{{asset(env('URL_IMAGE').'product/thumb/'.$img['image'])}}">
                        @endforeach
                      </a>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <div class="featurette-divider"></div>
            <div class="product-content">
                <h3 class="title"><a href="{{$detail['link']}}">{{$detail['product']}}</a></h3>
                <div class="price">{{$detail['description']}}</div>
                <br>
                <center>
                  <a class="btn btn-white" href="{{$detail['link']}}">{!! $detail['showPriceHTML'] !!}</a>
                </center>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="featurette-divider"></div>
</div>
<div class="featurette-divider"></div>
@endif

<div class="row g-0">
      <div class="col-md-6">
        <img class="img-fluid" src="assets/images/banner.webp">
      </div>
      <div class="col-md-6 purple-bg d-flex justify-content-center align-items-center">
        <div class="caption-banner">
          <h2>Contact</h2>
          <p>Have your favorite pet essentials delivered right when you need them. Plus, earn bonus perks like savings, free shipping, and surprise gifts.</p>
          <a class="btn btn-shop-now" href="{{url('/contact-us')}}">Contact Us</a>
        </div>
      </div>
</div>

<div class="featurette-divider"></div>

@if(count($favorite_kits)>0)
<div class="container-fluid">
      <h2>Favorite Kits</h2>
      <div class="row">
        @foreach($best_seller as $key)
        @php 
        $detail = \App\Helper\Common_helper::generateProduct($key);
        @endphp
        <div class="col-md-3 col-6">
          <div class="product-grid">
            @if($detail['discount'] != '0')
            <div class="product-label product-label-save">Save {{$detail['discount']}}%</div>
            @endif
            <div id="favkit{{$detail['id']}}" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
              <div class="carousel-indicators">
                @foreach($detail['image'] as $index => $value)
                <button type="button" data-bs-target="#favkit{{$detail['id']}}" data-bs-slide-to="{{$index}}" aria-label="Slide {{$index}}" class="{{$index=='0' ? 'active':''}}" style="background-color:{{$value['color']}}"></button>
                @endforeach
              </div>
              <div class="carousel-inner">
                @foreach($detail['image'] as $index => $value)
                <div class="carousel-item {{$index=='0'?'active':''}}">
                  <div class="product-image">
                      <a href="{{$detail['link']}}" class="image">
                        @foreach($value['image'] as $idximg => $img)
                        <img class="pic-{{$idximg+1}}" src="{{asset(env('URL_IMAGE').'product/thumb/'.$img['image'])}}">
                        @endforeach
                      </a>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <div class="featurette-divider"></div>
            <div class="product-content">
                <h3 class="title"><a href="{{$detail['link']}}">{{$detail['product']}}</a></h3>
                <div class="price">{{$detail['description']}}</div>
                <br>
                <center>
                  <a class="btn btn-white" href="{{$detail['link']}}">{!! $detail['showPriceHTML'] !!}</a>
                </center>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="featurette-divider"></div>
</div>
<div class="featurette-divider"></div>
@endif

@stop

@section('script')
@stop