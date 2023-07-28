@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/carousel.css')}}" rel="stylesheet">
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/shop.css')}}" rel="stylesheet">
<style>
.jscroll-added {
  display: contents;
}
</style>
@stop

@section('content')
<div class="purple-bg">
  <div class="container-fluid">
    <br>
    <div class="row">
      <div class="col-md-6">
        <h1>Shop</h1>
      </div>
    </div>
    <br>
  </div>
</div>

<div class="featurette-divider"></div>

<div class="container-fluid">
      <div class="row">
      @if($data_product == '')
      <div class="col-sm-12 mrg-tp-product"></div>
        <div class="alert alert-info col-sm-12 text-center">
          Sorry, product not available.<br>
          Please select another collection, thank you.
        </div>
        <p>&nbsp;</p>
      </div>
      @else
      <div class="col-md-12">
        <div class="row infinite-scroll" id="products">
          <?=$data_product?>
        </div>
      </div>
      @endif
      </div>
      <div class="featurette-divider"></div>
</div>
<div class="featurette-divider"></div>
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'jScroll/jquery.jscroll.min.js')}}"></script>
<script>
$(document).ready(function () {
  $('.infinite-scroll').jscroll({
      autoTrigger: true,
      loadingHtml: '<div class="col-xs-12" align="center" style="margin-top:10px;margin-bottom:10px;"></div>',
      padding: 0,
      nextSelector: 'a.jscroll-next',
      callback: function() {
          $('.pagination').remove();
      }
  });
});
</script>
@stop
