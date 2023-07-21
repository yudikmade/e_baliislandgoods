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
                    </div>
                    @foreach($data_result as $key)
                    <form id="form-save-data" class="form-horizontal" action="{{route('control_edit_social_media_process')}}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="bs-callout bs-callout-warning">
                              Please edit social media the form below.
                            </div>
                            <input type="hidden" name="social_id" id="social_id" value="{{$key->social_id}}">
                            <div class="form-group">
                                <label for="kind" class="col-sm-2 control-label"><span class="text-danger">*</span>Social media</label>
                                <div class="col-sm-10">
                                    <select type="text" class="form-control select2" name="kind" id="kind">
                                        <option value="">Choose social media</option>
                                        <?php
                                            foreach (\App\Helper\Common_helper::data_social_name() as $data => $value) 
                                            {
                                                if($key->social_name == $value)
                                                {
                                                    echo '<option value="'.$value.'" selected>'.$value.'</option>';
                                                }
                                                else
                                                {
                                                    echo '<option value="'.$value.'">'.$value.'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="url" class="col-sm-2 control-label"><span class="text-danger">*</span>Url</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="url" id="url" value="{{$key->social_url}}"  />
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
    </section>
</div>
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        $("#form-save-data").validate({
            rules :{
                url :{
                    required : true,
                }
            },
            messages: {
                url: {
                    required: 'Please input url of social media!',
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