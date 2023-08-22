@extends('frontend.layout.template')

@section('style')
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'select2/select2.min.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/shop.css')}}" rel="stylesheet">
<style>
body {
		background: #f7faff;
	}
.checkout .alert-info.for-shipping {
    margin-top: 20px;
    margin-bottom: 20px;
    z-index: 0;
}
.select2-container{
    width: -moz-available !important;          /* WebKit-based browsers will ignore this. */
    width: -webkit-fill-available !important;  /* Mozilla-based browsers will ignore this. */
    width: fill-available !important;
}
#showBtnStripeFree {
    display: none;
}
.sign-in{
    text-decoration: underline;
}
.plc-shipping-form .form-group{
    margin-bottom: 50px !important;
}
.form-control:focus {
    box-shadow: none;
    outline: none;
}
#three-ds-container {
		width: 100%;
		height: 100%;
		line-height: 200px;
		background-color: #ffffff;
		text-align: center;
	}
	#display-payment-processing p {
		color: #212529;
		font-size: 24px;
    	font-style: italic;
		margin-bottom: 0;
		margin-top:0px;
	}
	#staticBackdropLoading .modal-body {
		padding-top:20%;
	}
	#staticBackdropLoading .modal-body i {
		color: #279384;
	}
.separator{
  display:flex;
  align-items: center;
  margin-bottom: 20px;
}
.separator .line{
  height: 1px;
  flex: 1;
  background-color: #dedede;
}
.separator p{
    padding: 0px 15px;
    margin-top: 15px;
}
</style>
@include('frontend.login_style')
@stop

