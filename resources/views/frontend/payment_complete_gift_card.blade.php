@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/shop.css')}}" rel="stylesheet">
<style>
    img {
        width: 150px;
        height: auto;
        margin-bottom: 30px; 
    }
    h3 {
        margin-bottom: 30px; 
    }
    p.last {
        margin-bottom: 30px; 
    }
    b {
        color: #003B73;
    }
    a.btn {
        margin-bottom: 70px;
    }
</style>
@stop

@section('content')
<div class="container checkout">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6" align="center">
            <img src="{{asset(env('URL_IMAGE').'success.png')}}" alt="success" />
            <h3>Payment Successful</h3>
            <p>Thank you for shopping at {{env('AUTHOR_SITE')}}</p>
            <p>Your gift card already sent to: <b>{{$email_gift_card}}</b></p>
            <hr/>
            <a class="btn btn-primary" href="{{url('/shop')}}">CONTINUE SHOPPING</a>
        </div>
    </div>
</div>
@stop

@section('script')
@stop
