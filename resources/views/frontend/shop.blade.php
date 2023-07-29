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
      <div class="col-md-6 pt-2">
        <form class="row">
          <div class="col-md-12 row">
            <label class="col col-form-label"></label>
            <label for="staticEmail" class="col-sm-2 col-form-label">Filter By</label>
            <div class="col-sm-6">
              <div class="dropdown">
                <a href="#" class="btn btn-shop-now-reverse2 dropdown-toggle" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">{{$text_category}}</a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <a href="{{route('shop_page')}}" class="box-size-options" type="button"><p>ALL</p></a>
                  @foreach($data_product_category as $key)
                  <a href="{{route('shop_page')}}/{{str_replace(' ', '-', strtolower($key->category)).'-'.$key->category_id}}" class="box-size-options" type="button"><p>{{$key->category}}</p></a>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </form>
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
        <div class="infinite-scroll" id="products">
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
  $('.infinite-scroll').find('.jscroll-inner').addClass('row');
});
</script>
@stop
