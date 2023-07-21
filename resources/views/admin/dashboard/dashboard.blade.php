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
                @if(is_null(Session::get(env('SES_BACKEND_CATEGORY'))))
            	<div class="col-lg-4 col-xs-12">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{$count_customers}}</h3>
                            <p>Customers</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <a href="" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endif
                <div class="col-lg-4 col-xs-12">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{$count_products}}</h3>
                            <p>Products</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-list"></i>
                        </div>
                        <a href="" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-12">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{$count_transactions}}</h3>
                            <p>Transactions</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                        <a href="" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-12">
    		        <div style="background: #FFFFFF; padding-top: 10px;" class="col-sm-12">
                        <div class="col-sm-3 pull-right" style="padding: 0;">
                            <select name="year" id="dashboard_chart" class="form-control select2" data-url="{{route('control_dashboard')}}">
                                <?php
                                    $start = 2018;
                                    $end = date('Y');
                                    for ($i = $start; $i <= $end; $i++) 
                                    { 
                                        if($year == $i)
                                        {
                                            echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                        }
                                        else
                                        {
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div id="chartContainer" class="col-sm-12"></div>
                    </div> 
    	        </div>
                
            </div>
        </section>
    </div>
</div>
@stop

@section('script')         
<script src="{{asset(env('URL_ASSETS').'highcharts/highcharts.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'highcharts/modules/exporting.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });
    $(function () {
        $('#chartContainer').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Chart <?=$year?>'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'New Order',
                data: [<?=$js_total_transaction?>]
            },
            {
                name: 'Cancel',
                data: [<?=$js_total_transaction_cancel?>]
            },
            {
                name: 'Paid',
                data: [<?=$js_total_transaction_paid?>]
            },
            {
                name: 'Processed',
                data: [<?=$js_total_transaction_processed?>]
            }]
        });
    });

    $(document).ready(function(){
        $('#dashboard_chart').change(function(){
            location.href = $(this).attr('data-url') +'/'+ $(this).val();
        });
    });
</script>
@stop