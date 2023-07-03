@extends('frontend.layout.template')

@section('style')
@stop

@section('content')
<div class="title-other-page margin-other-page">
  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url('{{asset(env('URL_IMAGE').'slider/thumb/slider1.jpg')}}');opacity:0.9">
        <div class="carousel-caption carousel-caption-left">
        <center>
          <h1>ABOUT US</h1>
          <br>
        </center>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row mb-3">
    <div class="col-sm-2"></div>
    <div class="col-sm-8"><img class="img-fluid" src="{{asset(env('URL_IMAGE').'slider/thumb/slider2.jpg')}}"/></div>
    <div class="col-sm-2"></div>
  </div>
  <div class="row mb-3">
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
    <br/>
    <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.<br/>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
  </div>
</div>
@stop

@section('script')
<script>
$(document).ready(function () {
    
});
</script>
@stop
