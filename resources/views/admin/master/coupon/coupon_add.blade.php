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
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">{{$title_form}}</h3>
                    </div><!-- /.box-header -->
                    <form id="form-save-data" class="form-horizontal" action="{{route('control_add_coupon_process')}}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="bs-callout bs-callout-warning">
                              Please input new coupon the form below.
                            </div>
                            <div class="form-group">
                                <label for="code" class="col-sm-2 control-label"><span class="text-danger">*</span>Coupon code</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="code" id="code" value=""  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="count" class="col-sm-2 control-label"><span class="text-danger">*</span>Amount of usage</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control currency" name="count" id="count" value=""  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="discount" class="col-sm-2 control-label"><span class="text-danger">*</span>Discount</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control currency" name="discount" id="discount" value=""/>
                                        <span class="input-group-addon display-discount" add-symbol="%"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="save" id="btn-save-data" class="btn btn-primary pull-right btn-lg">Save</button> 
                            <img class="pull-right none" style="margin-top: 18px; margin-right: 10px;" id="loader" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                        </div>
                    </form>
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
                code :{
                    required : true,
                },
                count :{
                    required : true,
                },
                discount :{
                    required : true,
                }
            },
            messages: {
                code: {
                    required: 'Please input coupon code!',
                },
                count :{
                    required: 'Please input amount of usage!',
                },
                discount :{
                    required: 'Please insert discount!',
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
                            $('#code').val('');
                            $('#count').val('');
                            $('#discount').val('');
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