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
                    <form id="form-save-data" class="form-horizontal" action="{{route('control_process_information')}}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="bs-callout bs-callout-warning">
                              {{$title_form}}
                            </div>
                            <input type="hidden" name="meta_key" id="meta_key" value="{{$meta_key}}">
                            @if($meta_key == 'contact_us')
                            <div class="form-group">
                                <label for="information" class="col-sm-2 control-label">Latitude</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="latitude" id="latitude" value="{{$data_result_latitude == '' ? '':$data_result_latitude->meta_value}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="information" class="col-sm-2 control-label">Longitude</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="longitude" id="longitude" value="{{$data_result_longitude == '' ? '':$data_result_longitude->meta_value}}">
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <label for="information" class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="address" id="address" value="{{$data_result_address == '' ? '':$data_result_address->meta_value}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="information" class="col-sm-2 control-label">Telp.</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="telp" id="telp" value="{{$data_result_telp == '' ? '':$data_result_telp->meta_value}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="information" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="email" id="email" value="{{$data_result_email == '' ? '':$data_result_email->meta_value}}">
                                </div>
                            </div>
                            @endif
                            @if($meta_key != 'contact_us')
                            <div class="form-group">
                                <label for="information" class="col-sm-2 control-label"><span class="text-danger">*</span>{{$title_page}}</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="information" id="information">{{$data_result == '' ? '':$data_result->meta_value}}</textarea>
                                    <input type="hidden" name="information_text" id="information_text" value="">
                                </div>
                            </div>
                            @endif
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
<script src="{{asset(env('URL_ASSETS').'ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        @if($meta_key != 'contact_us')
        var company_profile = document.getElementById("information");
            CKEDITOR.replace(company_profile,{
            language:'en-gb'
        });
        @endif

        $("#form-save-data").validate({
            errorElement: 'small',
            submitHandler: function(form) {

                @if($meta_key != 'contact_us')
                CKEDITOR.instances['information'].updateElement();
                $('#information_text').val(CKEDITOR.instances['information'].editable().getText());
                @endif

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