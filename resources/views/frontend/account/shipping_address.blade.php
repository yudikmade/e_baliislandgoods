@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/account.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'select2/select2.min.css')}}">
<style type="text/css">
    [class^='select2'] {
        border-radius: 0px !important;
    }
    .select2-container .select2-selection{
        padding-bottom: 33px;
        padding-top: 3px;
        padding-left: 5px;
    }
    .input-group button.btn-default{
        background: none;
        border: 1px solid #aaa;
        color: #4d64ae;
    }
    body {
        background: #f7faff;
    }
</style>
@stop

@section('content')
<div class="container account">
    <div class="row no-mrg-top-mobile">
        @include('frontend.account.profile_nav')
        <div class="col-md-9 col-sm-12 no-mrg-top-mobile">
            @foreach($shipping_address as $key)
            <div class="account-right-side col-sm-12 no-pdg mrg-btm40 no-mrg-top-mobile">
                <h3>Shipping Address</h3>
                <input type="hidden" name="actionLocation" id="actionLocation" value="{{route('process_shipping_location')}}">
                <form id="form-shipping" action="{{route('user_process_profile')}}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="form_action" class="form_action" id="form_action" value="update-shipping">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="country">Country</label>
                                    <div class="col-sm-10">
                                        <select style="width: 100%;" class="select2 form-control" name="country" id="country">
                                            @foreach($country as $countries)
                                                @if($key->country_id == $countries->country_id)
                                                    <option value="{{$countries->country_id}}" selected>{{$countries->country_name}}</option>
                                                @else
                                                    <option value="{{$countries->country_id}}">{{$countries->country_name}}</option>
                                                @endif
                                            @endforeach
                                        </select> 
                                        <small class="notif-country error none"><i>Please choose country!</i></small>
                                    </div>
                                </div>

                                @if($key->country_id == '236')
                                <div class="form-group select-national">
                                    <label class="control-label col-sm-2" for="province">Province</label>
                                    <div class="col-sm-10">
                                        <select style="width: 100%;" class="select2 form-control" name="province" id="province">
                                            <option value="{{$key->province_id}}">{{$key->province_name}}</option>
                                        </select> 
                                        <small class="notif-province error none"><i>Please choose province!</i></small>
                                    </div>
                                </div>
                                <div class="form-group select-national">
                                    <label class="control-label col-sm-2" for="city">City</label>
                                    <div class="col-sm-10">
                                        <select style="width: 100%;" class="select2 form-control" name="city" id="city">
                                            <option value="{{$key->city_id}}">{{$key->city_name}}</option>
                                        </select> 
                                        <small class="notif-city error none"><i>Please choose city!</i></small>
                                    </div>
                                </div>
                                <div class="form-group select-national">
                                    <label class="control-label col-sm-2" for="subdistrict">Subdistrict</label>
                                    <div class="col-sm-10">
                                        <select style="width: 100%;" class="select2 form-control" name="subdistrict" id="subdistrict">
                                            <option value="{{$key->subdistrict_id}}">{{$key->subdistrict_name}}</option>
                                        </select> 
                                        <small class="notif-subdistrict error none"><i>Please choose subdistrict!</i></small>
                                    </div>
                                </div>
                                @else
                                <div class="form-group select-national none">
                                    <label class="control-label col-sm-2" for="province">State</label>
                                    <div class="col-sm-10">
                                        <select style="width: 100%;" class="select2 form-control" name="province" id="province">
                                            <option value="">Choose provinsi</option>
                                        </select> 
                                        <small class="notif-province error none"><i>Please choose state!</i></small>
                                    </div>
                                </div>
                                <div class="form-group select-national none">
                                    <label class="control-label col-sm-2" for="city">City</label>
                                    <div class="col-sm-10">
                                        <select style="width: 100%;" class="select2 form-control" name="city" id="city">
                                            <option value="">Choose city</option>
                                        </select> 
                                        <small class="notif-city error none"><i>Please choose city!</i></small>
                                    </div>
                                </div>
                                <div class="form-group select-national none">
                                    <label class="control-label col-sm-2" for="subdistrict">Subdistrict</label>
                                    <div class="col-sm-10">
                                        <select style="width: 100%;" class="select2 form-control" name="subdistrict" id="subdistrict">
                                            <option value="">Choose subdistrict</option>
                                        </select> 
                                        <small class="notif-subdistrict error none"><i>Please select subdistrict!</i></small>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <hr>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="address">Address</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control no-radius" name="address" id="address" rows="5">{{$key->detail_address}}</textarea>
                                        <small class="notif-address error none"><i>Please input address!</i></small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="postalcode">Postal Code</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control no-radius" name="postalcode" id="postalcode" value="{{$key->postal_code}}">
                                        <small class="notif-postalcode error none"><i>Please input postal code!</i></small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <hr>
                                    </div>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btn-shipping">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
