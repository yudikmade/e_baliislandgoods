@extends('admin.layout.template')

@section('style')
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'select2/select2.min.css')}}">
<style type="text/css">
    [class^='select2'] {
        border-radius: 0px !important;
    }
    .select2-container .select2-selection{
        padding-bottom: 25px;
        padding-left: 5px;
    }
</style>
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
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">{{$title_form}}</h3>
                    </div><!-- /.box-header -->
                    <form id="form-save-data" class="form-horizontal" action="{{route('control_add_product_category_process')}}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="bs-callout bs-callout-warning">
                              Please input new product catgeory the form below.
                            </div>
                            <div class="form-group">
                                <label for="fileBuku" class="col-sm-2 control-label"><span class="text-danger">*</span>Image</label>
                                <div class="col-sm-7">
                                    <input class="filestyle" id="up_image" type="file" name="up_image" data-buttonName="btn-primary" data-buttonText=" Select image">
                                    <small class="text-primary">* Format jpg|.jpeg|.png (max. size 2MB), upload all images in one size for better result (standard 1800px X 900px).</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="category" class="col-sm-2 control-label"><span class="text-danger">*</span>Category</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="category" id="category" value=""  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="category" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" id="description"></textarea>
                                </div>
                            </div>
                            <div class="form-group" style="display:none">
                                <label for="parent" class="col-sm-2 control-label"><span class="text-danger">*</span>Choose parent</label>
                                <div class="col-sm-10">
                                    <select type="text" class="form-control select2" name="parent" id="parent">
                                        <option value="">No parent</option>
                                        <?php
                                            foreach ($data_categories as $value) 
                                            {
                                                echo '<option value="'.$value->category_id.'">'.$value->category.'</option>';
                                            }
                                        ?>
                                    </select>
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
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'upload/bootstrap-filestyle.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
        $(":file").filestyle({buttonName: "btn-primary"});

        var description = document.getElementById("description");
            CKEDITOR.replace(description,{
            language:'en-gb'
        });

        $("#form-save-data").validate({
            rules :{
                category :{
                    required : true,
                }
            },
            messages: {
                category: {
                    required: 'Please input category!',
                }
            },
            errorElement: 'small',
            submitHandler: function(form) {
                $("#loader").fadeIn();
                $("#btn-save-data").attr('disabled', 'disabled');
                CKEDITOR.instances['description'].updateElement();

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
                            $('#parent').append('<option value="'+response.id+'">'+$('#category').val()+'</option>');
                            $('#category').val("");
                            $('#parent').select2('val', '');
                            CKEDITOR.instances['description'].setData("");
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