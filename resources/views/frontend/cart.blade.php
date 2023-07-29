@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/shop.css')}}" rel="stylesheet">
@include('frontend.login_style')
<style>
body {
		background: #f7faff;
	}
    .table-cart td{
        padding: 10px;
    }
    .btn-check-out {
        width: 100% !important;
    }
    .input-group-btn {
        background:#FFF!important;
        border: 1px solid #ced4da;
    }
    .input-group-btn button {
        margin-top: 4px;
    }
    .input-group-btn button span {
        color:#000 !important;
        padding-left:10px;
        padding-right:10px;
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
<div class="container" id="container-cart">
    @if(sizeof($data_cart) == 0)
    @php
        $stockArray = array();
    @endphp
    <div class="row">
        <div class="col-lg-12 text-center empty-cart">
            <p>@lang('cart.empty').</p>
            <a href="{{url('/shop')}}"><button class="btn btn-primary"><b>@lang('cart.back')</b></button></a>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="table-responsive plc-container">
                <br/>
                <p>@lang('cart.your_product') ( <b>{{sizeof($data_cart)}} {{sizeof($data_cart) == 1 ? 'item':'items'}} </b>)</p>
                <br>
                <table class="table table-cart cart table-striped table-hover">
                    <tbody>
                        <tr class="table-head-cart">
                            <td></td>
                            <td width='12%'></td>
                            <td><b>@lang('cart.product')</b></td>
                            <td><center><b>@lang('cart.price')</b></center></td>
                            <td width="250px" class="text-center"><b>@lang('cart.qty')</b></td>
                            <td><center><b>@lang('cart.subtotal')</b></center></td>
                        </tr>
                        @php
                            $countSubTotal = 0; 
                            $countProductWeight = 0;
                            $no = 1;
                            $stockArray = array();
                        @endphp
                        @foreach($data_cart as $carts)
                        @php
                            //discount
                            $setDiscount = \App\Helper\Common_helper::set_discount($carts['price'], $carts['discount']);
                            $priceAfterDisc = $setDiscount[0];
                            $discount = $setDiscount[1];
                            //--------

                            $priceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($priceAfterDisc);

                            $subTotal = $priceAfterDisc * $carts['qty'];
                            $subTotalInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($subTotal);
                            $countSubTotal += $subTotalInCurrencyFormat[0];

                            $productWeight = ($carts['weight'] * $carts['qty']);
                            $countProductWeight += $productWeight;

                            array_push($stockArray, array($no => $carts['stock']));

                            $disableMin = '';
                            if($carts['qty'] == '1')
                            {
                                $disableMin = ' disabled="disabled" ';
                            }

                            //$showPrice = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];
                            $showPrice = $current_currency[1].$priceInCurrencyFormat[1];
                            //$showSubTotal = $current_currency[1].$subTotalInCurrencyFormat[1].' '.$current_currency[2];
                            $showSubTotal = $current_currency[1].$subTotalInCurrencyFormat[1];
                        @endphp
                        <tr class="row-{{$no}}">
                            <td class="remove-cart" align="center">
                                <a class="delete-item-cart" data-index="{{$no}}" href="{{route('process_delete_item_cart').'/'.$carts['product_id'].'/'.$carts['sku_id']}}"><i class="pe-7s-close"></i></a>
                            </td>
                            <td>
                                <center>
                                    <img src="{{asset(env('URL_IMAGE').'product/thumb_sm/'.$carts['img'])}}" alt={{$alt_image}} class="img-fluid">
                                </center>
                            </td>
                            <td>
                                <p class="mobile-table-title">@lang('cart.product')</p>
                                <p class="cart-td-title">
                                    <a href="{{route('shop_detail_page').'/'.str_replace(' ', '-', $carts['product_name']).'-'.$carts['product_id']}}">
                                        {{$carts['product_name']}}
                                    </a>
                                </p>
                                <div class="cart-product-code"><i>@lang('cart.code') </i>: {{$carts['product_code']}}</div>
                                @if(isset($carts['size']) && $carts['size'] != '')
                                <div class="cart-size"><b>@lang('cart.size')</b> : {{$carts['size']}}</div>
                                @endif
                                @if(isset($carts['color_hexa']) && $carts['color_hexa'] != '')
                                    <div class="cart-color"> 
                                        <div class="cart-color-display" style="background: {{$carts['color_hexa']}}"></div>
                                        <span>: {{$carts['color_name']}}</span> 
                                    </div>
                                @endif
                            </td>
                            <td>
                                <p class="mobile-table-title">@lang('cart.price')</p>
                                <center><p class="cart-td-title">{{$showPrice}}</p></center>
                            </td>
                            <td>
                                <p class="mobile-table-title">@lang('cart.qty')</p>
                                    <div class="input-group select-qty">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn-number" 
                                            data-index="{{$no}}" 
                                            data-price="{{$priceInCurrencyFormat[0]}}" 
                                            data-weight="{{$carts['weight']}}" 
                                            {{$disableMin}} data-type="minus" data-field="quant{{$no}}[1]">
                                                <span class="pe-7s-less"></span>
                                            </button>
                                        </span>
                                        <input type="text" 
                                            data-sku-id="{{$carts['sku_id']}}" 
                                            data-index="{{$no}}" 
                                            data-price="{{$priceInCurrencyFormat[0]}}" 
                                            data-weight="{{$carts['weight']}}" 
                                            name="quant{{$no}}[1]" class="form-control input-number text-center" value="{{$carts['qty']}}" min="1" max="100">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn-number" 
                                            data-index="{{$no}}" 
                                            data-price="{{$priceInCurrencyFormat[0]}}" 
                                            data-weight="{{$carts['weight']}}" 
                                            data-type="plus" data-field="quant{{$no}}[1]">
                                                <span class="pe-7s-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                            </td>
                            <td>
                                <p class="mobile-table-title">@lang('cart.subtotal')</p>
                                <center>
                                    <p class="cart-td-title show-sub-total show-sub-total-{{$no}}" data-weight-total="{{$productWeight}}" data-sub-total="{{$subTotalInCurrencyFormat[0]}}">{{$showSubTotal}}</p>
                                </center>
                            </td>
                        </tr>
                        @if($carts['notif'] != '')
                            <tr>
                                <td colspan="6" class="notif-cart">{{$carts['notif']}}</td>
                            </tr>
                        @endif
                        <?php $no++;?>
                        @endforeach
                    </tbody>
                </table>
                <br/>
            </div>
        </div>
        <input type="hidden" name="symbol_global" id="symbol_global" value="{{$current_currency[1]}}">
        <input type="hidden" name="code_global" id="code_global" value="{{$current_currency[2]}}">
        <input type="hidden" name="actionLocation" id="actionLocation" value="{{route('process_shipping_location')}}">
        <input type="hidden" name="actionCheckout" id="actionCheckout" value="{{route('process_checkout')}}">
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <div class="col-lg-8 col-md-7"></div>
                <div class="col-lg-4 col-md-5">
                    <div class="summary">
                        @php
                        $countSubTotalInCurrencyFormat = \App\Helper\Common_helper::set_two_0_after_point(\App\Helper\Common_helper::convert_to_format_currency($countSubTotal));
                        //$showCountSubTotal = $current_currency[1].$countSubTotalInCurrencyFormat.' '.$current_currency[2];
                        $showCountSubTotal = $current_currency[1].$countSubTotalInCurrencyFormat;

                        $country_id = '';
                        $country_name = '';
                        $province_id = '';
                        $province_name = '';
                        $city_id = '';
                        $city_name = '';
                        $subdistrict_id = '';
                        $subdistrict_name = '';
                        $address = '';
                        $postalcode = '';

                        $type_shipping = 'national';
                        $showShippingCost = 'ADD INFO';
                        $shippingCostRaw = '';
                        $shippingCostTotal = '0';

                        $showNational = '';
                        $showInternational = ' none ';
                        if(sizeof($shipping_address) > 0)
                        {
                            foreach ($shipping_address as $key) 
                            {
                                if($key->country_id != '236')
                                {
                                    $showNational = ' none ';
                                    $showInternational = '';
                                }

                                $country_id = $key->country_id;
                                $country_name = $key->country_name;
                                $province_id = $key->province_id;
                                $province_name = $key->province_name;
                                $city_id = $key->city_id;
                                $city_name = $key->city_name;
                                $subdistrict_id = $key->subdistrict_id;
                                $subdistrict_name = $key->subdistrict_name;
                                $address = $key->detail_address;
                                $postalcode = $key->postal_code;
                            }
                        }

                        if(Session::get('delivery') != null)
                        {
                            $getShippingData = Session::get('delivery');
                            if(sizeof($getShippingData) > 0)
                            {
                                $country_id = $getShippingData[0]['country'];
                                $country_name = $getShippingData[0]['country_name'];
                                if($getShippingData[0]['national'])
                                {
                                    $province_id = $getShippingData[0]['province'];
                                    $province_name = $getShippingData[0]['province_name'];
                                    $city_id = $getShippingData[0]['city'];
                                    $city_name = $getShippingData[0]['city_name'];
                                    $subdistrict_id = $getShippingData[0]['subdistrict'];
                                    $subdistrict_name = $getShippingData[0]['subdistrict_name'];

                                    $showNational = '';
                                    $showInternational = ' none ';
                                }
                                else
                                {
                                    $country_id = $getShippingData[0]['country'];
                                    $country_name = $getShippingData[0]['country_name'];
                                    $address = $getShippingData[0]['address'];

                                    $showNational = ' none ';
                                    $showInternational = '';

                                    $type_shipping = 'international';
                                }

                                $postalcode = $getShippingData[0]['postalcode'];

                                if(sizeof($getShippingData) == 2)
                                {
                                    $shippingCostRaw = $getShippingData[1];

                                    $tmpData = explode('_', $shippingCostRaw);

                                    $shippingCostInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($tmpData[2]);
                                    $showShippingCost = $current_currency[1].$shippingCostInCurrencyFormat[1].' '.$current_currency[2];

                                    $shippingCostTotal = $shippingCostInCurrencyFormat[0];
                                }
                            }
                        }
                        @endphp
                        <h5 class="cart-totals">Cart Totals</h5>
                        <table class="table cart-total-table">
                            <tbody>
                                    <tr>
                                        <td>@lang('cart.subtotal')</td>
                                        <td class="btm-plc-total total-cart" data-count-sub-total="{{$countSubTotal}}"><b><span>{{$showCountSubTotal}}</span></b></td>
                                    </tr>
                                    <tr style="display:none">
                                        <td>@lang('cart.shipping')</td>
                                        <td class="btm-plc-shipping total-cart" data-shipping-raw="{{$shippingCostRaw}}" data-shipping-total="{{$shippingCostTotal}}"><b><span><a class="action-shipping-info pointer">{{$showShippingCost}}</a></span></b></td>
                                    </tr>
                                    @php
                                        $taxTotal = ($tax * $countSubTotal) / 100;
                                        $taxInCurrencyFormat = \App\Helper\Common_helper::set_two_0_after_point(\App\Helper\Common_helper::convert_to_format_currency($taxTotal));
                                        //$showTax = $current_currency[1].$taxInCurrencyFormat.' '.$current_currency[2];
                                        $showTax = $current_currency[1].$taxInCurrencyFormat;

                                        $granTotal = $taxTotal + $countSubTotal + $shippingCostTotal;
                                        $grandTotalInCurrencyFormat = \App\Helper\Common_helper::set_two_0_after_point(\App\Helper\Common_helper::convert_to_format_currency($granTotal));
                                        $showGrandTotal = $current_currency[1].$grandTotalInCurrencyFormat.' '.$current_currency[2];
                                    @endphp
                                    <tr>
                                        <td>@lang('cart.tax')</td>
                                        <td class="btm-plc-tax total-cart" data-tax="{{$tax}}" data-tax-total="{{$taxTotal}}"><b><span>{{$showTax}}</span></b></td>
                                    </tr>
                                    <tr>
                                        <td>@lang('cart.grand_total')</td>
                                        <td class="btm-plc-grand-total total-cart" data-grand-total="{{$granTotal}}"><b><span>{{$showGrandTotal}}</span></b></td>
                                    </tr>
                            </tbody>
                        </table>
                        @if(Session::get(env('SES_FRONTEND_ID')) == null)
                        <!-- <button class="btn btn-primary btn-show-login"><b>@lang('cart.checkout')</b></button> -->
                        <button class="btn btn-check-out mt-2"><b>@lang('cart.checkout')</b></button>
                        @else
                        <button class="btn btn-check-out mt-2"><b>@lang('cart.continue_payment')</b></button>
                        @endif
                        <br><br>
                        <center><a href="{{url('/shop')}}">@lang('cart.continue_shopping') <i class="pe-7s-right-arrow"></i></a></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<div class="container" id="container-login">
    <div class="row">
        <div class="col-sm-12" align="right">
            <button type="button" class="close btn container-login-close-btn">&times;</button>
        </div>
    </div>
    @include('frontend.login_form')
    @include('frontend.register_form')
</div>
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
@include('frontend.login_script')
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        var json_stock = <?php echo json_encode($stockArray)?>;

        $("#form-estimate-shipping").validate({
            submitHandler: function(form) {
                $(".loader-estimate").removeClass('hidden');
                $("#btn-estimate").attr('disabled', 'disabled');
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#btn-estimate").removeAttr('disabled');
                        if(response.trigger == "yes")
                        {
                            $('.result-shipping-cost').html(response.notif);
                            $('.notif-update-estimate').fadeOut();
                        }
                        else
                        {
                        	$('.result-shipping-cost').html(response.notif);
                            toastr.warning(response.notif);
                        }
                        $(".loader-estimate").addClass('hidden');
                    },
                    error: function()
                    {
                        $("#btn-estimate").removeAttr('disabled');
                        $(".loader-estimate").addClass('hidden');
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
        });

        function encodeRp(bilangan)
        {
            var tmpData = bilangan.toString().split('.');

            if(tmpData.length == 1){
                return bilangan+".00";
            }else if(tmpData.length == 2){
                if(tmpData[1].length == 1){
                    return bilangan+"0";
                }
            }
            return bilangan;


            var  number_string = bilangan.toString();
            var last_string = '';
            if(tmpData.length == 2)
            {
                number_string = tmpData[0];
                last_string = '.'+tmpData[1].substr(0, 2);
            }

            sisa     = number_string.length % 3,
            rupiah   = number_string.substr(0, sisa),
            ribuan   = number_string.substr(sisa).match(/\d{3}/g);
            
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return rupiah+last_string;

            // if(bilangan !== undefined){
                // var tmpData = bilangan.toString().split('.');

                // var	number_string = bilangan.toString();
                // var last_string = '';
                // if(tmpData.length == 2)
                // {
                //     number_string = tmpData[0];
                //     last_string = ','+tmpData[1].substr(0, 2);
                // }

                // sisa 	= number_string.length % 3,
                // rupiah 	= number_string.substr(0, sisa),
                // ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                
                // if (ribuan) {
                //     separator = sisa ? '.' : '';
                //     rupiah += separator + ribuan.join('.');
                // }

                // return rupiah+last_string;
            // }
        }

        function calculationTotal()
        {
            var code = $('#code_global').val();
            var symbol = $('#symbol_global').val();

            var totalPrice = 0;
            var totalWeight = 0;
            $('table tbody tr').each(function(index){
                if($(this).find('.show-sub-total').attr('data-sub-total') != undefined)
                {
                    totalPrice += parseFloat($(this).find('.show-sub-total').attr('data-sub-total'));
                    totalWeight += parseFloat($(this).find('.show-sub-total').attr('data-weight-total'));
                }
            });

            var add2lastNominal = '';
            if(code == 'IDR')
            {
                add2lastNominal = ',00';
            }


            $('.btm-plc-total').attr('data-sub-total', totalPrice);
            // $('.btm-plc-total').find('span').text(symbol+encodeRp(totalPrice)+add2lastNominal+' '+code);
            $('.btm-plc-total').find('span').text(symbol+encodeRp(totalPrice)+add2lastNominal);

            $('#weight_total').val(totalWeight);

            var shippingCost = $('.btm-plc-shipping').attr('data-shipping-total');
            if(shippingCost != '')
            {
                $('.btm-plc-shipping').find('span a').text(symbol+encodeRp(shippingCost)+' '+code)
            }
            else
            {
                shippingCost = symbol+'0'+add2lastNominal+' '+code;
            }
            
            var tax = (parseFloat(totalPrice) * parseFloat($('.btm-plc-tax').attr('data-tax')) /100);
            $('.btm-plc-tax').attr('data-tax-total', tax);
            // $('.btm-plc-tax').find('span').text(symbol+encodeRp(tax)+add2lastNominal+' '+code);
            $('.btm-plc-tax').find('span').text(symbol+encodeRp(tax)+add2lastNominal);

            var granTotal = parseFloat(totalPrice) + parseFloat(shippingCost) + parseFloat(tax);
            $('.btm-plc-grand-total').attr('data-grand-total', granTotal);
            $('.btm-plc-grand-total').find('span').text(symbol+encodeRp(granTotal)+add2lastNominal+' '+code);
        }

        function check_change_qty(index, qty, elemetHtml)
        {
            var code = $('#code_global').val();
            var add2lastNominal = '';
            if(code == 'IDR')
            {
                add2lastNominal = ',00';
            }

            var qty = parseInt(qty);
            var index = parseInt(index);
            var stock = json_stock[(index - 1)][index];

            var price = parseFloat(elemetHtml.attr('data-price'));
            var newTotalPrice = price * parseFloat(qty);

            var weight = parseFloat(elemetHtml.attr('data-weight'));
            var newWeight = (weight * parseFloat(qty));

            var code = $('#code_global').val();
            var symbol = $('#symbol_global').val();


            $('.show-sub-total-'+index).attr('data-sub-total', newTotalPrice);
            // $('.show-sub-total-'+index).text(symbol+encodeRp(newTotalPrice)+' '+code);
            $('.show-sub-total-'+index).text(symbol+encodeRp(newTotalPrice)+add2lastNominal);

            $('.show-sub-total-'+index).attr('data-weight-total', newWeight);

            if(qty > stock)
            {
                if($('tr.notif_row-'+index).length == 0)
                {
                    $('tr.row-'+index).after('<tr class="notif_row-'+index+' none"><td colspan="6" class="notif-cart">Only '+stock+' product stock(s) are available.</td></tr>');

                    $('tr.notif_row-'+index).fadeIn();
                }
            }
            else
            {
                $('tr.notif_row-'+index).fadeOut(function(){
                    $('tr.notif_row-'+index).remove();
                })
            }
            calculationTotal();
        }

        function showShippingByChanges()
        {
            if(($('.btm-plc-shipping').find('span a').text() != 'CANCEL') && ($('.btm-plc-shipping').find('span a').text() != 'ADD INFO'))
            {
                if($('.info-shipping').hasClass('none'))
                {
                    $('.action-shipping-info').click();
                }
                $('.notif-update-estimate').fadeIn();
            }
        }

        $('.delete-item-cart').click(function(e){
   			e.preventDefault();
   			var urlAction = $(this).attr('href');
   			var index = $(this).attr('data-index');
	        $.ajax({
	            url: urlAction,
	            dataType: 'json',
	            type: 'GET',
	            success: function(response, textStatus, XMLHttpRequest)
	            {
	                if(response.trigger=="yes")
                	{
	                    toastr.success(response.notif);
	                    $('tr.row-'+index).remove();
	                    $('tr.notif_row-'+index).remove();

	                    var countCart = parseInt($('.count-fill-cart').text());
	                    $('.count-fill-cart').text((countCart - 1));
                        if(countCart == 1){
                            $('.count-fill-cart').addClass('none');
                            // right side cart
                            $('.right-side-cart-empty').removeClass('none');
                            $('.right-side-cart').addClass('none');
                            $('#right-side-cart').html(response.right_side_cart);
                        }

	                    calculationTotal();
	                }
	                else
	                {
	                    toastr.warning(response.notif);
	                }
	            },
	            error: function(XMLHttpRequest, textStatus, errorThrown)
	            {
	                toastr.error('There is something wrong, please refresh page and try again.');
	            }
	        });
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

		    check_change_qty($(this).attr('data-index'), input.val(), $(this));
		});
		$('.input-number').focusin(function(){
		   $(this).data('oldValue', $(this).val());
		   check_change_qty($(this).attr('data-index'), $(this).val(), $(this));
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
		    showShippingByChanges();
		    check_change_qty($(this).attr('data-index'), $(this).val(), $(this));
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

	        check_change_qty($(this).attr('data-index'), $(this).val(), $(this));
	    });
	    $(".input-number").keyup(function (e) {
	    	e.preventDefault();
	    	check_change_qty($(this).attr('data-index'), $(this).val(), $(this));
	    })

        $(document).on('click', '#update-shipping-cost', function(e){
        	var shippingCost = $('input[name=shipping_choose]:checked').val();
        	if(shippingCost != undefined && shippingCost != '')
        	{
        		var tmpData = shippingCost.split('_');
        		$('.btm-plc-shipping').attr('data-shipping-total', tmpData[1]);
        		$('.btm-plc-shipping').attr('data-shipping-raw', shippingCost);
        		$('.result-shipping-cost').html('');
        		$('.info-shipping').fadeOut(function(){
        			$('.info-shipping').addClass('none');
        		});
        		calculationTotal();
        	}
        	else
        	{
        		toastr.warning('Please choose shipping cost.');
        	}
        });

        $('.action-shipping-info').click(function(e){
	    	if($('.info-shipping').hasClass('none'))
	    	{
	    		$('.info-shipping').fadeIn(function(){
	    			if($('.btm-plc-shipping').find('span a').text() == 'ADD INFO')
	    			{
	    				if($('#country').val() == '')
	    				{
			    			$('#country').select2('val', '');
			    			$('#province').select2('val', '');
			    			$('#city').select2('val', '');
			    			$('#subdistrict').select2('val', '');
		    			}
		    			$('.btm-plc-shipping').attr('data-shipping-total', '');
		    			$('.btm-plc-shipping').find('span a').text('CANCEL');
	    			}
	    			$('#country').select2();
	    			$('#province').select2();
	    			$('#city').select2();
	    			$('#subdistrict').select2();
	    		});

	    		$('.info-shipping').removeClass('none');
	    	}
	    	else
	    	{
	    		if($('.btm-plc-shipping').find('span a').text() == 'CANCEL')
	    		{
	    			$(this).text('ADD INFO');
    			}
	    		$('.info-shipping').fadeOut(function(){
	    			$('.info-shipping').addClass('none');
	    		});
	    	}
	    });

        $('#country, #province, #city').change(function(e){
	    	var data_id = $(this).val();
	    	var trigger = $(this).attr('id');

	    	if($('#country').val() == '236')
	    	{
	    		$('.international').fadeOut(function(){
	    			$('.national').fadeIn();
	    			if(trigger == 'country')
	    			{
		    			$('#province').select2();
		    			$('#city').select2();
		    			$('#subdistrict').select2();
	    			}
	    		});

	    		$('#type_shipping').val('national');
	    	}
	    	else
	    	{
	    		if($('#country').val() != '')
	    		{
		    		$('.national').fadeOut(function(){
		    			$('.international').fadeIn();
		    		});
		    		$('#type_shipping').val('international');
	    		}
	    	}

	    	if($('#country').val() == '236')
	    	{
	    		var urlAction = $('#actionLocation').val();
		        $.ajax({
		            url: urlAction,
		            dataType: 'json',
		            type: 'POST',
		            data: {
		            	'data_id': data_id, 
		            	'trigger': trigger,
		            	'_token': $('input[name=_token]').val()
		            },
		            success: function(response, textStatus, XMLHttpRequest)
		            {
		                if(response.trigger=="yes")
	                	{
	                		if(trigger == 'country')
	                		{
	                			$('#province').html(response.notif);
	                		}
	                		else if(trigger == 'province')
	                		{
	                			$('#city').html(response.notif);
	                		}
	                		else
	                		{
	                			$('#subdistrict').html(response.notif);
	                		}
		                }
		                else
		                {
		                     toastr.warning(response.notif)
		                }
		            },
		            error: function(XMLHttpRequest, textStatus, errorThrown)
		            {
		            	toastr.remove();
		                toastr.error('There is something wrong, please refresh page and try again.');
		            }
		        });
	    	}
	    });

        $('.btn-check-out').click(function(e){
	    	var dataQty = [];
	    	$('table.table-cart tbody tr').each(function(index){
	    		if($(this).find('input').val() != undefined)
	    		{
					dataQty.push($(this).find('input').val())
				}
			});

	    	if(dataQty.length > 0)
	    	{
	    		var timezone_offset_minutes = new Date().getTimezoneOffset();
				timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;

	    		$(".loader-check-out").removeClass('hidden');
		    	var urlAction = $(this).attr('href');
	   			var index = $(this).attr('data-index');
		        $.ajax({
		            url: $('#actionCheckout').val(),
		            dataType: 'json',
		            type: 'POST',
		            data: {
		            	'data_qty': dataQty, 
		            	'data_shipping_cost' : $('.btm-plc-shipping').attr('data-shipping-raw'),
		            	'timezone_offset_minutes': timezone_offset_minutes,
		            	'_token': $('input[name=_token]').val()
		            },
		            success: function(response, textStatus, XMLHttpRequest)
		            {
		            	$(".loader-check-out").addClass('hidden');
		                if(response.trigger=="yes")
	                	{
		                    location.href = response.notif;
		                }
		                else
		                {
		                	toastr.remove();
		                    toastr.warning(response.notif, 'Check stock(s) :', {timeOut: 50000})
		                }
		            },
		            error: function(XMLHttpRequest, textStatus, errorThrown)
		            {
		            	$(".loader-check-out").addClass('hidden');
		            	toastr.remove();
		                toastr.error('There is something wrong, please refresh page and try again.');
		            }
		        });
	        }
	        else
	        {
	        	toastr.error('There is something wrong, please refresh page and try again.');
	        }
	    });

        $('.btn-show-login').click(function(e){
            $('#container-cart').fadeOut(function(){
                $('#container-login').fadeIn(); 
            });
        });

        $('.container-login-close-btn').click(function(e){
            $('#container-login').fadeOut(function(){
                $('#container-cart').fadeIn();
            });
        });
    });
</script>
@stop
