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
                    <form id="form-save-data" class="form-horizontal" action="{{route('control_add_slide_process')}}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="bs-callout bs-callout-warning">
                              Please input new image for slide the form below.
                            </div>
                            <div class="form-group">
                                <label for="fileBuku" class="col-sm-2 control-label"><span class="text-danger">*</span>Image</label>
                                <div class="col-sm-7">
                                    <input class="filestyle" id="up_image" type="file" name="up_image" data-buttonName="btn-primary" data-buttonText=" Select image">
                                    <small class="text-primary">* Format jpg|.jpeg|.png (max. size 2MB), upload all images in one size for better result (standard 1800px X 900px).</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="url" class="col-sm-2 control-label">Link</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="url" id="url" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order" class="col-sm-2 control-label"><span class="text-danger">*</span>Order</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control currency" name="order" id="order" value="{{$new_order}}"/>
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
<script src="{{asset(env('URL_ASSETS').'upload/bootstrap-filestyle.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(":file").filestyle({buttonName: "btn-primary"});

        $("#form-save-data").validate({
            rules :{
                order :{
                    required : true,
                }
            },
            messages: {
                order :{
                    required: 'Please insert order data!',
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
                            $('.bootstrap-filestyle input:eq( 0 )').val("");
                            $('#order').val(response.order);
                            $('#url').val('');
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                        $('#loader').fadeOut();
                    },
                    error: function(data)
                    {
                        console.log(data);
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