@extends('frontend.layout.template_invoice')

@section('style')
<style type="text/css">
	.plc-invoice{ border: 1px solid #000000; padding: 20px; }
	h2{ color: #000000; margin: 0; padding: 0; }
	.header-text{ font-weight: bold; font-size: 18px;}
	img.header{ margin-top: 20px; margin-left: 0; margin-bottom: 15px; }
	.header-total span{ font-weight: bold; font-size: 20px; }
	.header-total div{ background: #ececec; border-radius: 10px; font-weight: bold; font-size: 20px; padding: 18px 0 18px 0; }
	table{ width: 100%; }
	table tr td, table tr th{ height: 30px; padding-left: 5px; padding-right: 5px;}
	table tr.total td, table tr.total th{ background: #ececec; }
</style>
@stop

@section('content')
<div class="cart-checkout row mrg-tp60">
    <div class="col-sm-12">
    	@if(isset($header_transaction->transaction_code))
    		<?php
				$processedPaymentTop = '';
				$processedPayment = '';
				$statusHeader = '';
			?>
			@if($header_transaction->status == '5' || $header_transaction->status == '6')
				<?php $statusHeader = '<span class="text-danger">CANCEL</span>';?>
			@else
				@if($header_transaction->payment_status == '1')
					<?php $statusHeader = '<span class="text-success">PAID</span>';?>
				@else
					<?php 
						$statusHeader = '<span class="" sty>NOT PAID</span>';
						$processedPaymentTop = '<a class="btn btn-default btn-lg pull-right" style="margin-top: 20px;" href="'.route('cart_checkout').'/'.$header_transaction->unique_code.'">PAY NOW</a>';
						// $processedPayment = '<a class="btn btn-default btn-lg" href="'.route('cart_checkout').'/'.$header_transaction->unique_code.'">PAY NOW</a>';
					?>
				@endif
			@endif
		<a href="{{url('/')}}">
    		<img class="header" width="120px" src="{{asset(env('URL_IMAGE').'logo.png')}}">
		</a>
    	<?=$processedPaymentTop?>
    	<div class="col-sm-12 no-pdg plc-invoice">
    		<h2 class="mrg-btm30" style="margin-bottom: 20px;">INVOICE : {{$header_transaction->transaction_code}}</h2>
    		<div class="row">
    			<div class="col-sm-6 text-left" style="margin-bottom: 20px;">
    				<div class="header-text">Send To :</div>
    				@foreach($shipping_data as $key)
    					{{$key->country_name}}<br>
    					{{$key->province_name ? $key->province_name.'<br>' : ''}}
						{{$key->city_name}}<br>
						{{$key->subdistrict_name}}<br>
    					{{$key->detail_address}}<br>
    					{{$key->postal_code}}
    				@endforeach
    			</div>
    			<div class="col-sm-6" style="margin-bottom: 20px;">
    				<div class="header-total text-center">
    					<?php
    						echo $statusHeader;

    						$total = \App\Helper\Common_helper::currency_transaction($header_transaction->transaction_id, $header_transaction->total_payment);
							echo '<div class="text-center mrg-tp20">'.$total[2].$total[1].' '.$total[3].'</div>';
    					?>
    				</div>
    			</div>
    		</div>
    		<div class="row mrg-tp40">
				<div class="col-sm-12">
					<div class="header-text">Details :</div>
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
	                        <thead>
	                            <tr>
	                                <th>#</th>
	                                <th>Product</th>
	                                <th>SKU</th>
	                                <th>Qty</th>
	                                <th>Price</th>
	                                <th>Status</th>
	                                <th>Total</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            
	                            <?php
	                                $no = 1;
	                                foreach ($detail_transaction as $key) 
	                                {
	                                    $formatPrice = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->price);

	                                    $priceDisc = \App\Helper\Common_helper::set_discount(($key->price * $key->qty), $key->discount);
	                                    $formatPriceTotal = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $priceDisc[0]);

	                                    $imgProduct = \App\Models\EmProductImg::getWhereLimitOne([['product_id', '=', $key->product_id],['sku_id', '=', $key->sku_id]]);

	                                    echo '
	                                        <tr>
	                                            <td>'.$no.$key->product_id.'</td>
	                                            <td>
	                                                <a target="_blank" href="'.route('shop_detail_page').'/'.str_replace(' ', '-', $key->product_name).'-'.$key->product_id.'">
	                                                    '.$key->product_name.'<br>
	                                                    <img width="50px" src="'.asset(env('URL_IMAGE').'product/thumb/'.(isset($imgProduct->image) ? $imgProduct->image : "")).'">
	                                                </a>
	                                            </td>
	                                            <td>';

	                                                if($key->size != '') 
	                                                    echo'<b>Size : </b> '.$key->size.'<br>';
	                                                    
	                                                if($key->color_name != '' && $key->color_hexa != '') 
	                                                    echo'<b>Color : </b> '.$key->color_name.' <div style="width: 25px; height: 25px; background: '.$key->color_hexa.'"></div><br>';
	                                    echo '
	                                            </td>
	                                            <td>'.$key->qty.'</td>';

	                                    echo '<td>'.$formatPrice[2].$formatPrice[1].' '.$formatPrice[3].'</td>';
	                                    echo '<td>'.\App\Helper\Common_helper::trans_detail_status($key->status).'</td>';
	                                    echo '<td class="text-right">'.$formatPriceTotal[2].$formatPriceTotal[1].' '.$formatPriceTotal[3].'</td>
	                                        </tr>
	                                    ';
	                                    $no++;
	                                }    
	                            ?>
	                            
	                        </tbody>
	                    </table>
                    </div>
				</div>
				<?php
					$subTotal = \App\Helper\Common_helper::currency_transaction($header_transaction->transaction_id, $header_transaction->total_price);
					$coupon = \App\Helper\Common_helper::currency_transaction($header_transaction->transaction_id, ($header_transaction->coupon + 0));
					$shippingCost = \App\Helper\Common_helper::currency_transaction($header_transaction->transaction_id, $header_transaction->shipping_cost);
					$additionalPrice = \App\Helper\Common_helper::currency_transaction($header_transaction->transaction_id, $header_transaction->additional_price);
					$tax = \App\Helper\Common_helper::currency_transaction($header_transaction->transaction_id, $header_transaction->tax);
				?>
				<div class="col-sm-6 col-sm-offset-6">
					<table>
						<tr>
							<th class="text-left">Sub Total</th>
							<td class="text-right">{{$subTotal[2].$subTotal[1].' '.$subTotal[3]}}</td>
						</tr>
                        <tr>
                            <th class="text-left">Voucher</th>
							@if($coupon[1] == '' || $coupon[1] == '0')
							<td class="text-right">{{$coupon[2]}}0 {{$coupon[3]}}</td>
							@else 
							<td class="text-right">-{{$coupon[2].$coupon[1]}} {{$coupon[3]}}</td>
							@endif
                        </tr>
						<tr>
							<th class="text-left">Shipping Cost</th>
							<td class="text-right">{{$shippingCost[2].$shippingCost[1].' '.$shippingCost[3]}}</td>
						</tr>
						@if($header_transaction->additional_price != 0)
						<tr>
							<th class="text-left">Additional Price</th>
							<td class="text-right">{{$additionalPrice[2].$additionalPrice[1].' '.$additionalPrice[3]}}</td>
						</tr>
						@endif
						<tr>
							<th class="text-left">Tax</th>
							<td class="text-right">{{$tax[2].$tax[1].' '.$tax[3]}}</td>
						</tr>
						<tr class="total">
							<th class="text-left">Total</th>
							<td class="text-right">{{$total[2].$total[1].' '.$total[3]}}</td>
						</tr>
						<tr>
							<th class="text-left" colspan="2"><hr></th>
						</tr>
						<tr>
							@if($header_transaction->payment_status == '1')
								<th class="text-left">PAID</th>
								<td class="text-right">{{$total[2].$total[1].' '.$total[3]}}</td>
							@else
								<th class="text-left">PAID</th>
								<td class="text-right">{{$subTotal[2].'0.00 '.$subTotal[3]}}</td>
							@endif
						</tr>
					</table>
				</div>
    		</div>
    	</div>
    	<div class="col-sm-12 no-pdg text-right"><?=$processedPayment?></div>
    	@else
    	<div class="text-center mrg-btm20">
    		<img width="200px" src="{{asset(env('URL_IMAGE').'logo_blue_font.png')}}">
    	</div>
    	<div class="alert alert-info text-center">
    		Invoice not found.
    	</div>
    	<h3 class="text-center"><a href="{{url('/')}}">Back to HOME <i class="fa fa-sign-out"></i></a></h3>
    	@endif
    </div>
</div>
@stop
