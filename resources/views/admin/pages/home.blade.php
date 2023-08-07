@extends('admin.layout.template')

@section('style')
<style>
.form-horizontal .control-label {
    text-align: left !important;
}
.mb-5 {
    margin-bottom: 5px !important;
}
.mb-15 {
    margin-bottom: 0px !important;
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
            <?php echo $breadcrumbs;?>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{$title_form}}</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive col-sm-12">
                            <form id="form-save-data" class="form-horizontal" action="{{route('control_home_process')}}" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="home_text" class="col-sm-2 control-label">Description</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="home_text" id="home_text">{{$home_text == '' ? '':$home_text}}</textarea>
                                                <input type="hidden" name="home_text_text" id="home_text_text" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="contact_adress" class="col-sm-12 col-md-10 control-label mb-5">Image (1)</label>
                                            <div class="col-sm-12 col-md-10 mb-15">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        @if($home_image_1 == '')
                                                            No Data Available
                                                        @else 
                                                            <img width="50%" src={{asset(env('URL_IMAGE').'home/thumb/'.$home_image_1)}} alt="" class="img-responsive" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <input class="filestyle" id="home_image_1" type="file" name="home_image_1" data-buttonName="btn-primary" data-buttonText=" Select image">
                                                        <small class="text-primary">* Format jpg|.jpeg|.png (max. size 2MB).</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="contact_adress" class="col-sm-12 col-md-10 control-label mb-5">Image (2)</label>
                                            <div class="col-sm-12 col-md-10 mb-15">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        @if($home_image_1 == '')
                                                            No Data Available
                                                        @else 
                                                            <img width="50%" src={{asset(env('URL_IMAGE').'home/thumb/'.$home_image_2)}} alt="" class="img-responsive" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <input class="filestyle" id="home_image_2" type="file" name="home_image_2" data-buttonName="btn-primary" data-buttonText=" Select image">
                                                        <small class="text-primary">* Format jpg|.jpeg|.png (max. size 2MB).</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="contact_adress" class="col-sm-12 col-md-10 control-label mb-5">Image (3)</label>
                                            <div class="col-sm-12 col-md-10 mb-15">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        @if($home_image_3 == '')
                                                            No Data Available
                                                        @else 
                                                            <img width="50%" src={{asset(env('URL_IMAGE').'home/thumb/'.$home_image_3)}} alt="" class="img-responsive" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <input class="filestyle" id="home_image_3" type="file" name="home_image_3" data-buttonName="btn-primary" data-buttonText=" Select image">
                                                        <small class="text-primary">* Format jpg|.jpeg|.png (max. size 2MB).</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="submit" name="save" id="btn-save-data" class="btn btn-primary pull-right btn-md">Save</button> 
                                        <img class="pull-right none" style="margin-top: 10px; margin-right: 10px;" id="loader" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'upload/bootstrap-filestyle.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        var company_profile = document.getElementById("home_text");
            CKEDITOR.replace(company_profile,{
            language:'en-gb'
        });

        $(":file").filestyle({buttonName: "btn-primary"});

        $("#form-save-data").validate({
            rules :{
                
            },
            messages: {
                
            },
            errorElement: 'small',
            submitHandler: function(form) {

                CKEDITOR.instances['home_text'].updateElement();
                $('#home_text_text').val(CKEDITOR.instances['home_text'].editable().getText());

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
                        if(response.trigger == "yes"){
                            toastr.success(response.notif);
                            setTimeout(function(){ window.location.reload(true); }, 500);
                        }else{
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