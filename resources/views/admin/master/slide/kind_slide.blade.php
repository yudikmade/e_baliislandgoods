@extends('admin.layout.template')

@section('style')
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'select2/select2.min.css')}}">
<style type="text/css">
    [class^='select2'] {
        border-radius: 0px !important;
    }
    .select2-container .select2-selection{
        padding-bottom: 26px;
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
                    </div>
                    <form id="form-save-data" class="form-horizontal" action="{{route('control_edit_kind_video')}}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="bs-callout bs-callout-warning">
                              Please edit kind of slide on home page.
                            </div>
                            <input type="hidden" name="action" id="action" value="kindofvideo">
                            <div class="form-group">
                                <label for="main_category" class="col-sm-2 control-label"><span class="text-danger">*</span>Kind of slide</label>
                                <div class="col-sm-6">
                                    <select type="text" class="form-control select2" name="kind" id="kind">
                                        <?php
                                            foreach ($masterKindOfSlide as $key => $value) 
                                            {
                                                if($key == $kind_data)
                                                {
                                                    echo '<option value="'.$key.'" selected>'.strtoupper($value).'</option>';
                                                }
                                                else
                                                {
                                                    echo '<option value="'.$key.'">'.strtoupper($value).'</option>';   
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="main_category" class="col-sm-2 control-label"><span class="text-danger">*</span>Show product category</label>
                                <div class="col-sm-6">
                                    <select type="text" class="form-control select2" name="show_category" id="show_category">
                                        <?php
                                            foreach ($masterDataProductCategory as $key => $value) 
                                            {
                                                if($key == $kind_data)
                                                {
                                                    echo '<option value="'.$key.'" selected>'.strtoupper($value).'</option>';
                                                }
                                                else
                                                {
                                                    echo '<option value="'.$key.'">'.strtoupper($value).'</option>';   
                                                }
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
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
        $("#form-save-data").validate({
            submitHandler: function(form) {
                $("#loader").fadeIn();
                $("#btn-save-data").attr('disabled', 'disabled');
                var formData = new FormData(form);
                $.ajax({
                    url: $("#form-save-data").attr('action'),
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