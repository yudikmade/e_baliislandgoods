@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/account.css')}}" rel="stylesheet">
<style>
    body {
		background: #f7faff;
	}
    .transaction-code{
        font-weight: bold;
        text-decoration: underline;
    }
</style>
@stop

@section('content')
<div class="container account margin-other-page">
    <div class="row mrg-tp20 no-mrg-top-mobile">
        @include('frontend.account.profile_nav')
        <div class="account-right-side col-md-9 col-sm-12 mrg-tp30 no-mrg-top-mobile">
            <div class="col-sm-12 no-pdg mrg-btm30 no-pdg">
                <h3>Transaction History</h3>
                <div class="table-responsive col-sm-12 no-pdg">
                    <div class="col-sm-12 no-pdg mrg-btm10 mrg-tp20">
                    </div>
                    <table id="displayData" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction Code</th>
                                <th>Total</th>
                                <!-- <th>Type of payment</th> -->
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                                $no = ($data_result->currentpage()-1) * $data_result->perpage() + 1;
                                foreach ($data_result as $key) 
                                {
                                    $formatPrice = \App\Helper\Common_helper::currency_transaction($key->transaction_id, $key->total_payment);

                                    echo '
                                            <td>'.$no.'</td>
                                            <td><a class="transaction-code" href="'.route('user_detail_transaction').'/'.$key->transaction_id.'">'.$key->transaction_code.'</a></td>';

                                    // echo '<td>'.$formatPrice[2].$formatPrice[1].' '.$formatPrice[3].'</td>';
                                    echo '<td>'.$formatPrice[2].$formatPrice[1].'</td>';
                                            // echo '<td>'.\App\Helper\Common_helper::type_of_payment($key->type_payment).'</td>';
                                    echo '
                                            <td>'.\App\Helper\Common_helper::data_date($key->transaction_date).'</td>
                                            <td>'.\App\Helper\Common_helper::transaction_status($key->status, $key->payment_status).'</td>
                                        </tr>
                                    ';
                                    $no++;
                                }    
                            ?>
                            
                        </tbody>
                    </table>
                </div>

                <div class="col-sm-12 mrg-tp30">
                    {{$data_result->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="featurette-divider"></div>
<div class="featurette-divider"></div>
@stop

@section('script')   
@stop