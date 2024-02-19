
<h1>Hi Admin,</h1>
<p>There is transaction from <?=$first_name?> for invoice number #<?=$transaction_code?></p>
<table border="1" class="" style="font-family: \'Helvetica Heue\', Arial, sans-serif; font-size: 13px; line-height: 18px;">
	<thead>
		<tr>
			<th style="text-align: center;">Order Number</th>
			<th style="text-align: center;">Item</th>
			<th style="text-align: center;">Quantity</th>
			<th style="text-align: center;">Date Ordered</th>
		</tr>
	</thead>
	<tbody>
		@foreach($transaction_detail as $key => $value)
			<tr>
				<td style="text-align: center;">{{($key + 1)}}</td>
				<td>
					@php $imgProduct = \App\Models\EmProductImg::getWhereLimitOne([['product_id', '=', $value->product_id]]); @endphp
					<a target="_blank" href="{{route('shop_detail_page').'/'.str_replace(' ', '-', $value->product_name).'-'.$value->product_id}}">
						<!-- <img width="50px" src="{{asset(env('URL_IMAGE').'product/thumb/'.$imgProduct->image)}}"> -->
						<!-- <br/> -->
                        {{$value->product_name}}
                    </a>
                    @if($value->size != '')
                    	<br/>
                        <b>Size : </b> {{$value->size}}
                 	@endif                               
                    @if($value->color_name != '' && $value->color_hexa != '') 
                    	<br/>
                    	<b>Color : </b> {{$value->color_name}} <div style="width: 25px; height: 25px; background: {{$value->color_hexa}}"></div><br>
                    @endif
				</td>
				<td style="text-align: center;">{{$value->qty}}</td>
				<td style="text-align: center;">{{\App\Helper\Common_helper::data_date($value->transaction_date)}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
<br/><br/>
<h4>Customer & Shipping Information</h4>
@foreach($data_customer as $customers)
<div><b>Name</b> : {{$customers->first_name.' '.$customers->last_name}}</div>
<div><b>Email</b> : {{$customers->email}}</div>
<div><b>Phone number</b> : {{$customers->phone_number}}</div>
@endforeach
@foreach($data_customer_meta as $customers)
	@if($customers->meta_key == "first_name")
		<div><b>First name</b> : {{$customers->meta_description}}</div>
	@endif
	@if($customers->meta_key == "last_name")
		<div><b>Last name</b> : {{$customers->meta_description}}</div>
	@endif
	@if($customers->meta_key == "email")
		<div><b>Email</b> : {{$customers->meta_description}}</div>
	@endif
	@if($customers->meta_key == "phone_number")
		<div><b>Phone number</b> : {{$customers->meta_description}}</div>
	@endif
@endforeach
<br/>
<div>
	<b>Send To :</b><br/>
	@foreach($shipping_data as $key)
		{{$key->country_name}}<br>
		{{$key->province_name}}<br>
		{{$key->city_name}}<br>
		@if($key->country_id == '236')
			{{$key->subdistrict_name}}<br>
		@endif
		{{$key->detail_address}}<br>
		{{$key->postal_code}}
	@endforeach
</div>
<br/><br/>
<p>Team <?=env('AUTHOR_SITE')?></p>
