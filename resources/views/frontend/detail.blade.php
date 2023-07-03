@extends('frontend.layout.template')

@section('style')
<link rel="stylesheet" type="text/css" href="{{asset(env('URL_ASSETS').'zoom/xzoom.css')}}" media="all" /> 
<link type="text/css" rel="stylesheet" media="all" href="{{asset(env('URL_ASSETS').'zoom/fancybox/source/jquery.fancybox.css')}}" />
<link type="text/css" rel="stylesheet" media="all" href="{{asset(env('URL_ASSETS').'zoom/magnific-popup/css/magnific-popup.css')}}" />
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'select2/select2.min.css')}}">
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/shop.css')}}" rel="stylesheet">
@stop

@section('content')
<div class="container detail-product margin-other-page">
  <div class="row">
    @if(sizeof($data_product) == 0)
      <div class="alert alert-info col-sm-12 text-center">
          Sorry, product not available.<br>
          Please choose another product, thank you.
      </div>
    @else
      @if($data_product[0]->stock == 0)
        <div class="alert alert-info col-sm-12 text-center">
            Sorry, this product has run out of stock.
        </div>
      @endif  
    @endif
  </div>
  <?php $tmpSizeByColor = array();?>
  @foreach($data_product as $products)
  <div class="row">
    <div class="col-md-5">
        <div class="row">
          <div class="preview col-md-12 hidden-xs">
            <section id="magnific">
                <div class="xzoom-container">
                  @if(sizeof($data_image) > 0)
                      <img class="xzoom img-fluid" id="xzoom-default" src="{{asset(env('URL_IMAGE').'product/thumb/'.$data_image[0]->image)}}" xoriginal="{{asset(env('URL_IMAGE').'product/'.$data_image[0]->image)}}" />
                    @endif

                    <div class="xzoom-thumbs">
                      @foreach ($data_image as $images)
                        <a href="{{asset(env('URL_IMAGE').'product/'.$images->image)}}"><img class="xzoom-gallery" width="80" src="{{asset(env('URL_IMAGE').'product/thumb_sm/'.$images->image)}}"  xpreview="{{asset(env('URL_IMAGE').'product/thumb/'.$images->image)}}"></a>
                      @endforeach
                    </div>
                </div>        
            </section>
          </div>
        </div>
    </div>
    <div class="col-md-7 summary-product">
      <h1>{{$products->product_name}}</h1>
      <div class="product-price">
        @php 
        $setDiscount = \App\Helper\Common_helper::set_discount($products->price, $products->discount);
        $priceAfterDisc = $setDiscount[0];
        $discount = $setDiscount[1];

        $priceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($priceAfterDisc);
        $showPriceAfterDisc = $current_currency[1].$priceInCurrencyFormat[1];//.' '.$current_currency[2];

        $priceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($products->price);
        $showPriceNormal = $current_currency[1].$priceInCurrencyFormat[1];//.' '.$current_currency[2];
        @endphp
        <h5 class="mb-4">
          @if($discount == '0')
            {{$showPriceAfterDisc}}
          @else
            <strike>{{$showPriceNormal}}</strike>{{$showPriceAfterDisc}}
          @endif
        </h5>
      </div>
      @if($data_product[0]->stock != 0)
        <form>
          <input type="hidden" name="urlAddToCart" id="urlAddToCart" value="{{route('process_add_to_cart')}}">
          <input type="hidden" name="product_id" id="product_id" value="{{$data_product[0]->product_id}}">
          <?php
            $countColor = 0;
            $countSize = 0;
            foreach ($data_product_sku as $key) {
              if($key->color_hexa != ''){
                $countColor++;
              }

              if($key->size != ''){
                $countSize++;
              }
            }
          
            echo '
              <div class="row">';
                if($countColor > 0){
                  echo '
                    <div class="info-price col">
                      <span>Choose Color: </span>
                      <div class="plc-color">';
                        $active = '';
                        $tmpArray = array();
                        foreach ($data_product_sku as $key) {
                          if(!in_array($key->color_hexa, $tmpArray)){

                            array_push($tmpArray, $key->color_hexa);

                            echo '
                              <div data-hexa="'.$key->color_hexa.'" class="color '.$active.' select-color-item" style="background: '.$key->color_hexa.';" data-toggle="tooltip" title="'.$key->color_name.'"></div>';

                            $active = '';
                          }
                          array_push($tmpSizeByColor, array($key->color_hexa, $key->size, $key->stock));
                        }
                  echo '
                      </div>
                    </div>';
                }
                if($countSize > 0){
                  echo '
                    <div class="action col">
                      <span>Choose Size: </span>';
                      $tmpArray = array();
                      echo '
                      <select class="select2" name="select-size" id="select-size">
                        <option value="">--Size--</option>';
                        foreach ($data_product_sku as $key) {
                          if($key->size != ''){
                            if(!in_array($key->size, $tmpArray)){
                              array_push($tmpArray, $key->size);
                              echo '<option value="'.$key->size.'">'.$key->size.'</option>';
                            }
                          }
                        }
                    echo '
                      </select>
                    </div>
                  ';
                }
            echo '
              </div>
            ';
          ?>

          <div class="row mt-3">
            <div class="action col-sm-5">
              <span>Qty: </span>
              <div class="input-group select-qty">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
                        <span class="pe-7s-less"></span>
                    </button>
                </span>
                <input type="text" name="quant[1]" class="form-control input-number text-center" value="1" min="1" max="100">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">
                        <span class="pe-7s-plus"></span>
                    </button>
                </span>
              </div>
            </div>
          </div>
            
          <br/><br/>

          <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Product Description
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body product-desc">
                  {!! $products->description_html !!}
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Category
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  {!! $categoriesHtml !!}
                </div>
              </div>
            </div>
          </div>

        <br/>

          <div class="row mt-4">
            <div class="action col">
              <button type="button" class="btn btn-primary add-to-cart">Add To Cart</button>
            </div>
          </div>
        </form>
      @endif
      <div class="featurette-divider"></div>
    </div>
  </div>
  @endforeach
  <div class="featurette-divider"></div>
  @if(count($also_like)>0)
  <center>
  <h2>
      <a href="#">Related Products</a>
      <div class="underline-title"></div>
  </h2>
  </center>
  <div class="featurette-divider"></div>
  <div class="row">
    @foreach($also_like as $key)
    <div class="col-md-3 col-6">
      <div class="product-grid">
        <div class="product-image">
            <a href="{{route('shop_detail_page')}}/{{str_replace(' ', '-', $key->product_name).'-'.$key->product_id}}" class="image">
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
  @endif
