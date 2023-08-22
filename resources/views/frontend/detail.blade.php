@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/carousel.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset(env('URL_ASSETS').'zoom/xzoom.css')}}" media="all" /> 
<link type="text/css" rel="stylesheet" media="all" href="{{asset(env('URL_ASSETS').'zoom/fancybox/source/jquery.fancybox.css')}}" />
<link type="text/css" rel="stylesheet" media="all" href="{{asset(env('URL_ASSETS').'zoom/magnific-popup/css/magnific-popup.css')}}" />
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'select2/select2.min.css')}}">
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/shop.css')}}" rel="stylesheet">
<style>
.product-detail-bg-white form p {
  margin-bottom:0;
}
.product-detail-bg-white form hr {
	margin-top:10px;
}
.plc-color {
	display: inline-block;
}
.select-color-item {
	width: 30px;
	height: 30px;
}
.input-group-btn {
	background:#FFF!important;
	border: 1px solid #ced4da;
}
.input-group-btn button span {
	color:#000 !important;
	padding-left:10px;
	padding-right:10px;
	margin-top:15px;
	font-size: 14px;
}
.btn-primary {
	border-radius: 0px;
	padding: 0px;
	font-size:20px;
	background:none!important;
}
</style>
@stop

@section('content')
<div class="bg-grey">
    <div class="container-fluid">
      <br/>
	  {!! $breadcrumbs !!}
	  <br/>

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
        <div class="col-md-7">
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
        <div class="col-md-5">
          <div class="product-detail-desc">
		  	@php 
			$setDiscount = \App\Helper\Common_helper::set_discount($products->price, $products->discount);
			$priceAfterDisc = $setDiscount[0];
			$discount = $setDiscount[1];

			$priceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($priceAfterDisc, "", false);
			$showPriceAfterDisc = $current_currency[1].$priceInCurrencyFormat[1];//.' '.$current_currency[2];

			$priceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($products->price, "", false);
			$showPriceNormal = $current_currency[1].$priceInCurrencyFormat[1];//.' '.$current_currency[2];
			@endphp
            <h5 class="mb-3">
				{{$products->product_name}}
				@if($discount == '0')
					<span>{{$showPriceAfterDisc}}</span>
				@else
					<strike>{{$showPriceNormal}}</strike>&nbsp;&nbsp;<span>{{$showPriceAfterDisc}}</span>
				@endif
			</h5>
			@if($data_product[0]->stock != 0)
            <div class="product-detail-bg-white">
				<form>
					<input type="hidden" name="urlAddToCart" id="urlAddToCart" value="{{route('process_add_to_cart')}}">
					<input type="hidden" name="product_id" id="product_id" value="{{$data_product[0]->product_id}}">
					@php
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
					@endphp
					@if($countColor > 0)
					<p class="mb-2">Select Your Color</p>
					@php 
					echo '
						<div class="info-price col">
							<div class="plc-color">';
								$active = '';
								$tmpArray = array();
								foreach ($data_product_sku as $key) {
								if(!in_array($key->color_hexa, $tmpArray)){

									array_push($tmpArray, $key->color_hexa);

									echo '
									<div data-hexa="'.$key->color_hexa.'" class="color '.$active.' select-color-item mb-2" style="background: '.$key->color_hexa.';" data-toggle="tooltip" title="'.$key->color_name.'"></div>';

									$active = '';
								}
								array_push($tmpSizeByColor, array($key->color_hexa, $key->size, $key->stock));
								}
						echo '
							</div>
						</div>';
					@endphp
					<hr>
					@endif
					@if($countSize > 0)
					<div class="row">
						@php 
						echo '
                    <div class="action col mb-4">
                      <span>Choose Size: </span>';
                      $tmpArray = array();
                      echo '
						<select class="select2" name="select-size" id="select-size">
							<option value="">-- Choose size --</option>';
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
					</div>';
						@endphp
					</div>
					@endif
					<div class="row">
						<div class="col-md-5">
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
						<div class="col-md-7"><button type="button" class="btn btn-shop-now-full add-to-cart">ADD TO CART</button></div>
					</div>
              	</form>
              <br>  
            </div>
			@endif
          </div>
        </div>
      </div>

      <div class="featurette-divider"></div>
      <br>
    </div>

    <div class="container">
        <div class="accordion" id="accordionExample">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Product Details
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse showx" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
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
		
		  @if($products->size_chart != '')
		  <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Size Chart
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <img src="{{asset(env('URL_IMAGE').'product/thumb/'.$products->size_chart)}}" class="img-fluid" alt=""/>
              </div>
            </div>
          </div>
		  @endif

        </div>
		<div class="featurette-divider"></div>
        <br><br>
    </div>
	@endforeach
  </div>

    <div class="featurette-divider"></div>

	@if(@count($also_like)>0)
    <div class="wave-bg"></div>
    <div class="purple-bg-detail">
    <div class="container">
      <h2>More Customer Favorites</h2>
      <br>
      <div class="row">
        @foreach($also_like as $key)
		@php 
        $detail = \App\Helper\Common_helper::generateProduct($key);
        @endphp
        <div class="col-md-3 col-6">
          <div class="product-grid">
            @if($detail['discount'] != '0')
            <div class="product-label product-label-save">Save {{$detail['discount']}}%</div>
            @endif
            <div id="product{{$detail['id']}}" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
              <div class="carousel-indicators">
                @foreach($detail['image'] as $index => $value)
                <button type="button" data-bs-target="#product{{$detail['id']}}" data-bs-slide-to="{{$index}}" aria-label="Slide {{$index}}" class="{{$index=='0' ? 'active':''}}" style="background-color:{{$value['color']}}"></button>
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
    </div>
	@endif
</div>
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

        var dataSizeOption = '<option value="">-- Choose size --</option>';
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
            toastr.warning('Please choose size.');
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
