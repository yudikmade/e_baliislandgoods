@extends('admin.layout.template')

@section('style')
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'iCheck/all.css')}}">
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
<link rel="stylesheet" type="text/css" href="{{asset(env('URL_ASSETS').'datepicker/datepicker3.css')}}">
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
                        <div class="col-sm-5 no-padding">
                            <div class="form-group">
                                <input type="hidden" id="urlSearch" value="{{$url_search}}" />
                                <input class="form-control no-radius" value="<?=$search?>" id="dataSearch" name="dataSearch" placeholder="Search..."/>
                            </div>
                            <div class="form-group">
                                <select type="text" class="form-control select2" name="status" id="status">
                                    <?php
                                        $dataStatus = \App\Helper\Common_helper::list_status_transaction();

                                        foreach ($dataStatus as $key => $value) 
                                        { 
                                            if($key == $status)
                                            {
                                                echo '<option value="'.$key.'" selected>'.$value.'</option>';
                                            }
                                            else
                                            {
                                                echo '<option value="'.$key.'">'.$value.'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-5 col-sm-offset-2 no-padding">
                            <div class="col-sm-12 no-padding">
                                <?php
                                    $dateStart = '';
                                    $dataFinish = '';
                                    $tmpData = explode('.', $date_transaction);
                                    if(count($tmpData) == 2)
                                    {
                                        $dateStart = $tmpData[0];
                                        $dataFinish = $tmpData[1];
                                    }

                                ?>
                                <div class="col-sm-6 no-padding-left">
                                    <div class="form-group">
                                        <input type="text" name="startDate" id="startDate" class="form-control" placeholder="Start date" value="{{$dateStart}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 no-padding-right">
                                    <div class="form-group">
                                        <input type="text" name="finishDate" id="finishDate" class="form-control" placeholder="Finish date" value="{{$dataFinish}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12" style="padding: 10px 0 10px 0; margin-bottom: 10px;">
                                <button id="btn-search" class="btn btn-success no-radius pull-right">Search <i class="fa fa-search"></i></button>
                                <button id="btn-export" data-href="{{route('control_detail_transactions_export')}}" style="margin-right: 10px;" class="btn btn-primary no-radius pull-right">Export <i class="fa fa-excel"></i></button>
                            </div>
                        </div>
                        <div class="table-responsive col-sm-12 no-padding">
                            <table id="displayData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transaction</th>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                        <th>Time</th>
                                        <th>Status Item</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php
                                        $no = ($data_result->currentpage()-1) * $data_result->perpage() + 1;
                                        foreach ($data_result as $key) 
                                        {
                                            $formatPrice = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->price);

                                            $priceDic = \App\Helper\Common_helper::set_discount(($key->price * $key->qty), $key->discount);
                                            $formatPriceTotal = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $priceDic[0]);


                                            echo '
                                                <tr>
                                                    <td>'.$no.'</td>
                                                    <td>'.$key->transaction_code.'</td>
                                                    <td>
                                                        <a target="_blank" href="'.route('control_edit_product').'/'.$key->product_id.'">
                                                            '.$key->product_name.'<br>
                                                            '.$key->product_code.'
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a target="_blank" href="'.route('control_products_sku').'/'.$key->sku_code.'">
                                                            '.$key->sku_code.'<br>
                                                        </a>';

                                                        if($key->size != '') 
                                                            echo'<b>Size : </b> '.$key->size.'<br>';
                                                            
                                                        if($key->color_name != '' && $key->color_hexa != '') 
                                                            echo'<b>Color : </b> '.$key->color_name.' <div style="width: 25px; height: 25px; background: '.$key->color_hexa.'"></div><br>';
                                            echo '
                                                    </td>
                                                    <td>'.$key->qty.'</td>
                                                    <td>'.$formatPrice[2].$formatPrice[1].' '.$formatPrice[3].'</td>
                                                    <td>';
                                                        if($key->discount != '' && $key->discount != '0') 
                                                            echo $key->discount.'%'; 
                                            echo '
                                                    </td>
                                                    <td>'.$formatPriceTotal[2].$formatPriceTotal[1].' '.$formatPriceTotal[3].'</td>
                                                    <td>'.\App\Helper\Common_helper::data_date($key->transaction_date).'</td>
                                                    <td>'.\App\Helper\Common_helper::trans_detail_status($key->status).'</td>
                                                    <td>'.\App\Helper\Common_helper::transaction_status($key->status_transaction, $key->payment_status).'</td>
                                                    <td>
                                                        <a class="btn btn-info btn-sm" title="Detail data" href="'.route('control_detail_transaction').'/'.$key->transaction_id.'"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            ';
                                            $no++;
                                        }    
                                    ?>
                                    
                                </tbody>
                            </table>
                            <div class="col-sm-12 text-center">
                                {{$data_result->links()}}
                            </div>
                            <div class="both-space-md"></div>
                            <div class="both-space-md"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'iCheck/icheck.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        $('#startDate, #finishDate').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true
        });

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
            var date = 'all-date';
            if($('#startDate').val() != '' && $('#finishDate').val() != '')
            {
                date = $('#startDate').val()+'.'+$('#finishDate').val();
            }

            var dataSearch = $('#dataSearch').val()+'-';
            dataSearch = dataSearch.replace(/ /g, "+");   
            location.href=$('#urlSearch').val()+'/'+$('#status').val()+'/'+dataSearch+'/'+date;
        }

        $('#btn-export').click(function(e){
            var date = 'all-date';
            if($('#startDate').val() != '' && $('#finishDate').val() != '')
            {
                date = $('#startDate').val()+'.'+$('#finishDate').val();
            }
            var dataSearch = $('#dataSearch').val()+'-';
            window.open($(this).attr('data-href')+'/'+$('#status').val()+'/'+dataSearch+'/'+date, 'export');
        });
    });
</script>
@stop