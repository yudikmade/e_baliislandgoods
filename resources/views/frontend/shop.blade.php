@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/shop.css')}}" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Irish+Grover&display=swap" rel="stylesheet">
<style>
.jscroll-added {
  display: contents;
}
.for-page-shop-upper h5 b {
  font-family: 'Irish Grover';
}
</style>
@stop

@section('content')
<div id="myCarousel" class="carousel slide myCarousel for-page-shop-upper" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url('{{asset(env('URL_IMAGE').'banner.jpg')}}');background-color: rgba(0, 0, 0, 0.6);">
        <div class="carousel-caption">
          <h5>Your shopping dollars make a <b>difference</b>.</h5>
          <h2>PRODUCTS THAT GIVE BACK</h2>
        </div>
      </div>
    </div>
</div>
<div class="carousel slide myCarousel for-page-shop-middle" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <div class="carousel-caption">
      <center>
        <h3>
          Purchase any of our merchandise on our online store and know that your purchase will be going back to support our programs and conservation initiatives.
        </h3>
        <h3>
            Visit <a target="_blank" href="https://bcwf.bc.ca/">https://bcwf.bc.ca/</a> to learn more about these initiatives!
        </h3>
      </center>
      </div>
    </div>
  </div>
</div>
<div class="carousel slide myCarousel for-page-shop" data-bs-ride="carousel">
  <div id="show-category" class="carousel-inner">
    <div id="product-category" class="carousel-item active">
      <div class="carousel-caption">
      <center>
        <h1>{{$text_category}}</h1>
        {!! $desc_category !!}
      </center>
      </div>
    </div>
  </div>
</div>

<div class="container shop">
    @if($data_product == '')
    <div class="col-sm-12 mrg-tp-product"></div>
      <div class="alert alert-info col-sm-12 text-center">
        Sorry, product not available.<br>
        Please select another category, thank you.
      </div>
      <p>&nbsp;</p>
    </div>
    @else
    <div class="row">
      <div class="col-md-3 left-side mb-5">
        <h5 class="widget-title">Search</h5>
        <form id="form-search" class="mb-5">
            <div class="input-group has-search black">
              <input name="category" id="category_search" type="hidden" value="{{$category_selected}}" />
              <input name="search" id="keyword_search" type="text" value="{{$search}}" class="form-control" placeholder="Search products..." aria-describedby="basic-addon2">
              <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2"><i class="pe-7s-search"></i></span>
              </div>
            </div>
        </form>
        <div class="shop-dropdown mb-5">
          <h5>Category</h5>
          @foreach($data_product_category as $key)
            <a href="{{route('shop_page')}}/{{str_replace(' ', '-', strtolower($key->category)).'-'.$key->category_id}}#product-category">{{$key->category}}</a>
          @endforeach
        </div>
        <!-- <div class="mb-5">
          <h5>Sale Products</h5>
        </div> -->
      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-6 col-6"></div>
          <div class="col-md-6 col-6 d-flex flex-wrap justify-content-end">
            <a href="#" id="list" class="btn btn-transparent2 mrg-right">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-ul" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm-3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
              </svg>
            </a>
            <a href="#" id="grid" class="btn btn-transparent2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid" viewBox="0 0 16 16">
                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
              </svg>
            </a>
          </div>
        </div>
        <div class="row infinite-scroll" id="products">
        <?=$data_product?>
        </div>
      </div>
    </div>
    @endif
    <div class="featurette-divider"></div>
</div>
<div class="featurette-divider"></div>
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'jScroll/jquery.jscroll.min.js')}}"></script>
<script>
$(document).ready(function () {
  $('#list').click(function() {
    $('#products .item .product-grid').addClass('row');
    $('#products .item .product-grid').addClass('product-grid-boder-listview');
    $('#products .item .product-image').addClass('col-md-4 col-4');
    $('#products .item .product-content').addClass('col-md-8 col-8');
    $("#products .item").removeClass("col-md-4");
    $("#products .item").removeClass("col-6");
  });
  $('#grid').click(function() {
    $('#products .item .product-grid').removeClass('row');
    $('#products .item .product-grid').removeClass('product-grid-boder-listview');
    $('#products .item .product-image').removeClass('col-md-4 col-4');
    $('#products .item .product-content').removeClass('col-md-8 col-8');
    $('#products .item').addClass('col-md-4');
    $('#products .item').addClass('col-6');
  });
  $('#form-search').submit(function() {
    return false;
  });
  $('#form-search').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) { 
      e.preventDefault();

      var search_keyword = $('#keyword_search').val();
      var search_category = $('#category_search').val();

      if(search_keyword != ''){
        location.href = '{{route('shop_page')}}'+'/'+search_category+'/'+search_keyword;
      } 
      return false;
    }
  });
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
