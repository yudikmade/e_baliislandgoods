@extends('admin.layout.template')

@section('style')
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'iCheck/all.css')}}">
@stop

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            {{$title_page}}
            <small></small>
        </h1>
            <ol class="breadcrumb">
            <?=$breadcrumbs?>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#detail" data-toggle="tab">Detail Information</a></li>
                        @if(Session::get(env('SES_BACKEND_CATEGORY')) != '1')
                        <li><a href="#update" data-toggle="tab">Upadate Status</a></li>
                        @endif
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="detail">
                            <?php $type_payment = ''; $statusTrans = '';?>
                            @foreach($data_transaction as $key)
                            <?php 
                                $statusTrans = $key->status;
                                $type_payment = $key->type_payment;
                            ?>
                            <form id="form-save-data-top" class="form-horizontal" method="post">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="bs-callout bs-callout-warning">
                                        Detail information of transaction.
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Transaction info</h3>
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="col-sm-3 control-label">Trans. code : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                <b>{{$key->transaction_code}}</b>
                                            </div>
                                        </div>
                                        <?php
                                            $formatSubTotal = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->total_price);

                                            $formatShipping = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->shipping_cost);

                                            $formatTotal = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->total_payment);

                                            $formatTax = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->tax);

                                            $formatCoupon = \App\Helper\Common_helper::currency_transaction($key->transaction_id, ($key->coupon+0));

                                            $couponData = \App\Helper\Common_helper::getCouponTransaction($key->transaction_id);
                                        ?>
                                        @if(Session::get(env('SES_BACKEND_CATEGORY')) != '1')
                                        <div class="form-group">
                                            <label for="code_product" class="col-sm-3 control-label">Sub total : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                {{$formatSubTotal[2].$formatSubTotal[1].' '.$formatSubTotal[3]}}
                                            </div>
                                        </div>
                                        @endif
                                        @if(Session::get(env('SES_BACKEND_CATEGORY')) != '1')
                                        <div class="form-group">
                                            <label for="code_product" class="col-sm-3 control-label">Coupon Disc. : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                {{$formatCoupon[2].$formatCoupon[1].' '.$formatCoupon[3]}} 

                                                @foreach($couponData as $value)
                                                    <br>
                                                    {{'Coupon code : '.$value->coupon_code}} ({{$value->discount.'%'}})
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                        @if(Session::get(env('SES_BACKEND_CATEGORY')) != '1')
                                        <div class="form-group">
                                            <label for="code_product" class="col-sm-3 control-label">Shipping cost : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                {{$formatShipping[2].$formatShipping[1].' '.$formatShipping[3]}}
                                            </div>
                                        </div>
                                        @endif
                                        @if(Session::get(env('SES_BACKEND_CATEGORY')) != '1')
                                        @if($formatSubTotal[4] == '1')
                                        <div class="form-group">
                                            <label for="code_product" class="col-sm-3 control-label">Payment code : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                {{$key->additional_price}}
                                            </div>
                                        </div>
                                        @endif
                                        @endif
                                        @if(Session::get(env('SES_BACKEND_CATEGORY')) != '1')
                                        <div class="form-group">
                                            <label for="code_product" class="col-sm-3 control-label">Tax : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                {{$formatTax[2].$formatTax[1].' '.$formatTax[3]}}
                                            </div>
                                        </div>
                                        @endif
                                        @if(Session::get(env('SES_BACKEND_CATEGORY')) != '1')
                                        <div class="form-group">
                                            <label for="code_product" class="col-sm-3 control-label">Total payment : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                {{$formatTotal[2].$formatTotal[1].' '.$formatTotal[3]}} <small class="text-primary">(Amount to be paid)</small>
                                            </div>
                                        </div>
                                        @endif
                                        <!-- <div class="form-group">
                                            <label for="code_product" class="col-sm-3 control-label">Type of payment : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                <?=\App\Helper\Common_helper::type_of_payment($key->type_payment)?>
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <label for="code_product" class="col-sm-3 control-label">Status : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                <?=\App\Helper\Common_helper::transaction_status($key->status, $key->payment_status)?>
                                            </div>
                                        </div>
                                        <!-- <hr>
                                        <div class="form-group">
                                            <label for="code_product" class="col-sm-3 control-label">Transaction ID : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                <?php
                                                    if(isset($paypal['transaction_id']->meta_description))
                                                    {
                                                        echo $paypal['transaction_id']->meta_description;
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="code_product" class="col-sm-3 control-label">Payment : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                <?php
                                                    if(isset($paypal['payment_status']->meta_description))
                                                    {
                                                        echo $paypal['payment_status']->meta_description;
                                                    }
                                                ?>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="col-sm-6">
                                        @foreach($data_customer as $customers)
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Customer info</h3>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_name" class="col-sm-3 control-label">Name : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                <a target="_blank" href="{{route('control_customers')}}/{{str_replace(' ', '+',$customers->customer_name)}}">{{$customers->first_name.' '.$customers->last_name}}</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_name" class="col-sm-3 control-label">Email : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                <a href="mailto:{{$customers->email}}">{{$customers->email}}</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_name" class="col-sm-3 control-label">Phone number : </label>
                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                <a href="tel:{{$customers->phone_number}}">{{$customers->phone_number}}</a>
                                            </div>
                                        </div>
                                        <br><br>
                                        @endforeach

                                        @if(sizeof($data_customer) == 0)
                                            @foreach($data_customer_meta as $customers)

                                                @if($customers->meta_key == 'name')
                                                <div class="form-group">
                                                    <label for="product_name" class="col-sm-3 control-label">Name : </label>
                                                    <div class="col-sm-9" style="padding-top: 7px;">
                                                        {{$customers->meta_description}}
                                                    </div>
                                                </div>
                                                @endif
                                                @if($customers->meta_key == 'email')
                                                <div class="form-group">
                                                    <label for="product_name" class="col-sm-3 control-label">Email : </label>
                                                    <div class="col-sm-9" style="padding-top: 7px;">
                                                        <a href="mailto:{{$customers->meta_description}}">{{$customers->meta_description}}</a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($customers->meta_key == 'phone_number')
                                                <div class="form-group">
                                                    <label for="product_name" class="col-sm-3 control-label">Phone number : </label>
                                                    <div class="col-sm-9" style="padding-top: 7px;">
                                                        <a href="tel:{{$customers->meta_description}}">{{$customers->meta_description}}</a>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-sm-12"><hr></div>
                                    <div class="col-sm-12">
                                        @if(sizeof($data_shipping) == 0)
                                            <div class="alert alert-danger">
                                                There is no shipping address. Please send invoice to customer, if there the customer has not enter email address yet, please cancel or delete this transaction.
                                            </div>
                                        @endif
                                        @foreach($data_shipping as $shippings)
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Shipping info</h3>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <label for="product_name" class="col-sm-4 control-label">Packet : </label>
                                                        <div class="col-sm-8" style="padding-top: 7px;">
                                                            {{$shippings->shipping_packet}}
                                                        </div>
                                                    </div>
                                                    @if($shippings->shipping_description != '')
                                                    <div class="form-group">
                                                        <label for="product_name" class="col-sm-4 control-label"></label>
                                                        <div class="col-sm-8" style="padding-top: 7px;">
                                                            {{$shippings->shipping_description}}
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="form-group">
                                                        <label for="product_name" class="col-sm-4 control-label">Shipping estimate : </label>
                                                        <div class="col-sm-8" style="padding-top: 7px;">
                                                            {{$shippings->shipping_estimate}} day(s)
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-7">
                                                    <div class="form-group">
                                                        <label for="product_name" class="col-sm-3 control-label">Region : </label>
                                                        <div class="col-sm-9" style="padding-top: 7px;">
                                                            {{$shippings->country_name}}
                                                        </div>
                                                    </div>
                                                    @if($shippings->province_name)
                                                        <div class="form-group">
                                                            <label for="product_name" class="col-sm-3 control-label">City : </label>
                                                            <div class="col-sm-9" style="padding-top: 7px;">
                                                                {{$shippings->province_name}}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="form-group">
                                                        <label for="product_name" class="col-sm-3 control-label">City : </label>
                                                        <div class="col-sm-9" style="padding-top: 7px;">
                                                            {{$shippings->city_name}}
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="product_name" class="col-sm-3 control-label">Subdistrict : </label>
                                                        <div class="col-sm-9" style="padding-top: 7px;">
                                                            {{$shippings->subdistrict_name}}
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="product_name" class="col-sm-3 control-label">Address : </label>
                                                        <div class="col-sm-9" style="padding-top: 7px;">
                                                            {{$shippings->detail_address}}
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="product_name" class="col-sm-3 control-label">Zip/Post Code : </label>
                                                        <div class="col-sm-9" style="padding-top: 7px;">
                                                            {{$shippings->postal_code}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="table-responsive col-sm-12">
                                    <div class="bs-callout bs-callout-warning">
                                        Detail of transaction.
                                    </div>
                                    <table id="displayData" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>SKU</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>#</th>
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
                                                                <a target="_blank" href="'.route('control_edit_product').'/'.$key->product_id.'">
                                                                    '.$key->product_name.'<br>
                                                                    '.$key->product_code.'<br>
                                                                    <img width="50px" src="'.asset(env('URL_IMAGE').'product/thumb/'.$imgProduct->image).'">
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a target="_blank" href="'.route('control_products_sku').'/'.$key->sku_code.'">
                                                                    '.$key->sku_code.'<br>
                                                                </a>';

                                                                if($key->size != '') 
                                                                    echo'<b>Size : </b> '.$key->size.'<br>';
                                                                    
                                                                if($key->color_name != '' && $key->color_hexa != '') 
                                                                    echo'<b>Color : </b> '.$key->color_name.' <div style="width: 25px; height: 25px; background: '.$key->color_hexa.'"></div><br>';
                                                    echo '
                                                            </td>
                                                            <td>'.$key->qty.'</td>
                                                            <td>'.$formatPrice[2].$formatPrice[1].' '.$formatPrice[3].'</td>
                                                            <td>';
                                                                if($key->discount != '' && $key->discount != '0') 
                                                                    echo $key->discount.'%'; 
                                                    echo '
                                                            </td>
                                                            <td>'.$formatPriceTotal[2].$formatPriceTotal[1].' '.$formatPriceTotal[3].'</td>
                                                            <td>'.\App\Helper\Common_helper::trans_detail_status($key->status).'</td>
                                                            <td>';
                                                                if($statusTrans == '1')
                                                                {
                                                                    echo '
                                                                        <a class="btn btn-danger btn-sm" title="Cancel data" href="'.route('control_action_transaction_detail').'/'.$key->detail_id.'" data-confirm="Are you sure cancel this data ?"><i class="fa fa-close"></i></a>';
                                                                }
                                                    echo '
                                                            </td>
                                                        </tr>
                                                    ';
                                                    $no++;
                                                }    
                                            ?>
                                            
                                        </tbody>
                                    </table>
                                    <div class="both-space-md"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if(Session::get(env('SES_BACKEND_CATEGORY')) != '1')
                        <div class="tab-pane" id="update">
                            @foreach($data_transaction as $dataDetail)
                            <form id="form-save-data" class="form-horizontal" action="{{route('control_action_transaction')}}" method="post">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="bs-callout bs-callout-warning">
                                        Please update status transaction here.
                                    </div>
                                    <input type="hidden" name="transaction_id" id="transaction_id" value="{{$dataDetail->transaction_id}}">
                                    <input type="hidden" name="form_action" id="update-data" value="update-data">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Current status : </label>
                                        <div class="col-sm-10" style="padding-top: 7px;">
                                            <?=\App\Helper\Common_helper::transaction_status($dataDetail->status, $dataDetail->payment_status)?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status" class="col-sm-2 control-label"><span class="text-danger">*</span>Status</label>
                                        <div class="col-sm-10" style="padding-top: 8px;">
                                            <?php
                                                $dataStatus = \App\Helper\Common_helper::list_status_transaction();
                                                foreach ($dataStatus as $key => $value) 
                                                { 
                                                    if(!in_array($key, array('all-status', '0', '6')))
                                                    {
                                                        if($dataDetail->status == $key)
                                                        {
                                                            echo '
                                                                <input type="radio" class="minimal" name="status" id="status'.$value.'" value="'.$key.'" checked/> 
                                                                <label for="status'.$value.'">'.$value.'</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            ';
                                                        }
                                                        else
                                                        {
                                                            echo '
                                                                <input type="radio" class="minimal" name="status" id="status'.$value.'" value="'.$key.'"/> 
                                                                <label for="status'.$value.'">'.$value.'</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            ';
                                                        }
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-10" style="padding-top: 7px;">
                                            <input value="1" type="checkbox" class="minimal" name="send-email" id="send-email"> check if you want to send notification to customer when you update status of transaction.
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" name="save" id="btn-save-data" class="btn btn-primary pull-right btn-lg">Save</button> 
                                    <img class="pull-right none" style="margin-top: 18px; margin-right: 10px;" id="loader-save" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                                </div>
                            </form>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'iCheck/icheck.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        if(window.location.hash) 
        {
            var hash =window.location.hash.substring(1);
            $('.nav-tabs li').removeClass('active');
            $('.tab-pane').removeClass('active');
            if(hash == 'detail')
            {
                $('.nav-tabs li:first').addClass('active');
                $('#detail').addClass('active');
            }
            else
            {
                $('.nav-tabs li:eq(1)').addClass('active');
                $('#update').addClass('active');
            }
        }

        $('.nav-tabs li a').click(function(e){
            var hash = $(this).attr('href');
            location.hash = hash;
        })

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        $("#form-save-data").validate({
            rules :{
            },
            messages: {
            },
            errorElement: 'small',
            submitHandler: function(form) {
                $("#loader-save").fadeIn();
                $("#btn-save-data").attr('disabled', 'disabled');
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#btn-save-data").removeAttr('disabled');
                        if(response.trigger == "yes")
                        {
                            toastr.success(response.notif);
                            $('input[name=send-email]').iCheck('uncheck');
                            setTimeout(function(){ location.reload(); }, 4000);
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                        $('#loader-save').fadeOut();
                    },
                    error: function()
                    {
                        $("#btn-save-data").removeAttr('disabled');
                        $('#loader-save').fadeOut();
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
        });
    });
</script>
@stop