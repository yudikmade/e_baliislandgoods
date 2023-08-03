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
                    <form id="form-save-data" class="form-horizontal" action="{{route('control_add_faq_process')}}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="bs-callout bs-callout-warning">
                              Please input new FAQ the form below.<br>
                              Input question without question mark.
                            </div>
                            <div class="form-group">
                                <label for="question" class="col-sm-2 control-label"><span class="text-danger">*</span>Question</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="question" id="question" value=""  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="answer" class="col-sm-2 control-label"><span class="text-danger">*</span>Answer</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="answer" id="answer"></textarea>
                                    <input type="hidden" name="answer_text" id="answer_text" value="">
                                </div>
                            </div>
                            <hr>
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
<script src="{{asset(env('URL_ASSETS').'ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var company_profile = document.getElementById("answer");
                CKEDITOR.replace(company_profile,{
                language:'en-gb'
            });

        $("#form-save-data").validate({
            rules :{
                question :{
                    required : true,
                }
            },
            messages: {
                question: {
                    required: 'Please input question!',
                }
            },
            errorElement: 'small',
            submitHandler: function(form) {

                CKEDITOR.instances['answer'].updateElement();
                $('#answer_text').val(CKEDITOR.instances['answer'].editable().getText());

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
                            $('#question').val('');
                            CKEDITOR.instances['answer'].setData("");
                            $('#answer_text').val("");
                            $('#order').val(response.order);
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