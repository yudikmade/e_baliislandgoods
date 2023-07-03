@extends('frontend.layout.template')

@section('style')
<style>
    .carousel-caption h2 {
        font-size: 32px;
        color: #FFF;
    }
    .carousel-caption h5 {
        line-height: 24px;
        font-size: 22px;
        color: #FFF;
    }
    .carousel-caption h5.sub {
        line-height: 24px;
        font-size: 18px;
        color: #FFF;
    }
    .carousel-caption {
        top: 45%;
    }
    @media (max-width: 768px) {
        .carousel-caption h2 {
            font-size: 24px;
            color: #FFF;
        }
        .carousel-caption h5 {
            line-height: 20px;
            font-size: 18px;
            color: #FFF;
        }
        .carousel-caption h5.sub {
            line-height: 24px;
            font-size: 16px;
            color: #FFF;
        }
        .carousel-caption {
            top: 90px;
        }
    }
</style>
@stop

@section('content')
<div id="myCarousel" class="carousel slide myCarousel for-page-home" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url('{{asset(env('URL_IMAGE').'banner.jpg')}}')">
        <div class="carousel-caption carousel-caption-left">
          <h5>PRODUCTS THAT GIVE BACK</h5>
          <h2>Your shopping dollars make a difference</h2>
          <h5 class="sub">
            Purchase any of our merchandise on our online store and know that your purchase will be going back to support our programs and conservation initiatives.
          </h5>
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
@stop

@section('script')
@stop