</div>

<div class="featurette-divider"></div>
@stop

@section('script')
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset(env('URL_ASSETS').'zoom/xzoom.min.js')}}"></script>
<script type="text/javascript" src="{{asset(env('URL_ASSETS').'zoom/hammer.js/1.0.5/jquery.hammer.min.js')}}"></script>  
<script type="text/javascript" src="{{asset(env('URL_ASSETS').'zoom/fancybox/source/jquery.fancybox.js')}}"></script>
<script type="text/javascript" src="{{asset(env('URL_ASSETS').'zoom/magnific-popup/js/magnific-popup.js')}}"></script>   
<script src="{{asset(env('URL_ASSETS').'zoom/setup.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
      var json_size = <?php echo json_encode($tmpSizeByColor)?>;

      $('.select2').select2();

      $('.select-color-item').click(function(e){
        $('.select-color-item').removeClass('active');
        $(this).addClass('active');
        var chooseHexa = $(this).attr('data-hexa');

        var dataSizeOption = '<option value="">Size</option>';
        for(var i = 0; i < json_size.length; i++){
          var color_hexa = json_size[i][0];
          if(color_hexa == chooseHexa)
          {
            // dataSizeOption += '<option value="'+json_size[i][1]+'">'+json_size[i][1]+' ('+json_size[i][2]+')</option>';
            dataSizeOption += '<option value="'+json_size[i][1]+'">'+json_size[i][1]+'</option>';
          }
        }

        $('#select-size').html(dataSizeOption);
        $('#select-size').select2('val', '');
      });
    
      $('.add-to-cart').click(function(e){
        var size = $('#select-size').val();

        var dataHexa = undefined;
        var counterColor = 0;
        $('.select-color-item').each(function(index){
          if($(this).hasClass('active'))
          {
            dataHexa = $(this).attr('data-hexa');
          }
          counterColor++;
        });

        if(dataHexa == undefined)
        {
          if(counterColor > 0)
          {
            dataHexa = '';
          }
        }

        if(dataHexa == '')
        {
          toastr.warning('Please choose color.');
        }
        else
        {
          if(size == '')
          {
            toastr.warning('SilPlease choose size.');
          }
          else
          {
            var urlAction = $('#urlAddToCart').val();
            $.ajax({
                url: urlAction,
                dataType: 'json',
                type: 'POST',
                data: {
                  'product_id': $('#product_id').val(), 
                  'size': size,
                  'color': dataHexa,
                  'qty':  $('.input-number').val(),
                  '_token': $('input[name=_token]').val()
                },
                success: function(response, textStatus, XMLHttpRequest)
                {
                  toastr.remove();
                    if(response.trigger=="yes")
                    {
                      $('.count-fill-cart').text(response.count_cart);
                      $('.count-fill-cart').removeClass('none');
                      // right side cart
                      $('.right-side-cart-empty').addClass('none');
                      $('.right-side-cart').removeClass('none');
                      $('#right-side-cart').html(response.right_side_cart);
                        toastr.success(response.notif);
                    }
                    else
                    {
                         toastr.warning(response.notif, 'Product information :', {timeOut: 50000})
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                  toastr.remove();
                    toastr.error('There is something wrong, please refresh page and try again.');
                }
            });
          }
        }
      });
      $('.btn-number').click(function(e){
          e.preventDefault();
          
          fieldName = $(this).attr('data-field');
          type      = $(this).attr('data-type');
          var input = $("input[name='"+fieldName+"']");
          var currentVal = parseInt(input.val());
          if (!isNaN(currentVal)) {
              if(type == 'minus') {
                  
                  if(currentVal > input.attr('min')) {
                      input.val(currentVal - 1).change();
                  } 
                  if(parseInt(input.val()) == input.attr('min')) {
                      $(this).attr('disabled', true);
                  }

              } else if(type == 'plus') {

                  if(currentVal < input.attr('max')) {
                      input.val(currentVal + 1).change();
                  }
                  if(parseInt(input.val()) == input.attr('max')) {
                      $(this).attr('disabled', true);
                  }

              }
          } else {
              input.val(0);
          }
      });
      $('.input-number').focusin(function(){
         $(this).data('oldValue', $(this).val());
      });
      $('.input-number').change(function() {
          
          minValue =  parseInt($(this).attr('min'));
          maxValue =  parseInt($(this).attr('max'));
          valueCurrent = parseInt($(this).val());
          
          name = $(this).attr('name');
          if(valueCurrent >= minValue) {
              $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
          } else {
              // alert('Sorry, the minimum value was reached');
              $(this).val($(this).data('oldValue'));
          }
          if(valueCurrent <= maxValue) {
              $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
          } else {
              // alert('Sorry, the maximum value was reached');
              $(this).val($(this).data('oldValue'));
          }
          
          
      });
      $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                 // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) || 
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
</script>
@stop
