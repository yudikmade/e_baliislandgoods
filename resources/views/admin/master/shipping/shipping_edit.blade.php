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
                    </div>
                    @foreach($data_result as $key)
                    <form id="form-save-data" class="form-horizontal" action="{{route('control_edit_shipping_cost_process')}}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="bs-callout bs-callout-warning">
                              Please edit shipping cost the form below.<br>
                              - Please input shipping cost in rupiah.<br>
                            </div>
                            <input type="hidden" name="shipping_cost_id" id="shipping_cost_id" value="{{$key->shipping_cost_id}}">
                            <div class="form-group">
                                <label for="category" class="col-sm-2 control-label"><span class="text-danger">*</span>Category</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="category" id="category" value="{{$key->category}}" readonly="readonly"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cost" class="col-sm-2 control-label"><span class="text-danger">*</span>Cost</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control currency" name="cost" id="cost" value="{{$key->cost}}"/>
                                        <span class="input-group-addon display-cost">
                                            {{\App\Helper\Common_helper::convert_to_format_currency($key->cost)}}
                                        </span>
                                    </div>
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
<script type="text/javascript">
    $(document).ready(function() {
        $("#form-save-data").validate({
            rules :{
                cost :{
                    required : true,
                }
            },
            messages: {
                cost: {
                    required: 'Please input shipping cost!',
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