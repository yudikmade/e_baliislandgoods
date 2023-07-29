@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/account.css')}}" rel="stylesheet">
<style>
#form-save-data-top .box-body {
    margin-top: 40px;
}
#form-save-data-top .btn-white {
    width: auto;
    margin-left: 10px;
}
#form-save-data-top .mrg-btm{
    margin-bottom: 20px;
}
body {
		background: #f7faff;
	}
</style>
@stop

@section('content')
<div class="container account">
    <div class="row mrg-tp20 no-mrg-top-mobile">
        @include('frontend.account.profile_nav')
        <div class="account-right-side col-md-9 col-sm-12 mrg-tp30 no-mrg-top-mobile">
            <div class="col-sm-12 no-pdg mrg-btm30 no-pdg">
                <?php $type_payment = '';?>
                @foreach($data_transaction as $key)
                <h3>Transaction - {{$key->transaction_code}}</h3>
                <?php 
                    $type_payment = $key->type_payment;
                ?>
                <form id="form-save-data-top">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="col-sm-12">
                            <div class="row mrg-btm">
                                <div class="col-lg-3 col-md-3" style="padding-top: 5px;">Invoice : </div>
                                <div class="col-lg-9 col-md-9">
                                    <b>{{$key->transaction_code}}</b>
                                    &nbsp;&nbsp;
                                    @if($key->payment_status == '0' && $key->status != '5' && $key->status != '6')
                                        <a class="btn btn-primary" href="{{route('cart_checkout').'/'.$key->transaction_code}}">PAY NOW</a>
                                    @else
                                        <a class="btn btn-default" href="{{route('show_invoice').'/'.$key->unique_code}}">INVOICE</a>
                                    @endif
                                </div>
                            </div>
                            <?php
                                $formatSubTotal = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->total_price);

                                $formatShipping = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->shipping_cost);

                                $formatTotal = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->total_payment);
                                $formatTax = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->tax);
                                $formatCoupon = \App\Helper\Common_helper::currency_transaction($key->transaction_id, ($key->coupon+0));
                            ?>
                            <div class="row mrg-btm">
                                <div class="col-lg-3 col-md-3">Sub total : </div>
                                <div class="col-lg-9 col-md-9">
                                    {{$formatSubTotal[2].$formatSubTotal[1].' '.$formatSubTotal[3]}}
                                </div>
                            </div>
                            <!-- <div class="row mrg-btm">
                                <div class="col-lg-2 col-md-3">Coupon Disc. : </div>
                                <div class="col-lg-10 col-md-9">
                                    -{{$formatCoupon[2].$formatCoupon[1].' '.$formatCoupon[3]}}
                                </div>
                            </div> -->
                            <div class="row mrg-btm">
                                <div class="col-lg-3 col-md-3">Shipping Cost : </div>
                                <div class="col-lg-9 col-md-9">
                                    {{$formatShipping[2].$formatShipping[1].' '.$formatShipping[3]}}
                                </div>
                            </div>
                            @if($formatSubTotal[4] == '1')
                            <div class="row mrg-btm">
                                <div class="col-lg-3 col-md-3">Additional Cost : </div>
                                <div class="col-lg-9 col-md-9">
                                    {{$key->additional_price}}
                                </div>
                            </div>
                            @endif
                            <div class="row mrg-btm">
                                <div class="col-lg-3 col-md-3">Tax : </div>
                                <div class="col-lg-9 col-md-9">
                                    {{$formatTax[2].$formatTax[1].' '.$formatTax[3]}}
                                </div>
                            </div>
                            <div class="row mrg-btm">
                                <div class="col-lg-3 col-md-3">Total : </div>
                                <div class="col-lg-9 col-md-9">
                                    {{$formatTotal[2].$formatTotal[1].' '.$formatTotal[3]}} <span class="text-primary">(PAID)</span>
                                </div>
                            </div>
                            <!-- <div class="row mrg-btm">
                                <div class="col-lg-2 col-md-3">Type of payment : </div>
                                <div class="col-lg-10 col-md-9">
                                    <?=\App\Helper\Common_helper::type_of_payment($key->type_payment)?>
                                </div>
                            </div> -->
                            <div class="row mrg-btm">
                                <div class="col-lg-3 col-md-3">Status : </div>
                                <div class="col-lg-9 col-md-9">
                                    <?=\App\Helper\Common_helper::transaction_status($key->status, $key->payment_status)?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <br>
                            <hr>
                            <br>
                            @foreach($data_shipping as $shippings)
                                <div class="row mrg-btm">
                                    <div class="col-lg-3 col-md-3">Country : </div>
                                    <div class="col-lg-9 col-md-9">
                                        {{$shippings->country_name}}
                                    </div>
                                </div>
                                @if($shippings->country_id == '236')
                                    <div class="row mrg-btm">
                                        <div class="col-lg-3 col-md-3">Province : </div>
                                        <div class="col-lg-9 col-md-9">
                                            {{$shippings->province_name}}
                                        </div>
                                    </div>
                                    <div class="row mrg-btm">
                                        <div class="col-lg-3 col-md-3">City : </div>
                                        <div class="col-lg-9 col-md-9">
                                            {{$shippings->city_name}}
                                        </div>
                                    </div>
                                    <div class="row mrg-btm">
                                        <div class="col-lg-3 col-md-3">Subdistrict : </div>
                                        <div class="col-lg-9 col-md-9">
                                            {{$shippings->subdistrict_name}}
                                        </div>
                                    </div>
                                @endif
                                <div class="row mrg-btm">
                                    <div class="col-lg-3 col-md-3">Address : </div>
                                    <div class="col-lg-9 col-md-9">
                                        {{$shippings->detail_address}}
                                    </div>
                                </div>
                                <div class="row mrg-btm">
                                    <div class="col-lg-3 col-md-3">Postal Code : </div>
                                    <div class="col-lg-9 col-md-9">
                                        {{$shippings->postal_code}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
                @endforeach
                <br><br>
                <div class="row">
                    <div class="table-responsive col-sm-12 mrg-tp30">
                        <div class="table-responsive col-sm-12 no-pdg">
                            <table id="displayData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <!-- <th>SKU</th> -->
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <!-- <th>Discount</th> -->
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php
                                        $no = 1;
                                        foreach ($data_transaction_detail as $key) 
                                        {
                                            $formatPrice = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->price);

                                            $priceDisc = \App\Helper\Common_helper::set_discount(($key->price * $key->qty), $key->discount);
                                            $formatPriceTotal = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $priceDisc[0]);

                                            $imgProduct = \App\Models\EmProductImg::getWhereLimitOne([['product_id', '=', $key->product_id]]);

                                            echo '
                                                <tr>
                                                    <td>'.$no.'</td>
                                                    <td>
                                                        <a target="_blank" href="'.route('shop_detail_page').'/'.str_replace(' ', '-', $key->product_name).'-'.$key->product_id.'">
                                                            '.$key->product_name.'<br>
                                                            <img width="50px" src="'.asset(env('URL_IMAGE').'product/thumb/'.$imgProduct->image).'">
                                                        </a>
                                                    </td>';
                                            echo '<td>'.$key->qty.'</td>';

                                            // echo '<td>'.$formatPrice[2].$formatPrice[1].' '.$formatPrice[3].'</td>'; 
                                            echo '<td>'.$formatPrice[2].$formatPrice[1].'</td>'; 
                                            // echo '<td>'.$formatPriceTotal[2].$formatPriceTotal[1].' '.$formatPriceTotal[3].'</td>';
                                            echo '<td>'.$formatPriceTotal[2].$formatPriceTotal[1].'</td>';

                                            echo '<td>'.\App\Helper\Common_helper::trans_detail_status($key->status).'</td>
                                                </tr>
                                            ';
                                            $no++;
                                        }    
                                    ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="featurette-divider"></div>
<div class="featurette-divider"></div>
@stop

@section('script')   
@stop