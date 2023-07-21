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
                            <input type="hidden" name="kind_of_payment" id="kind_of_payment" value="all-payments">
                            <!-- <div class="form-group">
                                <select type="text" class="form-control select2" name="kind_of_payment" id="kind_of_payment">
                                    <?php
                                        $dataPayment = array('all-payments', 'paypal', 'bank-transfer');

                                        for ($i=0; $i < count($dataPayment); $i++) 
                                        { 
                                            if($dataPayment[$i] == $payment)
                                            {
                                                echo '<option value="'.$dataPayment[$i].'" selected>'.str_replace('-', ' ', $dataPayment[$i]).'</option>';
                                            }
                                            else
                                            {
                                                echo '<option value="'.$dataPayment[$i].'">'.str_replace('-', ' ', $dataPayment[$i]).'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div> -->
                        </div>
                        <div class="col-sm-5 col-sm-offset-2 no-padding">
                            <div class="col-sm-12 no-padding">
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
                                @if(!is_null(Session::get(env('SES_BACKEND_CATEGORY'))))
                                    <button id="btn-export" data-href="{{route('control_transactions_export')}}" style="margin-right: 10px;" class="btn btn-primary no-radius pull-right">Export <i class="fa fa-excel"></i></button>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive col-sm-12 no-padding">
                            <table id="displayData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Customer</th>
                                        <th>Transaction code</th>
                                        <th>Total</th>
                                        <!-- <th>Type of payment</th> -->
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php
                                        $no = ($data_result->currentpage()-1) * $data_result->perpage() + 1;
                                        foreach ($data_result as $key) 
                                        {
                                            $formatPrice = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->total_payment);

                                            echo '
                                                <tr>';
                                                    if($key->status == '5' || $key->status == '2')
                                                    {
                                                        echo '<td></td>';
                                                    }
                                                    else
                                                    {
                                                        echo '
                                                        <td width="20px"><input value="'.$key->transaction_id.'-'.$key->customer_id.'" type="checkbox" class="minimal" name="data'.$no.'" id="data'.$no.'"></td>';
                                                    }
                                            echo '
                                                    <td>'.$no.'</td>';
                                                    if($key->first_name != '')
                                                    {
                                                        echo '<td>'.$key->first_name.', '.$key->last_name.'</td>';
                                                    }
                                                    else
                                                    {
                                                        $customerMeta = \App\Models\EmTransactionMeta::getMeta(array('transaction_id' => $key->transaction_id, 'meta_key' => 'name'));
                                                        if($customerMeta) 
                                                        {
                                                            if(isset($customerMeta->meta_description))
                                                            {
                                                                echo '<td>'.$customerMeta->meta_description.'</td>';
                                                            }
                                                            else
                                                            {
                                                                echo '<td></td>';
                                                            }
                                                        }
                                                    }
                                            echo '
                                                    <td>'.$key->transaction_code.'</td>
                                                    <td>'.$formatPrice[2].$formatPrice[1].' '.$formatPrice[3].'</td>';
                                                    // echo '<td>'.\App\Helper\Common_helper::type_of_payment($key->type_payment).'</td>';
                                            echo '
                                                    <td>'.\App\Helper\Common_helper::data_date($key->transaction_date).'</td>
                                                    <td>'.\App\Helper\Common_helper::transaction_status($key->status, $key->payment_status).'</td>
                                                    <td>
                                                        <a class="btn btn-info btn-sm" title="Detail data" href="'.route('control_detail_transaction').'/'.$key->transaction_id.'"><i class="fa fa-eye"></i></a>
                                                        <a class="btn btn-primary btn-sm" title="Send invoice" href="'.route('control_action_transaction').'/'.$key->transaction_id.'/invoice" data-confirm="Are you sure send invoice to this transaction ?"><i class="fa fa-paper-plane"></i></a>
                                                        <a class="btn btn-danger btn-sm" title="Delete data" href="'.route('control_action_transaction').'/'.$key->transaction_id .'" data-confirm="Are you sure delete this data ?"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            ';
                                            $no++;
                                        }    
                                    ?>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th><input type="checkbox" class="minimal" name="checkAll" id="checkAll"> </th>
                                        <th colspan="7">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    {{ csrf_field() }}
                                                    <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_transaction')}}" data-status="2">Paid</a></li>
                                                    <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_transaction')}}" data-status="3">On process</a></li>
                                                    <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_transaction')}}" data-status="4">Sent</a></li>
                                                    <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_transaction')}}" data-status="5">Cancel</a></li>
                                                    <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_transaction')}}" data-status="6">Delete</a></li>
                                                </ul>
                                                <img class="none" style="margin-top: 0px; margin-right: 10px;" id="loader" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                                            </div> 
                                        </th>
                                    </tr>
                                </tfoot>
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
            location.href=$('#urlSearch').val()+'/'+$('#kind_of_payment').val()+'/'+$('#status').val()+'/'+dataSearch+'/'+date;
        }

        $('#btn-export').click(function(e){
            var date = 'all-date';
            if($('#startDate').val() != '' && $('#finishDate').val() != '')
            {
                date = $('#startDate').val()+'.'+$('#finishDate').val();
            }
            var dataSearch = $('#dataSearch').val()+'-';
            window.open($(this).attr('data-href')+'/'+$('#kind_of_payment').val()+'/'+$('#status').val()+'/'+dataSearch+'/'+date, 'export');
        });
    });
</script>
@stop