@section('content')
<div id="container-checkout" class="container checkout">
    @if($payment_failed != '')
        <div class="alert alert-danger col-sm-12">
            <?=$payment_failed?>
        </div>
    @endif
    @if(sizeof($data_cart) == 0)
        <div class="alert alert-info col-sm-12 text-center mrg-btm20">
            @if($need_login == '1')
                Please login to view your transaction.
            @else
                Your order not found.
            @endif
        </div>
    @else
    <h3>Payment Details</h3>
    <br/>
    <div class="row">
        <?php
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

            $first_name = '';
            $last_name = '';
            $email_address = '';
            $phone_prefix_input = '';
            $phone_number = '';
            $national = '';

            if(sizeof($shipping_data) > 0){
                foreach ($shipping_data as $key => $value) {
                    $country_id = $value->country_id;
                    $country_name = $value->country_name;
                    if($value->country_id == '236')
                    {
                        $national = '';
                        $province_id = $value->province_id;;
                        $province_name = $value->province_name;
                        $city_id = $value->city_id;
                        $city_name = $value->city_name;
                        $subdistrict_id = $value->subdistrict_id;
                        $subdistrict_name = $value->subdistrict_name;
                        $address = $value->detail_address;
                        $postalcode = $value->postal_code;
                    }
                    else
                    {	
                        $national = ' none ';
                    }
                }
            }

            $showCustomer = '';
            if(Session::get(sha1(env('AUTHOR_SITE').'_checkout_customer')) != ''){
                $showCustomer = 'show';
            }

            $showShipping = '';
            if(Session::get(sha1(env('AUTHOR_SITE').'_checkout_shipping')) != ''){
                $showShipping = 'show';

                $tmpData = Session::get(sha1(env('AUTHOR_SITE').'_checkout_shipping'));
                $country_id = $tmpData['country_id'];
                if($country_id == '236'){
                    $national = '';
                }else{
                    $national = ' none ';
                }
                $country_name = $tmpData['country_name'];
                $province_id = $tmpData['province_id'];
                $province_name = $tmpData['province_name'];
                $city_name = $tmpData['city_name'];
                $address = $tmpData['address'];

                if(Session::get(env('SES_FRONTEND_ID')) == null){
                    $first_name = $tmpData['first_name'];
                    $last_name = $tmpData['last_name'];
                    $phone_prefix_input = $tmpData['phone_prefix'];
                    $phone_number = $tmpData['phone_number'];
                }
            }

            $getTimerTrans = array();
            if(isset($timezone['timezone']->meta_description)){
                $getTimerTrans = \App\Helper\Common_helper::timerCheckout($header_transaction->transaction_date, $timezone['timezone']->meta_description);
                // echo '
                //     <div id="countdowntimer" class="text-center mb-3">
                //         <p>Please make payment before the time runs out!</p>
                //         <span id="future_date" class="alert alert-info"></span>
                //     </div>
                // ';
            }
        ?>

        @php $is_customer_form_complete = "0";@endphp
        @if(Session::get(env('SES_FRONTEND_ID')) != null)
            @php $is_customer_form_complete = "1";@endphp
        @else
            @if(in_array('customer', $check_info))
                @php $is_customer_form_complete = "1";@endphp
            @endif    
        @endif

        <div class="col-lg-7 col-md-6 checkout-information customer">
            <div class="customer-info plc-container {{$is_customer_form_complete?'':'none'}} mb-5">
                <h5 class="mb-4">Customer</h5>

                @if(Session::get(env('SES_FRONTEND_ID')) == null)
                    <a class="pull-right cursor edit-customer" href="javascript:void(0);">Edit</a>
                @endif
                <div class="after-submit">
                    @if(Session::get(sha1(env('AUTHOR_SITE').'_checkout_customer')) != null)
                        @foreach(Session::get(sha1(env('AUTHOR_SITE').'_checkout_customer')) as $key => $value)
                            {{$value}}<br/>
                        @endforeach
                    @endif
                </div>
            </div>
                
            <div class="customer-form plc-container {{$is_customer_form_complete?'none':''}} mb-5">                
                <h5 class="mb-4">Customer</h5>
                <p>
                    Checking out as a Guest? <br/>
                    You'll be able to save your details to create an account with us later.
                </p>
                @php $emailGuest = '';@endphp
                @if(Session::get(sha1(env('AUTHOR_SITE').'_checkout_customer')) != '')
                    @foreach(Session::get(sha1(env('AUTHOR_SITE').'_checkout_customer')) as $key => $value)
                        @php $emailGuest = $value;@endphp
                    @endforeach
                @endif
                <div class="row">
                    <form id="form-guest" action="{{route('process_checkout_guest')}}" method="post">
                        {{ csrf_field() }}
                        <div class="col-sm-12">
                            <label for="email">Email address</label>
                        </div>
                        <input type="hidden" name="trans_id" id="trans_id" value="{{$header_transaction->transaction_id}}">
                        <div class="col-sm-12 col-xs-12 mb-3">
                            <input type="email" class="form-control" id="emailGuest" name="emailGuest" value="{{$emailGuest}}">
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <button type="submit" class="btn btn-primary btn-main-2" id="btn-guest">CONTINUE AS GUEST</button>
                        </div>
                    </form> 
                </div>
                <br>
                <p>Already have an account? <a class="sign-in" href="javascript:void(0);" data-toggle="modal">Sign in now</a></p>
            </div>

            <div class="shipping-fill-info plc-container mb-5">
                <h5 class="mb-4">Shipping Information</h5>

                <form id="form-shipping" class="" action="{{route('process_checkout_shipping')}}" method="post">
                    <input type="hidden" name="trans_id" id="trans_id" value="{{$header_transaction->transaction_id}}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 plc-shipping-form">
                                <div class="row">
                                    @if(Session::get(env('SES_FRONTEND_ID')) == null)
                                        <div class="col-sm-6 no-pdg-lt">
                                            <label for="first_name">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{$first_name}}">
                                            <small class="notif-first_name error none"><i>Please input first name!</i></small>
                                        </div>
                                        <div class="col-sm-6 no-pdg-rg">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{$last_name}}">
                                            <small class="notif-last_name error none"><i>Please input last name!</i></small>
                                        </div>
                                        <div class="col-sm-12 no-pdg-lt no-pdg-rg mt-4 mb-4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="phone_prefix">Phone Number</label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <select style="width: 100%;" class="select2 form-control pull-left" name="phone_prefix" id="phone_prefix">
                                                        <option value="">Prefix</option>
                                                        @foreach($phone_prefix_data as $phone)
                                                            @if($phone_prefix_input == $phone->phone_prefix)
                                                            <option value="{{$phone->phone_prefix}}" selected>{{$phone->name}} ({{$phone->phone_prefix}})</option>
                                                            @else
                                                            <option value="{{$phone->phone_prefix}}">{{$phone->name}} ({{$phone->phone_prefix}})</option>
                                                            @endif
                                                        @endforeach
                                                    </select> 
                                                    <small class="notif-phone_prefix error none"><i>Select prefix!</i></small>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="phone_number" id="phone_number" value="{{$phone_number}}">
                                                    <small class="notif-phone_number error none"><i>Please input your number!</i></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 no-pdg">
                                            <hr>
                                        </div>
                                    @endif
                                    <div class="col-sm-6 no-pdg-lt">
                                        <div class="form-group">
                                            <label for="country">Country</label>
                                            <select class="form-control select2" id="country" style="width: 100%;" name="country">
                                                <option value="">Choose Country</option>
                                                    @foreach($country_data  as $countries)
                                                        @if($country_id == $countries->country_id)
                                                            <option value="{{$countries->country_id}}" selected>{{$countries->country_name}}</option>
                                                        @else
                                                            <option value="{{$countries->country_id}}">{{$countries->country_name}}</option>
                                                        @endif
                                                    @endforeach
                                            </select>
                                            <small class="notif-country error none"><i>Please choose country!</i></small>
                                        </div>
                                        <div class="form-group select-national {{$national}}">
                                            <label for="province">Province</label>
                                            <select class="form-control select2" style="width: 100%;" name="province" id="province">
                                                <option value="">Choose Province</option>
                                                <?php
                                                    if($province_id != '')
                                                    {
                                                        echo '<option value="'.$province_id.'" selected>'.$province_name.'</option>';
                                                    }
                                                    // foreach($province_data  as $countries)
                                                    // {
                                                    // 	if($province_id == $countries->province_id)
                                                    // 	{
                                                    // 		echo '<option value="'.$countries->province_id.'" selected>'.$countries->province_name.'</option>';
                                                    // 	}
                                                    // 	else
                                                    // 	{
                                                    // 		echo '<option value="'.$countries->province_id.'">'.$countries->province_name.'</option>';
                                                    // 	}
                                                    // }
                                                ?>
                                            </select>
                                            <small class="notif-province error none"><i>Please choose province!</i></small>
                                        </div>
                                        <div class="form-group select-national {{$national}}">
                                            <label for="city">City</label>
                                            <select class="form-control select2" name="city" style="width: 100%;" id="city">
                                                <option value="">Choose City</option>
                                                <?php
                                                    if($city_id != '')
                                                    {
                                                        echo '<option value="'.$city_id.'" selected>'.$city_name.'</option>';
                                                    }
                                                    // foreach($city_data  as $countries)
                                                    // {
                                                    // 	if($city_id == $countries->city_id)
                                                    // 	{
                                                    // 		echo '<option value="'.$countries->city_id.'" selected>'.$countries->city_name.'</option>';
                                                    // 	}
                                                    // 	else
                                                    // 	{
                                                    // 		echo '<option value="'.$countries->city_id.'">'.$countries->city_name.'</option>';
                                                    // 	}
                                                    // }
                                                ?>
                                            </select>
                                            <small class="notif-city error none"><i>Please choose city!</i></small>
                                        </div>
                                        <div class="form-group select-national {{$national}}">
                                            <label for="subdistrict">Subdistrict</label>
                                            <select class="form-control select2" name="subdistrict" style="width: 100%;" id="subdistrict">
                                                <option value="">Choose Subdistrict</option>
                                                <?php
                                                    if($subdistrict_id != '')
                                                    {
                                                        echo '<option value="'.$subdistrict_id.'" selected>'.$subdistrict_name.'</option>';
                                                    }
                                                    // foreach($subdistrict_data  as $countries)
                                                    // {
                                                    // 	if($subdistrict_id == $countries->subdistrict_id)
                                                    // 	{
                                                    // 		echo '<option value="'.$countries->city_id.'" selected>'.$countries->subdistrict_name.'</option>';
                                                    // 	}
                                                    // 	else
                                                    // 	{
                                                    // 		echo '<option value="'.$countries->city_id.'">'.$countries->subdistrict_name.'</option>';
                                                    // 	}
                                                    // }
                                                ?>
                                            </select>
                                            <small class="notif-subdistrict error none"><i>Please choose subdistrict!</i></small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 no-pdg-rg">
                                        <div class="mb-2">
                                            <label for="address">Address</label>
                                            <textarea class="form-control no-radius" rows="4" name="address" id="address">{{$address}}</textarea>
                                            <small class="notif-address error none"><i>Please input address!</i></small>
                                        </div>
                                        <div class="mb-2">
                                            <label for="postalcode">Postal Code</label>
                                            <input type="text" class="form-control" id="postalcode" name="postalcode" value="{{$postalcode}}" />
                                            <small class="notif-postalcode error none"><i>Please input postal code!</i></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="shipping-info col-12">
                                    
                                </div>
                            </div>

                            <div>
                                <div class="col-sm-12 no-pdg">
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="result-shipping-cost">
                                            <?=$shipping_list?>
                                        </table>
                                        <div class="notif-shipping">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 no-pdg text-right mt-3 plc-lanjut-bayar">
                                    <button type="button" class="btn btn-primary" id="btn-shipping">CONTINUE PAYMENT</button>
                                </div>
                </form>
                                <div class="col-sm-12 no-pdg plc-bayar-sekarang mt-3 none">
                                    @php 
                                        $coupon_code = '';
                                        $readonly = '';
                                        $btnVerification = '';
                                        $btnDelete = ' none ';
                                        $discountCoupon = '0';
                                        foreach ($coupon_data as $value)
                                        {
                                            $coupon_code = $value->coupon_code;
                                            $readonly = ' readonly="true" ';
                                            $discountCoupon = $value->discount;

                                            $btnVerification = ' none ';
                                            $btnDelete = '';
                                        }
                                    @endphp
                                    <div class="plc-container for-coupon none mb-5">
                                        <div class="row">
                                            <h5 class="mb-3">Coupon</h5>
                                            <div class="col-md-12"><i>Please enter coupon code to get discount!</i></div>
                                            <div class="col-md-7">
                                                <div class="plc-coupon pdg10">
                                                    <form id="form-coupon" class="form-inline mt-3 mb-2" action="{{route('user_coupon_verification')}}">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="unique_code" id="unique_code" value="{{$header_transaction->unique_code}}">
                                                        <div class="form-group mb-3">
                                                            <input type="text" class="form-control no-radius input-lg" {{$readonly}} name="coupon_code" id="coupon_code" placeholder="Coupon code" value="{{$coupon_code}}">
                                                        </div>
                                                        <button type="button" class="btn btn-primary btn-main-2 {{$btnVerification}} btn-coupon" id="btn-coupon-verification" data-action="verification-coupon">Submit</button>
                                                        <button type="button" class="btn btn-primary btn-main-2 {{$btnDelete}} btn-coupon" id="btn-coupon-delete" data-action="delete-coupon">Delete</button>
                                                    </form>
                                                    <small class="notif-coupon error none"><i>Please input coupon code!</i></small>
                                                    <small class="notif-coupon-success text-success {{$btnDelete}}"><i>Coupon verified!</i></small>
                                                    <div class="mrg-btm20"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <button class="btn btn-primary" id="pay-button">PAY</button> -->
                                    <p>Please click the button below to process payment</p>
                                    <div id="showBtnStripe">
                                        <!-- <p class="sbs-header">Stripe</p> -->
                                        <div class="sbs-container">
                                            <form style="width: 100%;" id="form-pay-now" name="form-pay-now" action="{{route('user_payment_xendit')}}" method="post" novalidate="novalidate">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" id="id" value="{{$header_transaction->transaction_code}}" />
                                                <input type="hidden" name="amount" id="amount" value="" />
                                                <!-- xendit -->
                                                <input type="hidden" name="token_id" id="token_id" value="" />
                                                <input type="hidden" name="authentication_id" id="authentication_id" value="" />

                                                <fieldset class="form-group p-3" id="box-price-cc">
                                                    <div class="form-group row">
                                                        <label for="name_on_card" class="col-md-4 col-form-label text-md-right">Name on card</label>
                                                        <div class="col-md-8 field">
                                                            <input autocomplete="off" type="text" id="name_on_card" class="form-control required" name="name_on_card" aria-required="true">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="billing_address" class="col-md-4 col-form-label text-md-right">Billing address</label>
                                                        <div class="col-md-8 field">
                                                            <input autocomplete="off" type="text" id="billing_address" class="form-control" name="billing_address">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="cc_number" class="col-md-4 col-form-label text-md-right">Card Number</label>
                                                        <div class="col-md-8 field">
                                                            <input autocomplete="off" class="form-control cc-number" name="card_number" id="card_number" type="text" required pattern="(\d{4}\s?){4}" placeholder="&#8226;&#8226;&#8226;&#8226; &#8226;&#8226;&#8226;&#8226; &#8226;&#8226;&#8226;&#8226; &#8226;&#8226;&#8226;&#8226;" maxlength="19">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="cc_expiry" class="col-md-4 col-form-label text-md-right">Expiration Date</label>
                                                        <div class="col-md-8 field">
                                                            <input autocomplete="off" class="form-control cc-expires" name="card_expires" id="card_expires" type="text" maxlength='5' placeholder="MM/YY">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="cc_cvc" class="col-md-4 col-form-label text-md-right">CVC Code</label>
                                                        <div class="col-md-8 field">
                                                            <input autocomplete="off" class="form-control cc-cvc" name="cvn_code" id="cvn_code" placeholder="CVC" type="text" maxlength="4">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <div class="form-group row">
                                                    <div class="col-md-12 text-right field">
                                                        <button type="submit" id="form-pay-now-btn" class="btn btn-primary btn-main-2 btn-round-full">PAY NOW</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="showBtnStripeFree">
                                        <div class="sbs-container">
                                            <form style="width: 100%;" id="form-pay-now-free" name="form-pay-now-free" action="{{route('user_payment_xendit_free')}}" method="post" novalidate="novalidate">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" id="id" value="{{$header_transaction->transaction_code}}" />
                                                <div class="form-group row">
                                                    <div class="col-md-12 text-right field">
                                                        <button type="submit" id="form-pay-now-free-btn" class="btn btn btn-main-2 btn-round-full">FREE</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="separator">
                                        <div class="line"></div>
                                        <p>or</p>
                                        <div class="line"></div>
                                    </div>
                                    <button type="button" id="btn-xendit" class="btn btn-primary btn-main-2 btn-round-full mb-3">ANOTHER PAYMENT METHOD</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="col-lg-1 col-md-1"></div>
        <div class="col-lg-4 col-md-5 checkout-frame">
            <div class="checkout-bg-grey plc-container">
                <div class="checkout-bg-grey-inner">
                    <h5 class="mb-3">Order Summary</h5>
                    @if($header_transaction)
                        <a class="no-pdg cursor edit-cart col-xs-12" data-id="{{$header_transaction->unique_code}}" href="javascript:void(0);" data-href="{{route('process_edit_cart')}}">Edit</a>
                    @endif
                    <br>
                    @php
                        $countSubTotal = 0; 
                        $countProductWeight = 0;
                        $no = 1;
                        $stockArray = array();
                    @endphp
                    <table class="table cart-total-table">
                        <tbody>
                            <tr>
                                <td>PRODUCT</td>
                                <td class="title-subtotal total-cart"></td>
                            </tr>
                            @foreach($data_cart as $carts)
                            @php
                            //discount
                            $setDiscount = \App\Helper\Common_helper::set_discount($carts['price'], $carts['discount']);
                            $priceAfterDisc = $setDiscount[0];
                            $discount = $setDiscount[1];
                            //--------

                            $priceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($priceAfterDisc, "", false);

                            $subTotal = $priceAfterDisc * $carts['qty'];
                            $subTotalInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($subTotal, "", false);
                            $countSubTotal += $subTotalInCurrencyFormat[0];

                            $productWeight = ($carts['weight'] * $carts['qty']);
                            $countProductWeight += $productWeight;

                            $showPrice = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];
                            // $showSubTotal = $current_currency[1].$subTotalInCurrencyFormat[1];
                            $showSubTotal = $subTotalInCurrencyFormat[1];
                            @endphp
                            <tr>
                                <td>{{$carts['product_name']}} <b>× {{$carts['qty']}}</b></td>
                                <!-- <td class="total-cart"><b>{{$current_currency[1].$showSubTotal}}</b></td> -->
                                <td class="total-cart"><b>{{$current_currency[1].$showSubTotal}}</b></td>
                            </tr>
                            @endforeach
                            @if($header_transaction)
                            <?php
                                $subTotalInCurrencyFormat = \App\Helper\Common_helper::convert_to_format_currency($header_transaction->total_price);
                                // $showSubTotal = $current_currency[1].$subTotalInCurrencyFormat.' '.$current_currency[2];
                                $showSubTotal = $current_currency[1].$subTotalInCurrencyFormat;

                                $shippingCostInCurrencyFormat = \App\Helper\Common_helper::convert_to_format_currency($header_transaction->shipping_cost);
                                $showShippingCost = $current_currency[1].$shippingCostInCurrencyFormat.' '.$current_currency[2];

                                $taxInCurrencyFormat = \App\Helper\Common_helper::convert_to_format_currency($header_transaction->tax);
                                $showTax = $current_currency[1].$taxInCurrencyFormat.' '.$current_currency[2];

                                $grandTotalInCurrencyFormat = \App\Helper\Common_helper::convert_to_format_currency($header_transaction->total_payment);
                                $showGrandTotal = $current_currency[1].$grandTotalInCurrencyFormat.' '.$current_currency[2];

                                $couponInCurrencyFormat = \App\Helper\Common_helper::convert_to_format_currency(($header_transaction->coupon + 0));
                                $showCoupon = $current_currency[1].$couponInCurrencyFormat.' '.$current_currency[2];
                            ?>
                            <tr class="border-subtotal">
                                <td><b>Subtotal</b></td>
                                <td class="total-cart"><b>{{$showSubTotal}}</b></td>
                            </tr>
                            <tr class="border-subtotal">
                                <td><b>Disc. <!-- ({{$discountCoupon}}%) --></b></td>
                                <td class="total-cart btm-plc-discount"><b><span>{{$showCoupon}}</span></b></td>
                            </tr>
                            <tr class="border-subtotal">
                                <td><b>Shipping</b></td>
                                <td class="total-cart btm-plc-shipping" data-currency="{{$current_currency[1]}}" data-shipping-total="{{$header_transaction->shipping_cost}}"><b><span>{{$showShippingCost}}</span></b></td>
                            </tr>
                            <tr class="border-subtotal">
                                <td><b>Tax</b></td>
                                <td class="total-cart"><b>{{$showTax}}</b></td>
                            </tr>
                            @if($header_transaction->additional_price != '0')
                            <?php
                            $sddPriceInCurrencyFormat = \App\Helper\Common_helper::set_two_0_after_point(\App\Helper\Common_helper::convert_to_format_currency($header_transaction->additional_price));
                            $showAddPrice = $current_currency[1].$sddPriceInCurrencyFormat;
                            ?>
                            <tr class="border-subtotal">
                                <td><b>Additional Price</b></td>
                                <td class="total-cart"><b>{{$showAddPrice}}</b></td>
                            </tr>
                            @endif
                            <tr class="last-row-chekcout">
                                <td><b>Total ({{$current_currency[2]}})</b></td>
                                <td class="total-cart btm-plc-grand-total" data-grand-total="{{$header_transaction->total_payment}}"><b><span>{{$showGrandTotal}}</span></b></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- xendit -->
	<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-fullscreen">
			<div class="modal-content">
				<div class="modal-body">
					<div id="three-ds-container">
						<iframe height="100%" width="100%" id="sample-inline-frame" name="sample-inline-frame"> </iframe>
					</div>
				</div>
			</div>
		</div>
	</div>	

	<!-- loading -->
	<div class="modal fade" id="staticBackdropLoading" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabelloading" aria-hidden="true">
		<div class="modal-dialog modal-fullscreen">
			<div class="modal-content">
				<div class="modal-body" align="center">
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-6">
							<i class="fa fa-spin fa-6x fa-cog" id="loading-icon"></i>
							<div id="display-payment-processing"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	

