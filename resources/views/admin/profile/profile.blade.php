@extends('admin.layout.template')

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
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="{{asset(env('URL_IMAGE').'user.png')}}" alt="User profile picture">
                        @foreach($data_profile as $key)
                        <h3 class="profile-username text-center">{{$key->full_name}}</h3>
                        <p class="text-muted text-center">Administrator</p>
                        <ul class="list-group list-group-unbordered">
                            <!-- <li class="list-group-item">
                                <b>Registered since </b> <a class="pull-right">{{Session::get(env('SES_BACKEND_REGISTERED'))}}</a>
                            </li> -->
                            <li class="list-group-item">
                                <b>Last update</b> <a class="pull-right last-update">{{\App\Helper\Common_helper::registerd_date($key->last_update)}}</a>
                            </li>
                        </ul>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab">Information</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="profile">
                            @foreach($data_profile as $key)
                            <form id="form-save-data" class="form-horizontal" action="{{route('control_profile_process')}}" method="post">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="bs-callout bs-callout-warning">
                                        If you don't want to change the password, please leave the password form blank
                                    </div>
                                    <div class="form-group">
                                        <label for="full_name" class="col-sm-3 control-label"><span class="text-danger">*</span>Full name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="full_name" id="full_name" value="{{$key->full_name}}"  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="col-sm-3 control-label"><span class="text-danger">*</span>Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="email" id="email" value="{{$key->email}}"  />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="new_password" class="col-sm-3 control-label">New password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="new_password" id="new_password" value=""  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="rnew_password" class="col-sm-3 control-label">Retype new password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="rnew_password" id="rnew_password" value=""  />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="current_password" class="col-sm-3 control-label"><span class="text-danger">*</span>Current password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="current_password" id="current_password" value=""  />
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" name="save" id="btn-save-data" class="btn btn-primary pull-right btn-lg">Save</button> 
                                    <img class="pull-right none" style="margin-top: 18px; margin-right: 10px;" id="loader" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                                </div>
                            </form>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $("#form-save-data").validate({
            rules :{
                full_name :{
                    required : true,
                },
                email :{
                    required : true,
                },
                rnew_password : {
                    equalTo : "#new_password"
                },
                current_password : {
                    required : true,
                }
            },
            messages: {
                category: {
                    required: 'Please input full name!',
                },
                email: {
                    required: 'Please input email!',
                },
                rnew_password: {
                    equalTo: 'New password is not match!',
                },
                current_password : {
                    required : 'Please insert current password!',
                }
            },
            errorElement: 'small',
            submitHandler: function(form) {
                $("#loader").fadeIn();
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
                            $('#current_password').val('');
                            $('#new_password').val('');
                            $('#rnew_password').val('');

                            $('.last-update').text(response.last_update);
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                        $('#loader').fadeOut();
                    },
                    error: function()
                    {
                        $("#btn-save-data").removeAttr('disabled');
                        $('#loader').fadeOut();
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
        });
    });
</script>
@stop