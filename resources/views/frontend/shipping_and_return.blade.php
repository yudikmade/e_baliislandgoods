@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/carousel.css')}}" rel="stylesheet">
@stop

@section('content')
<div class="title-other-page">
  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url('{{asset(env('URL_IMAGE').'banner-other.webp')}}');opacity:0.9">
        <div class="carousel-caption carousel-caption-left">
        <center>
          <h1>SHIPPING AND RETURN</h1>
          <br>
        </center>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="featurette-divider"></div>
  <br/>
  <div class="row mb-3">
    <div class="col-sm-2"></div>
    <div class="col-sm-8"></div>
    <div class="col-sm-2"></div>
  </div>
  <div class="row mb-3">
    {!! $data !!}
  </div>
  <div class="featurette-divider"></div>
  <br/>
</div>
@stop

@section('script')
<script>
$(document).ready(function () {
    
});
</script>
@stop
