@extends('admin.layout.template')

@section('style')
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'colorpicker/bootstrap-colorpicker.min.css')}}">
<style type="text/css">
    .colorpicker{
        border-radius: 0;
    }
</style>
@stop

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?=$title_page?>
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
                    </div>
                    <form id="form-save-data" class="form-horizontal" action="{{route('control_add_product_sku_process')}}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="bs-callout bs-callout-warning">
                              Please input SKU (Stock Keeping Unit) the form below.<br>
                              System will generate product code if you let product code blank.<br>
                            </div>
                            <input type="hidden" class="form-control" name="product_id" id="product_id" value="{{$product_id}}"  />
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="sku_code" class="col-sm-12">SKU Code</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="sku_code" id="sku_code" value=""  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stock" class="col-sm-12"><span class="text-danger">*</span>Stock</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control currency" name="stock" id="stock" value=""/>
                                            <span class="input-group-addon display-stock"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="col-sm-6 no-padding-left">
                                    <div class="form-group">
                                        <label for="color_name" class="col-sm-12">Color Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="color_name" id="color_name" value=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 no-padding-right">
                                    <div class="form-group">
                                        <label for="color" class="col-sm-12">Pick Color</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control colorpicker" autocomplete="off" name="color" id="color" value=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 no-padding-left">
                                    <div class="form-group">
                                        <label for="color_name" class="col-sm-12">Size</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="size" id="size" value=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 no-padding-right">
                                    <div class="form-group">
                                        <label for="product_name" class="col-sm-12">Order Data</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="new_order" id="new_order" value="{{$new_order}}"  />
                                        </div>
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
<script src="{{asset(env('URL_ASSETS').'colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.colorpicker').colorpicker();

        $("#form-save-data").validate({
            rules :{
                stock :{
                    required : true,
                }
            },
            messages: {
                stock: {
                    required: 'Please input stock of sku!',
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
                            $('#sku_code').val("");
                            $('#stock').val("");
                            $('#color_name').val("");
                            $('#size').val("");
                            $('#color').val("");
                            $('#new_order').val(response.new_order);
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