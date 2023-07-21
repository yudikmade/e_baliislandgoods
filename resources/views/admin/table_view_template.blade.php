@extends('admin.layout.template')

@section('style')
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'iCheck/all.css')}}">
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'colorpicker/bootstrap-colorpicker.min.css')}}">
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'select2/select2.min.css')}}">
<style type="text/css">
    .colorpicker{
        border-radius: 0;
    }
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
                        <div class="bs-callout bs-callout-warning">
                              {{$information}}
                        </div>
                        @if(isset($url_search))
                        <div class="col-sm-5 no-padding"></div>
                        <div class="col-sm-5 col-sm-offset-2">
                            <div class="col-sm-12 no-padding">
                                <input type="hidden" id="urlSearch" value="{{$url_search}}" />
                                <input class="form-control no-radius" value="<?=$search?>" id="dataSearch" name="dataSearch" placeholder="Search..."/>
                            </div>
                            <div class="col-sm-12" style="padding: 10px 0 10px 0; margin-bottom: 10px;">
                                <button id="btn-search" class="btn btn-success no-radius pull-right">Search <i class="fa fa-search"></i></button>
                                @if(!is_null(Session::get(env('SES_BACKEND_CATEGORY'))))
                                    @if(isset($url_export))
                                        <button id="btn-export" data-href="{{$url_export}}" style="margin-right: 10px;" class="btn btn-primary no-radius pull-right">Export <i class="fa fa-excel"></i></button>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endif
                        <?=$view_content?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('admin.product.modal_edit_sku');
@include('admin.customer.modal_detail_customer');
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'iCheck/icheck.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        $('.colorpicker').colorpicker({format: 'hex'});

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        $('#btn-search').click(function(){
            searchData();
        });

        $('#dataSearch').keypress(function(e){
            if(e.which==13)
            {
                searchData();
            }
        });
        function searchData()
        {
            var dataSearch = $('#dataSearch').val();
            dataSearch = dataSearch.replace(/ /g, "+");   
            location.href=$('#urlSearch').val()+'/'+dataSearch;
        }

        $('#btn-export').click(function(){
            var dataSearch = $('#dataSearch').val();
            dataSearch = dataSearch.replace(/ /g, "+");   
            window.open($(this).attr('data-href')+'/'+dataSearch);
        });

        $('#send-mesasage').click(function(e){
            $('#form-message').submit();
        });

        $("#form-message").validate({
            submitHandler: function(form) {
                $('.loader-reset').removeClass('hidden');
                $("#send-mesasage").attr('disabled', 'disabled');
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#send-mesasage").removeAttr('disabled');
                        if(response.trigger == "yes")
                        {
                            toastr.success(response.notif);

                            $('.displayData-message').prepend(''+
                                '<tr>'+
                                    '<td colspan="3" class="text-center">new send message</td>'+
                                    '<td><a href="'+response.admin_detail+'">'+$('#subject').val()+'</a></td>'+
                                    '<td width="180px">'+response.date+'</td>'+
                                    '<td>'+
                                        '<a class="btn btn-info btn-sm" title="Detail data" href="'+response.admin_detail+'/0"><i class="fa fa-eye"></i></a>'+
                                        '<a class="btn btn-danger btn-sm" title="Delete data" href="'+response.admin_delete+'" data-confirm="Are you sure delete this data ?"><i class="fa fa-trash"></i></a>'+
                                    '</td>'+
                                '</tr>'
                            );

                            // $('#customer_id').select2('val', '');
                            $('#subject').val('');
                            $('#message').val('');

                            $('#myModal').modal('toggle');
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                        $('.loader-reset').addClass('hidden');
                    },
                    error: function()
                    {
                        $("#send-mesasage").removeAttr('disabled');
                        $('.loader-reset').addClass('hidden');
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
        });
    });
</script>
@stop