<div class="featurette-divider"></div>
<div class="featurette-divider"></div>

<div id="myModalCurrentPassword" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Please enter the current password</h4>
                <button type="button" class="close btn" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="current_password">Password :</label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                    <small class="notif-current_password error none"><i>Please input password!</i></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default no-radius pull-left" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-process-update" data-target="">Save</button>
                <div class="lds-ring blue mrg-tp10 loader-process hidden pull-right" style="margin-top: 10px;"><div></div><div></div><div></div><div></div></div> 
            </div>
        </div>
    </div>
</div>
@stop

@section('script')   
<script src="{{asset(env('URL_ASSETS').'iCheck/icheck.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        $("#btn-shipping").click(function(e){
            e.preventDefault();

            var country = $('#country');
            var elementBtn = $("#btn-shipping");

            if(country.val() != '')
            {
                var triggerSubmit = true;

                if(country.val() == '236')
                {
                    var province = $('#province');
                    var city = $('#city');
                    var subdistrict = $('#subdistrict');

                    if(subdistrict.val() == ''){
                        triggerSubmit = false;
                        $('.notif-subdistrict').fadeIn();
                        subdistrict.focus();
                    }else{
                        $('.notif-subdistrict').fadeOut();
                    }

                    if(city.val() == ''){
                        triggerSubmit = false;
                        $('.notif-city').fadeIn();
                        city.focus();
                    }else{
                        $('.notif-city').fadeOut();
                    }

                    if(province.val() == ''){
                        triggerSubmit = false;
                        $('.notif-province').fadeIn();
                        province.focus();
                    }else{
                        $('.notif-province').fadeOut();
                    }
                }
                
                var address = $('#address');
                var postalcode = $('#postalcode');

                if(postalcode.val() == ''){
                    triggerSubmit = false;
                    $('.notif-postalcode').fadeIn();
                    postalcode.focus();
                }else{
                    $('.notif-postalcode').fadeOut();
                }

                if(address.val() == ''){
                    triggerSubmit = false;
                    $('.notif-address').fadeIn();
                    address.focus();
                }else{
                    $('.notif-address').fadeOut();
                }
                

                if(triggerSubmit)
                {
                    $('.loader-process').removeClass('hidden');
                    elementBtn.attr('disabled', 'disabled');
                    var emelentForm = $('#form-shipping')
                    $.ajax({
                        url: emelentForm.attr('action'),
                        type: 'POST',
                        data: emelentForm.serialize(),
                        dataType: 'json',
                        success: function(response) {
                            elementBtn.removeAttr('disabled');
                            if(response.trigger == "yes")
                            {
                                toastr.success(response.notif, '', {timeOut: 3000});
                            }
                            else
                            {
                                toastr.warning(response.notif);
                            }
                            $('.loader-process').addClass('hidden');
                        },
                        error: function()
                        {
                            elementBtn.removeAttr('disabled');
                            $('.loader-process').addClass('hidden');
                            toastr.error('There is something wrong, please refresh page and try again.');
                        }            
                    });
                }
            }
            else
            {
                $('.notif-country').fadeIn();
            }
        });

        $('#country, #province, #city').change(function(e){
            var data_id = $(this).val();
            var trigger = $(this).attr('id');

            // if($('#country').val() == '236')
            // {
            //     $('.select-national').fadeIn();
            // }
            // else
            // {
            //     if($('#country').val() != '')
            //     {
            //         $('.select-national').fadeOut();
            //     }
            // }

            $('.select-national').fadeIn();

            // if($('#country').val() == '236')
            // {
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
                                if(response.notif.length > 50){
                                    $('#province').html(response.notif);
                                } else {
                                    $('.select-national').fadeOut();   
                                }
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
            // }
        });
    });
</script>
@stop