</div>


<input type="hidden" name="customer_form_complete" id="customer_form_complete" value="{{$is_customer_form_complete}}" />
<input type="hidden" name="shipping_form_complete" id="shipping_form_complete" value="0">

<input type="hidden" name="symbol_global" id="symbol_global" value="{{$current_currency[1]}}">
<input type="hidden" name="code_global" id="code_global" value="{{$current_currency[2]}}">
<input type="hidden" name="actionLocation" id="actionLocation" value="{{route('process_shipping_location')}}">
<input type="hidden" name="actionEstimate" id="actionEstimate" value="{{route('process_shipping_estimate')}}">
<input type="hidden" name="actionCheckBeforePayment" id="actionCheckBeforePayment" value="{{route('check_before_payment')}}">
<div class="featurette-divider"></div>
<div class="featurette-divider"></div>


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
<script type="text/javascript" src="{{asset(env('URL_ASSETS').'countdown/jQuery.countdownTimer.min.js')}}"></script>

<!-- xendit -->
<script type="text/javascript" src="https://js.xendit.co/v1/xendit.min.js"></script>
<script>
$(document).ready(function() {
    var $form = $('#form-pay-now');
    
    $('#btn-xendit').click(function(e){
        $.ajax({
            url: '{{route("user_payment_xendit_another_payment_method")}}',
            type: "POST",
            data: {'_token':  $form.find('input[name=_token]').val(), 'id': $form.find('#id').val(), 'amount': $form.find('#amount').val()},
            dataType: 'json',
            success: function(response) {
                if(response.trigger == "yes"){
                    location.href = response.direct
                }else{
                    toastr.warning(response.notif);
                }
            },
            error: function()
            {
                toastr.warning('There is something wrong, please refresh page and try again.');
            }            
        });
    });

    $("#form-pay-now").validate({
        rules :{
            name_on_card :{
                required : true,
            },
            billing_address :{
                required : true,
            },
            card_number :{
                required : true,
            },
            card_expires :{
                required : true,
            },
            cvn_code :{
                required : true,
            },
        },
        messages: {
            name_on_card: {
                required: 'Card Name is required!',
            },
            billing_address: {
                required: 'Billing Address is required!',
            },
            card_number: {
                required: 'Card Number is required!',
            },
            card_expires: {
                required: 'Card Expiry is required!',
            },
            cvn_code: {
                required: 'CVC is required!',
            },
        },
        errorElement: 'small',
        submitHandler: function(form) {

            if($('#shipping_form_complete').val() == '1' && $('#customer_form_complete').val() == '1'){
                
                var do_next = false
                if($form.find('#token_id').val() != ''){
                    do_next = true
                } else {
                    Xendit.setPublishableKey('{{env('XENDIT_PUBLIC_KEY')}}');

                    // Request a token from Xendit:
                    var tokenData = getTokenData();
                    Xendit.card.createToken(tokenData, xenditResponseHandler);
                }

                if(do_next){
                // $("#form-payment-loader").fadeIn();
                $("#form-payment-btn").attr('disabled', 'disabled');
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#form-payment-btn").removeAttr('disabled');
                        // $('#form-payment-loader').fadeOut();
                        if(response.trigger == "yes"){
                            location.href = response.direct
                        }else{
                            toastr.warning(response.notif);
                            $('#display-payment-processing').html('')
                            $('#staticBackdropLoading').modal('hide');
                        }
                    },
                    error: function()
                    {
                        // $('#form-payment-loader').fadeOut();
                        $("#form-payment-btn").removeAttr('disabled');
                        toastr.warning('There is something wrong, please refresh page and try again.');
                        $('#display-payment-processing').html('')
                        $('#staticBackdropLoading').modal('hide');
                    }            
                });
                } else {
                    return false
                }
            }else{
                NProgress.done();
                if($('#customer_form_complete').val() == '0'){
                    toastr.warning('Please complete customer form.');
                }
                if($('#shipping_form_complete').val() == '0'){
                    toastr.warning('Please complete shipping information.');   
                }
            }
        }
    });

    function getTokenData () {
        var card_expires = $form.find('#card_expires').val();
        var card_exp_month = '';
        var card_exp_year = '';
        card_expires = card_expires.split('/');
        try {
            card_exp_month = card_expires[0];
            card_exp_year = '20'+card_expires[1];
        } catch (error) {
            
        }

        var card_number = $form.find('#card_number').val();
        card_number = card_number.split(" ").join("");

        return {
            amount: $form.find('#amount').val(),
            currency: 'IDR',
            card_number: card_number,
            card_exp_month: card_exp_month,
            card_exp_year: card_exp_year,
            card_cvn: $form.find('#cvn_code').val(),
            is_multiple_use: false,
            should_authenticate: true,
            token_id: $form.find('#token_id').val(),
        };
    }  

    function xenditResponseHandler (err, creditCardToken) {
        $form.find('.submit').prop('disabled', false);
        if (err) {
            return displayError(err);
        }
        if (creditCardToken.status === 'APPROVED' || creditCardToken.status === 'VERIFIED') {
            $form.find('#token_id').val(creditCardToken.id);
            $form.find('#authentication_id').val(creditCardToken.authentication_id);
            $form.submit();
            $('#staticBackdrop').modal('hide');
            $('#staticBackdropLoading').modal('show');
            $form.find('.submit').prop('disabled', true);
            $('#display-payment-processing').html('<br/><p>payment is still in process, please do not close this page</p>')
        } else if (creditCardToken.status === 'IN_REVIEW') {
            window.open(creditCardToken.payer_authentication_url, 'sample-inline-frame');
            $('#staticBackdrop').modal('show');
        } else if (creditCardToken.status === 'FRAUD') {
            displayError(creditCardToken);
        } else if (creditCardToken.status === 'FAILED') {
            displayError(creditCardToken);
        }
    }

    function displayError (err) {
        toastr.warning(JSON.stringify(err, null, 4));
        $('#staticBackdrop').modal('hide');
        $('#staticBackdropLoading').modal('hide');
    };

    // payment stripe free
    $("#form-pay-now-free").validate({
        rules :{},
        messages: {},
        errorElement: 'small',
        submitHandler: function(form) {
            $("#form-pay-now-free-btn").attr('disabled', 'disabled');
            var formData = new FormData(form);
            NProgress.start();
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    NProgress.done();
                    $("#form-pay-now-free-btn").removeAttr('disabled');
                    if(response.trigger == "yes"){
                        toastr.success(response.notif)
                        setTimeout(function(){ 
                            location.href = response.direct; 
                        }, 2000);
                    }else{
                        toastr.warning(response.notif)
                    }
                },
                error: function()
                {
                    NProgress.done();
                    $("#form-pay-now-free-btn").removeAttr('disabled');
                }            
            });
        }
    });
});
</script>
<!-- ==== xendit ==== -->

