<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use Validator;
use View;

use App\Helper\Common_helper;
use App\Models\EmTransaction;
use App\Models\EmTransactionDetail;
use App\Models\EmCustomer;
use App\Models\EmTransactionShipping;
use App\Models\EmTransactionMeta;

class TestemailController extends Controller{
    public function index(){

        //order data';
        $transaction_id = 44;
        $getDetailTransaction = EmTransactionDetail::transactionDetailPerAdmin([['em_transaction_detail.transaction_id', '=', $transaction_id]]);
        $shipping_data = EmTransactionShipping::getWhere([['transaction_id', '=', $transaction_id]], '', false);
        $getCustomer = EmCustomer::getWhere([['customer_id', '=', 'EC19040018']], '', false);
        $getCustomerMeta = EmTransactionMeta::getWhere([['transaction_id', '=', $transaction_id]]);
        //order data====================

        $message['invoice'] = "trans_code";
        $message['first_name'] = "first_name";
        $message['unique_code'] = "unique_code";
        $message['shipping_data'] = $shipping_data;
        $message['transaction_detail'] = $getDetailTransaction;
        $message['data_customer_meta'] = $getCustomerMeta;
        $message['data_customer'] = $getCustomer;

        // $viewMessage = View::make('email.header');
        // $viewMessage .= View::make('email.payment_to_admin', $message);
        // $viewMessage .= View::make('email.footer');

        // echo $viewMessage;

        //////////////////////

        $getDetailTransaction = EmTransactionDetail::transactionDetailPerAdmin([
            ['em_transaction_detail.transaction_id', '=', $transaction_id],
            ['em_product.admin_id', '=', '1']
        ]);
        $message['transaction_detail'] = $getDetailTransaction;
        Common_helper::send_email('crystal.thompson@marks.com', $message, 'Test - Payment Complete #'.$message['invoice'], 'payment_to_admin');
    }
}