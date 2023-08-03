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
          <h1>FAQ</h1>
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
  <div class="row">
    <div class="accordion" id="accordionExample">
        @foreach($data_faq as $order => $key)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{$order}}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$order}}" aria-expanded="true" aria-controls="collapse{{$order}}">
                <b>{{$key->question}}</b>
            </button>
            </h2>
            <div id="collapse{{$order}}" class="accordion-collapse collapse {{$order == '0' ? 'show':''}}" aria-labelledby="heading{{$order}}" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                {!! $key->answer !!}
            </div>
            </div>
        </div>
        @endforeach
    </div>
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