<script>
$(document).ready(function() {
    $('.select2').select2();

    <?php
        if(sizeof($getTimerTrans) > 0)
        {
    ?>
    $(function(){
        $("#future_date").countdowntimer({
            startDate : "<?=$getTimerTrans['timeStart']?>",
            dateAndTime : "<?=$getTimerTrans['timeEnd']?>",
            size : "lg",
            regexpMatchFormat : "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
            regexpReplaceWith : "$1<sup>years</sup> / $2<sup>months</sup> / $3<sup>days</sup> / $4<sup>hours</sup> / $5<sup>minutes</sup> / $6<sup>seconds</sup>"
        });
    });
    <?php
        }
    ?>

    $('.edit-cart').click(function(e){
        e.preventDefault();
        var urlAction = $(this).attr('data-href');
        var trans_id = $(this).attr('data-id');
        NProgress.start();
        $.ajax({
            url: urlAction,
            dataType: 'json',
            type: 'POST',
            data: {
                'trans_id': trans_id, 
                '_token': $('input[name=_token]').val()
            },
            success: function(response, textStatus, XMLHttpRequest)
            {
                NProgress.done();
                if(response.trigger=="yes")
                {
                    location.href = response.notif;
                }
                else
                {
                        toastr.warning(response.notif);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                NProgress.done();
                toastr.error('There is something wrong, please refresh page and try again.');
            }
        });
        return false;
    });

    $("#btn-shipping").click(function(e){
        $("#btn-shipping").attr('disabled', 'disabled');
        NProgress.start();
        var emelentForm = $('#form-shipping');
        // console.log(emelentForm.serialize());
        $.ajax({
            url: emelentForm.attr('action'),
            type: 'POST',
            data: emelentForm.serialize(),
            dataType: 'json',
            success: function(response) {
                $("#btn-shipping").removeAttr('disabled');
                if(response.trigger == "yes"){
                    $('#amount').val(response.amount);
                    $('.notif-shipping').html('');
                    var currency = $('.btm-plc-shipping').attr('data-currency');
                    $('.btm-plc-shipping').find('span').text(currency+response.shipping_cost);
                    $('.btm-plc-grand-total').find('span').text(currency+response.total_payment);
                    
                    // free - without payment
                    if(response.total_payment == 0){
                        $('#showBtnStripeFree').fadeIn();
                        $('#showBtnStripe').fadeOut();
                    } else {
                        $('#showBtnStripeFree').fadeOut();
                        $('#showBtnStripe').fadeIn();
                    }

                    $('.result-shipping-cost').fadeOut();
                    $('.plc-lanjut-bayar').fadeOut(function(){
                        $('.plc-bayar-sekarang').fadeIn();
                    })

                    // coupon page
                    $('.for-coupon').fadeIn();

                    $('.plc-shipping-form').fadeOut(function(){

                        var addInfo = '';
                        if(response.profile_cus != undefined){
                            addInfo = ''+
                            '<div class="after-submit">'+$('#first_name').val()+'</div>'+
                            '<div class="after-submit">'+$('#last_name').val()+'</div>'+
                            '<div class="after-submit mrg-btm20">'+$('#phone_prefix').val()+$('#phone_number').val()+'</div>';
                        }

                        $('.shipping-info').html(''+
                            '<a class="cursor edit-shipping pull-right" href="javascript:void(0);">Change Shipping</a>'+
                            addInfo+
                            '<div class="after-submit mt-3">'+response.country+'</div>'+
                            '<div class="after-submit">'+response.province+'</div>'+
                            '<div class="after-submit">'+response.city+'</div>'+
                            '<div class="after-submit">'+response.subdistrict+'</div>'+
                            '<div class="after-submit">'+$('#address').val()+'</div>'+
                            '<div class="after-submit">'+$('#postalcode').val()+'</div>'+
                        '');
                        $('.shipping-info').fadeIn();
                        $('#shipping_form_complete').val('1');
                    });
                }
                else
                {
                    $('.notif-shipping').html('<div class="text-danger mrg-btm20">'+response.notif+'</div>');
                }
                NProgress.done();
            },
            error: function()
            {
                $("#btn-shipping").removeAttr('disabled');
                NProgress.done();
                toastr.error('There is something wrong, please refresh page and try again.');
            }            
        });
    });

    //shipping
    $(document).on('click', '.edit-shipping', function(e){
        $('#shipping_form_complete').val('0');
        $('.shipping-info').fadeOut(function(){
            $('.plc-shipping-form').fadeIn();
        });
        $('.plc-bayar-sekarang').fadeOut(function(){
            $('.plc-lanjut-bayar').fadeIn();
            $('.result-shipping-cost').fadeIn();
        });
    });

    function get_shipping_cost()
    {
        var triggerNational = true;
        if($('#country').val() != '')
        {
            if($('#province').val() == '')
            {
                triggerNational = false;
            }

            if($('#city').val() == '')
            {
                triggerNational = false;
            }

            if($('#country').val() == '236')
            {
                if($('#subdistrict').val() == '')
                {
                    triggerNational = false;
                }
            }
        }
        else
        {
            triggerNational = false;
        }

        if($('#address').val() == '')
        {
            triggerNational = false;
        }

        if($('#postalcode').val() == '')
        {
            triggerNational = false;
        }

        if(triggerNational == true)
        {
            NProgress.start();
            var urlAction = $('#actionEstimate').val();
            $.ajax({
                url: urlAction,
                dataType: 'json',
                type: 'POST',
                data: {
                    'trans_id': $('#form-shipping').find('input[name=trans_id]').val(), 
                    'country': $('#country').val(), 
                    'province': $('#province').val(),
                    'city': $('#city').val(),
                    'subdistrict': $('#subdistrict').val(),
                    'postalcode': $('#postalcode').val(),
                    'address': $('#address').val(),
                    '_token': $('input[name=_token]').val()
                },
                success: function(response, textStatus, XMLHttpRequest)
                {
                    NProgress.done();
                    if(response.trigger=="yes")
                    { 
                        $("#btn-shipping").removeAttr('disabled');
                        $('.result-shipping-cost').html(response.notif);
                    }
                    else
                    {
                            toastr.warning(response.notif)
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    NProgress.done();
                    toastr.remove();
                    toastr.error('There is something wrong, please refresh page and try again.');
                }
            });
        }
    }

    $('#address, #postalcode').blur(function(){
        get_shipping_cost();
    });

    $('#country, #province, #city, #subdistrict').change(function(e){
        $("#btn-shipping").attr('disabled', 'disabled');
        var data_id = $(this).val();
        var trigger = $(this).attr('id');

        $('.result-shipping-cost').html('');
        get_shipping_cost();
        // $('#province').select2('val', '');
        // $('#city').select2('val', '');
        // $('#subdistrict').select2('val', '');

        // if($('#country').val() == '236')
        // {
        //     $('.select-national').fadeIn();
        // }
        // else
        // {
        //     $('.select-national').fadeOut();
        // }

        $('.select-national').fadeIn();

        if(trigger != 'subdistrict')
        {
            // if($('#country').val() == '236')
            // {
                if($(this).val() != '')
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
            // }
        }
    });

    // coupon
    $('.btn-coupon').click(function(e){
        	checkingCoupon($(this).attr('data-action'));
    });
    $('#coupon_code').keypress(function(e){
        if(e.which == 13)
        {
            checkingCoupon('verification');
        }
    });

    function checkingCoupon(trigger)
    {        	
        if(trigger == '')
        {
            toastr.error('Coupons cannot be processed.');
        }
        else
        {
            if($('#coupon_code').val() == '')
            {
                $('.notif-coupon').fadeIn();
            }
            else
            {
                $('.notif-coupon').fadeOut();
                $("#check-coupon").attr('disabled', 'disabled');
                var emelentForm = $('#form-coupon')
                NProgress.start();
                $.ajax({
                    url: emelentForm.attr('action')+'/'+trigger,
                    type: 'POST',
                    data: emelentForm.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        NProgress.done();
                        $("#btn-coupon").removeAttr('disabled');
                        if(response.trigger == "yes")
                        {
                            $('#amount').val(response.amount);
                            var currency = $('.btm-plc-shipping').attr('data-currency');
                            // $('.btm-plc-discount').find('label').text('DISC. ('+response.discount+'%)');
                            // $('.btm-plc-discount').find('span').text('-'+currency+response.discount_nominal);
                            $('.btm-plc-discount').find('span').text(currency+response.discount_nominal);
                            $('.btm-plc-grand-total').find('span').text(currency+response.total_payment);

                            // free - without payment
                            if(response.total_payment == 0){
                                $('#showBtnStripeFree').fadeIn();
                                $('#showBtnStripe').fadeOut();
                            } else {
                                $('#showBtnStripeFree').fadeOut();
                                $('#showBtnStripe').fadeIn();
                            }

                            if(trigger == 'verification-coupon')
                            {
                                $("#coupon_code").attr('readonly', 'true');
                                $('.notif-coupon-success').fadeIn();

                                $('#btn-coupon-verification').fadeOut(function(){
                                    $('#btn-coupon-delete').fadeIn();
                                });
                            }
                            else
                            {
                                $("#coupon_code").removeAttr('readonly');
                                $('.notif-coupon-success').fadeOut();

                                $('#btn-coupon-delete').fadeOut(function(){
                                    $('#btn-coupon-verification').fadeIn();
                                });
                            }
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                    },
                    error: function()
                    {
                        NProgress.done();
                        $("#btn-coupon").removeAttr('disabled');
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
        }
    }

    // paypal - stripe -animation
    $('#showBtnPaypal .sbp-header').on('click', function() {
        $('.sbs-header').fadeIn()
        $('.sbs-container').slideUp();

        $('.sbp-header').fadeOut()
        $('.sbp-container').slideDown();
    });
    $('#showBtnStripe .sbs-header').on('click', function() {
        $('.sbp-header').fadeIn()
        $('.sbp-container').slideUp();

        $('.sbs-header').fadeOut()
        $('.sbs-container').slideDown();
    });

    // js credit card
    $('#card_number').on('keyup',function (e) {
        if (e.keyCode !== 8) {
            if (this.value.length === 4 || this.value.length === 9 || this.value.length === 14) {
            this.value = this.value += ' ';
            }
        }
    });

    $('#card_expires').on('keyup',function (event) {
        var inputChar = String.fromCharCode(event.keyCode);
        var code = event.keyCode;
        var allowedKeys = [8];
        if (allowedKeys.indexOf(code) !== -1) {
            return;
        }

        event.target.value = event.target.value.replace(
            /^([1-9]\/|[2-9])$/g, '0$1/' // 3 > 03/
        ).replace(
            /^(0[1-9]|1[0-2])$/g, '$1/' // 11 > 11/
        ).replace(
            /^([0-1])([3-9])$/g, '0$1/$2' // 13 > 01/3
        ).replace(
            /^(0?[1-9]|1[0-2])([0-9]{2})$/g, '$1/$2' // 141 > 01/41
        ).replace(
            /^([0]+)\/|[0]+$/g, '0' // 0/ > 0 and 00 > 0
        ).replace(
            /[^\d\/]|^[\/]*$/g, '' // To allow only digits and `/`
        ).replace(
            /\/\//g, '/' // Prevent entering more than 1 `/`
        );
    });

    $(document).on('click', '.edit-customer', function(e){
        $('.customer-info').fadeOut(function(){
            $('.customer-form').fadeIn();
            $('#customer_form_complete').val('0');
        });
    });

    $("#form-guest").validate({
        rules :{
            emailGuest :{
                required : true,
            }
        },
        messages: {
            emailGuest: {
                required: 'Please insert email address!',
            }
        },
        errorElement: 'small',
        submitHandler: function(form) {
            NProgress.start();
            $("#btn-guest").attr('disabled', 'disabled');
            var formData = new FormData(form);
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    $("#btn-guest").removeAttr('disabled');
                    NProgress.done();
                    if(response.trigger == "yes"){
                        $('.customer-form').fadeOut(function(){
                            $('.customer-info .after-submit').html(response.notif);
                            $('.customer-info').fadeIn();

                            $('#customer_form_complete').val('1');
                        });
                    }else{
                        $('.result-shipping-cost').html(response.notif);
                        toastr.warning(response.notif);
                    }
                },error: function(){
                    NProgress.done();
                    $("#btn-guest").removeAttr('disabled');
                    toastr.error('There is something wrong, please refresh page and try again.');
                }            
            });
        }
    });

    $('.sign-in').click(function(e){
        $('#container-checkout').fadeOut(function(){
            $('#container-login').fadeIn(); 
            $('html, body').animate({scrollTop:0}, '500');
        });
    });

    $('.container-login-close-btn').click(function(e){
        $('#container-login').fadeOut(function(){
            $('#container-checkout').fadeIn();
        });
    });
});
</script>
@include('frontend.login_script')
@stop