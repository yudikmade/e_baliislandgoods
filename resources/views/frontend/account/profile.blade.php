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
<div class="container account margin-other-page">
	<div class="row no-mrg-top-mobile">
		@include('frontend.account.profile_nav')
		<div class="col-md-9 col-sm-12 no-mrg-top-mobile">
			@foreach($profile as $key)
			<?php
				$prefix = '';
			?>
			<div class="account-right-side col-sm-12 no-pdg mrg-btm40 no-mrg-top-mobile">
				<h3>Profile Information</h3>
				<form id="form-profile-information" action="{{route('user_process_profile')}}" method="post">
					{{ csrf_field() }}
					<input type="hidden" name="current_pass" class="current_pass" id="current_pass">
					<input type="hidden" name="form_action" class="form_action" id="form_action" value="update-profile">
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="first_name">First Name</label>
									<input type="text" class="form-control" id="first_name" name="first_name" value="{{$key->first_name}}">
									<small class="notif-first_name error none"><i>Please input first name!</i></small>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="last_name">Last Name</label>
									<input type="text" class="form-control" id="last_name" name="last_name" value="{{$key->last_name}}">
									<small class="notif-last_name error none"><i>Please input last name!</i></small>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="phone_prefix">Country Code</label>
									<select style="width: 100%;" class="select2 form-control pull-left" name="phone_prefix" id="phone_prefix">
										<option value="">--Choose--</option>
										@foreach($phone_prefix as $phone)
											@if($key->country_phone_id == $phone->country_phone_id)
												<?php $prefix = $phone->phone_prefix;?>
												<option value="{{$phone->country_phone_id}}" selected>{{$phone->name}} ({{$phone->phone_prefix}})</option>
											@else
												<option value="{{$phone->country_phone_id}}">{{$phone->name}} ({{$phone->phone_prefix}})</option>
											@endif
										@endforeach
									</select> 
									<small class="notif-phone_prefix error none"><i>Please select country code!</i></small>
								</div>
							</div>
							<div class="col-sm-8">
								<div class="form-group">
									<label for="last_name">Phone Number</label>
									<input type="text" class="form-control" id="phone_number" name="phone_number" value="{{\App\Helper\Common_helper::phone_no_prefix($prefix, $key->phone_number)}}">
									<small class="notif-phone_number error none"><i>Please input phone number!</i></small>
								</div>
							</div>
							<div class="col-sm-12"><hr></div>
							<div class="col-sm-12 text-right">
								<button type="submit" class="btn btn-primary btn-md" id="btn-profile-information">Save</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="account-right-side col-sm-12 no-pdg">
				<h3>Login Information</h3>
				<div class="col-sm-12">
					<p class="subtitle">
					Leave password blank if only changing email.
					</p>
				</div>
				<form id="form-login-information" action="{{route('user_process_profile')}}" method="post">
					{{ csrf_field() }}
					<input type="hidden" name="current_pass" class="current_pass" id="current_pass">
					<input type="hidden" name="form_action" class="form_action" id="form_action" value="update-login">
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="email">Email Address</label>
									<input type="email" class="form-control" id="email" name="email" value="{{$key->email}}">
									<small class="notif-email error none"><i>Please input email address!</i></small>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="new_password">New Password</label>
									<input type="password" class="form-control" id="new_password" name="new_password">
									<small class="notif-new_password error none"><i>Please input password!</i></small>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="r_new_password">Repeat Password</label>
									<input type="password" class="form-control" id="r_new_password" name="r_new_password">
									<small class="notif-r_new_password error none"><i>Please input password!</i></small>
								</div>
							</div>
							<div class="col-sm-12"><hr></div>
							<div class="col-sm-12 text-right">
								<button type="button" class="btn btn-primary btn-md" id="btn-login-information">Save</button>
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
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
    	$('.select2').select2();

	    $("#btn-login-information").click(function(e){
	    	e.preventDefault();

	    	var email = $('#email');
	    	var new_password = $('#new_password');
	    	var r_new_password = $('#r_new_password');

	    	var triggerSubmit = true;

	    	if(new_password.val() != '' || r_new_password.val() != ''){
	    		if(r_new_password.val() != new_password.val()){
	    			triggerSubmit = false;
		    		$('.notif-r_new_password').fadeIn();
		    		r_new_password.focus();
	    		}
	    		else{
    				$('.notif-r_new_password').fadeOut();
	    			if(new_password.val() == ""){
	    				triggerSubmit = false;
			    		$('.notif-new_password').fadeIn();
			    		new_password.focus();
	    			}else{
	    				$('.notif-new_password').fadeOut();
	    			}

	    			if(r_new_password.val() == ""){
	    				triggerSubmit = false;
			    		$('.notif-r_new_password').fadeIn();
			    		r_new_password.focus();
	    			}else{
	    				$('.notif-r_new_password').fadeOut();
	    			}
	    		}
	    	}

	    	if(email.val() == ''){
	    		triggerSubmit = false;
	    		$('.notif-email').fadeIn();
	    		email.focus();
	    	}else{
	    		$('.notif-email').fadeOut();
    		}

    		if(triggerSubmit)
	    	{
	    		$('#btn-process-update').attr('data-target', 'form-login-information');
				$('#myModalCurrentPassword').modal('show');
	    	}
    	});

	    $("#btn-profile-information").click(function(e){
	    	e.preventDefault();

	    	var first_name = $('#first_name');
	    	var last_name = $('#last_name');
	    	var phone_prefix = $('#phone_prefix');
	    	var phone_number = $('#phone_number');

	    	var triggerSubmit = true;

	    	if(phone_number.val() == ''){
	    		triggerSubmit = false;
	    		$('.notif-phone_number').fadeIn();
	    		phone_number.focus();
	    	}else{
	    		$('.notif-phone_number').fadeOut();
    		}

    		if(phone_prefix.val() == ''){
	    		triggerSubmit = false;
	    		$('.notif-phone_prefix').fadeIn();
	    		phone_prefix.focus();
	    	}else{
	    		$('.notif-phone_prefix').fadeOut();
    		}

	    	if(last_name.val() == ''){
	    		triggerSubmit = false;
	    		$('.notif-last_name').fadeIn();
	    		last_name.focus();
	    	}else{
	    		$('.notif-last_name').fadeOut();
    		}

	    	if(first_name.val() == ''){
	    		triggerSubmit = false;
	    		$('.notif-first_name').fadeIn();
	    		first_name.focus();
	    	}else{
	    		$('.notif-first_name').fadeOut();
    		}

    		if(triggerSubmit)
	    	{
	    		$('#btn-process-update').attr('data-target', 'form-profile-information');
				$('#myModalCurrentPassword').modal('show');
	    	}
    	});

	    $('#current_password').keypress(function(e){
	    	if(e.which == 13)
	    	{
	    		$('#btn-process-update').click();
    		}
	    });

    	$('#btn-process-update').click(function(e){
    		var elementBtn = $(this);
    		var dataTarget = $(this).attr('data-target');
    		var current_password = $('#current_password');
    		var triggerSubmit = true;
    		if(current_password.val() == ''){
    			triggerSubmit = false;
    			current_password.focus();
    			$('.notif-current_password').fadeIn();
    		}else{
    			$('.current_pass').val(current_password.val());
    			$('.notif-current_password').fadeOut();
    		}

	    	if(triggerSubmit)
	    	{
	    		$('.loader-process').removeClass('hidden');
                elementBtn.attr('disabled', 'disabled');
                var emelentForm = $('#'+dataTarget)
                $.ajax({
                    url: emelentForm.attr('action'),
                    type: 'POST',
                    data: emelentForm.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        elementBtn.removeAttr('disabled');
                        if(response.trigger == "yes")
                        {
                        	if(dataTarget == 'form-login-information')
                        	{
                        		emelentForm.find("input[type=password]").val("");
                        	}

                        	current_password.val('');
                        	$('.current_pass').val('');
                        	$('#myModalCurrentPassword').modal('toggle');
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
        });
    });
</script>
@stop