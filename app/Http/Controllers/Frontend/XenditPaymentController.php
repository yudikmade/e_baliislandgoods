<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use Session;
use Validator;

use App\Helper\Common_helper;
use App\Helper\Xendit_helper;
use App\Models\EmTransaction;
use App\Models\EmTransactionDetail;
use App\Models\EmTransactionMeta;
use App\Models\EmCustomer;
use App\Models\MCurrency;
use App\Models\EmCoupon;
use App\Models\EmProduct;
use App\Models\EmTransactionShipping;

class XenditPaymentController extends Controller
{
    public function __construct(){
    }

    public function index(Request $request){
        $result['trigger'] = 'no';
        $result['notif'] = 'Transaction is not exist.';

        $validator = Validator::make(request()->all(), [
            'id' => 'required',
            'name_on_card' => 'required',
            'billing_address' => 'required',
            'card_number' => 'required',
            'card_expires' => 'required',
            'cvn_code' => 'required',
            'token_id' => 'required',
            'authentication_id' => 'required',
            'amount' => 'required',
        ],[
            'id.required' => 'Transaction is not exist.',
            'name_on_card.required' => 'Please insert name of card.',
            'billing_address.required' => 'Please insert billing address.',
            'card_number.required' => 'Please insert card number.',
            'card_expires.required' => 'Please insert expiry card.',
            'cvn_code.required' => 'Please insert cvc number.',
            'token_id.required' => 'Please insert Token.',
            'authentication_id.required' => 'Please insert Authentication.',
            'amount.required' => 'Please insert amount.'
        ]);
        
        if($validator->fails()) {
            $notif = '';
            foreach ($validator->errors()->all() as $messages) {
                $notif .= $messages.'<br>';
            }
            $result['notif'] = $notif;
        }else{
            $input = $request->all();
            
            $get_transaction = EmTransaction::where('transaction_code', $input['id'])->where('payment_status', '0')->where('status', '1')->first();
            if($get_transaction){

                $customer_email = '';
                $getUser = EmCustomer::select('email')->where('customer_id',$get_transaction->customer_id)->first();
                if($getUser){
                    $customer_email = $getUser->email;
                }

                //payment
                $doNext = true;
                $error_xendit = false;
                
                $cekPrice = EmTransactionMeta::getMeta(array('transaction_id' => $get_transaction->transaction_id, 'meta_key' => 'amount_idr'));
                if($cekPrice->meta_description != $input['amount']){
                    $doNext = false;
                }

                $result_xendit = Xendit_helper::pay($input, $input['amount']);
                if($result_xendit['status'] != '1'){
                    $doNext = false;
                    $error_xendit = true;
                }


                if($doNext){
                    $trans_id = $get_transaction->transaction_id;
                    EmTransaction::updateData($trans_id, ['payment_status' => '1','status' => '2','type_payment' => 'xendit']);
                    EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_response', 'meta_description' => json_encode($result_xendit)));
                    EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_date', 'meta_description' => gmdate('Y-m-d H:i:s')));

                    if(isset($get_transaction->transaction_code))
                    {
                        $trans_code = $get_transaction->transaction_code;
                        $unique_code = $get_transaction->unique_code;

                        $first_name = '';
                        $customer_email = '';
                        if($get_transaction->customer_id == '' || $get_transaction->customer_id == null){
                            $getFirstName = EmTransactionMeta::getMeta(array('transaction_id' => $get_transaction->transaction_id, 'meta_key' => 'first_name'));
                            $first_name = $getFirstName->meta_description;
                            $getEmailCustomer = EmTransactionMeta::getMeta(array('transaction_id' => $get_transaction->transaction_id, 'meta_key' => 'email'));
                            $customer_email = $getEmailCustomer->meta_description;
                        }else{
                            $getCustomer = EmCustomer::getWhere([['customer_id', '=', $get_transaction->customer_id]], '', false);
                            foreach ($getCustomer as $value) {
                                $first_name = $value->first_name;
                                $customer_email = $value->email;
                            }
                        }

                        $message['first_name'] = $first_name;
                        $message['transaction_code'] = $trans_code;
                        $message['unique_code'] = $unique_code;
                        // send email to customer
                        if($customer_email != ''){
                            Common_helper::send_email($customer_email, $message, 'Payment Complete #'.$trans_code, 'payment_to_customer');
                        }
                        // send email to admin
                        Common_helper::send_email(env('MAIL_REPLAY_TO'), $message, 'Someone made a payment for transaction number #'.$trans_code, 'payment_to_admin');
                    }

                    // view
                    Session::put(sha1(env('AUTHOR_SITE').'_trans_code_xendit'), $input['id']);
                    Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
                    Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));

                    $result['trigger'] = 'yes';
                    $result['notif'] = 'Payment has been successful, please wait until the process is complete.';
                    $result['direct'] = route('user_success_payment_xendit');
                } else {
                    if($error_xendit){
                        $result['notif'] = 'Credit Card Invalid.';
                    } else {
                        $result['notif'] = 'Payment not same with database, please contact customer support.';
                    }
                }
                //payment========
            }
        }

        echo json_encode($result);
	}

    // without payment
    public function free(Request $request){
        $result['trigger'] = 'no';
        $result['notif'] = 'Transaction is not exist.';

        $validator = Validator::make(request()->all(), [
            'id' => 'required'
        ],
        [
            'id.required' => 'Transaction is not exist.'
        ]);
        
        if($validator->fails()) {
            $notif = '';
            foreach ($validator->errors()->all() as $messages) {
                $notif .= $messages.'<br>';
            }
            $result['notif'] = $notif;
        }else{
            $input = $request->all();
            
            $get_transaction = EmTransaction::where('transaction_code', $input['id'])->where('payment_status', '0')->where('status', '1')->first();
            if($get_transaction){

                $customer_email = '';
                $getUser = EmCustomer::select('email')->where('customer_id',$get_transaction->customer_id)->first();
                if($getUser){
                    $customer_email = $getUser->email;
                }

                //payment
                $doNext = false;

                $_coupon = $get_transaction->coupon;
                $_total_payment = ($get_transaction->total_price + $get_transaction->shipping_cost + $get_transaction->additional_price + $get_transaction->tax);
                $_remaining = $get_transaction->coupon - $_total_payment;
                if($_remaining < 0){
                    $_remaining = 0;
                }

                $paymentData = array(
                    'payment_status' => 'Paid',
                    'description' => 'Without Payment',
                    'payment_date' => gmdate('Y-m-d H:i:s'),
                    'coupon' => $_coupon,
                    'total_payment' => $_total_payment,
                    'remaining' => $_remaining,
                );
                $result['notif'] = 'Payment not same with database, please contact customer support.';

                if($get_transaction->total_payment == 0 && $get_transaction->payment_status == 0 && $get_transaction->status == '1'){
                    $doNext = true;
                }

                if($doNext){
                    $trans_id = $get_transaction->transaction_id;
                    EmTransaction::updateData($trans_id, ['payment_status' => '1','status' => '2','type_payment' => 'xendit']);
                    EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_response', 'meta_description' => json_encode($paymentData)));
                    EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_date', 'meta_description' => gmdate('Y-m-d H:i:s')));

                    if(isset($get_transaction->transaction_code))
                    {
                        $trans_code = $get_transaction->transaction_code;
                        $unique_code = $get_transaction->unique_code;

                        $first_name = '';
                        $customer_email = '';
                        if($get_transaction->customer_id == '' || $get_transaction->customer_id == null){
                            $getFirstName = EmTransactionMeta::getMeta(array('transaction_id' => $get_transaction->transaction_id, 'meta_key' => 'first_name'));
                            $first_name = $getFirstName->meta_description;
                        }else{
                            $getCustomer = EmCustomer::getWhere([['customer_id', '=', $get_transaction->customer_id]], '', false);
                            foreach ($getCustomer as $value) 
                            {
                                $first_name = $value->first_name;
                                $customer_email = $value->email;
                            }
                        }

                        // send email to customer
                        if($customer_email != ''){
                            $message['first_name'] = $first_name;
                            $message['transaction_code'] = $trans_code;
                            $message['unique_code'] = $unique_code;
                            Common_helper::send_email($customer_email, $message, 'Payment Complete #'.$trans_code, 'payment_to_customer');
                        }

                        //order data';
                        $shipping_data = EmTransactionShipping::getWhere([['transaction_id', '=', $get_transaction->transaction_id]], '', false);
                        $getCustomer = EmCustomer::getWhere([['customer_id', '=', $get_transaction->customer_id]], '', false);
                        $getCustomerMeta = [];
                        if(sizeof($getCustomer) == 0){
                            $getCustomerMeta = EmTransactionMeta::getWhere([['transaction_id', '=', $get_transaction->transaction_id]]);
                        }
                        //order data====================

                        // send email to admin
                        $message['invoice'] = $trans_code;
                        $message['first_name'] = $first_name;
                        $message['unique_code'] = $unique_code;
                        $message['data_customer'] = $getCustomer;
                        $message['data_customer_meta'] = $getCustomerMeta;
                        $message['shipping_data'] = $shipping_data;
                        $getAdmin = EmTransactionDetail::getAdmin($trans_id);
                        foreach($getAdmin as $admin){
                            $getDetailTransaction = EmTransactionDetail::transactionDetailPerAdmin([
                                ['em_transaction_detail.transaction_id', '=', $get_transaction->transaction_id],
                            ]);
                            $message['transaction_detail'] = $getDetailTransaction;
                            Common_helper::send_email($admin->email, $message, 'Payment Complete #'.$trans_code, 'payment_to_admin');
                        }
                    }

                    // view
                    Session::put(sha1(env('AUTHOR_SITE').'_trans_code_xendit'), $input['id']);
                    Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
                    Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));

                    $result['trigger'] = 'yes';
                    $result['notif'] = 'Payment has been successful, please wait until the process is complete.';
                    $result['direct'] = route('user_success_payment_xendit');
                }
                //payment========
            }
        }

        echo json_encode($result);
	}

    public function success(Request $request){

        if(Session::get(sha1(env('AUTHOR_SITE').'_trans_code_xendit')) == null)
        {
            return redirect()->route('shop_page');
        }

        $trans_code = Session::get(sha1(env('AUTHOR_SITE').'_trans_code_xendit'));
        Session::forget(sha1(env('AUTHOR_SITE').'_trans_code_xendit'));

        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => 'Payment Complete | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'trans_code' => $trans_code,
            'is_page' => 'shop',
        );
        return view('frontend.payment_complete', $data);
    }

    public function anotherPaymentMethod(Request $request){
        $result['trigger'] = 'no';
        $result['notif'] = 'Transaction is not exist.';

        $validator = Validator::make(request()->all(), [
            'id' => 'required',
            'amount' => 'required',
        ],
        [
            'id.required' => 'Transaction is not exist.',
            'amount.required' => 'Please insert amount.'
        ]);
        
        if($validator->fails()) {
            $notif = '';
            foreach ($validator->errors()->all() as $messages) {
                $notif .= $messages.'<br>';
            }
            $result['notif'] = $notif;
        }else{
            $input = $request->all();

            $secret_key = 'Basic ' .  base64_encode(env('XENDIT_SECRET_KEY') . ':');

            $data_request = Http::withHeaders([
                'Authorization' => $secret_key
            ])->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $input['id'],
                'amount' => $input['amount'],
                'payment_methods' => [
                    'BCA', 'BNI', 'BRI', 'MANDIRI', 'CIMB', 'PERMATA', 'QRIS', 'OVO'
                ]
            ]);

            $response = $data_request->object();

            $result['trigger'] = 'yes';
            $result['notif'] = 'Link generated.';
            $result['direct'] = $response->invoice_url;
        }

        echo json_encode($result);
    }

    public function callback(Request $request){
        $data = request()->all();
        $status = $data['status'];
        $external_id = $data['external_id'];

        if(strtolower($status) == 'paid'){
            $get_transaction = EmTransaction::where('transaction_code', $external_id)->where('payment_status', '0')->where('status', '1')->first();
            if($get_transaction){
                $paymentData = $data;

                $trans_id = $get_transaction->transaction_id;
                EmTransaction::updateData($trans_id, ['payment_status' => '1','status' => '2','type_payment' => 'xendit']);
                EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_response', 'meta_description' => json_encode($paymentData)));
                EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_date', 'meta_description' => gmdate('Y-m-d H:i:s')));

                if(isset($get_transaction->transaction_code))
                {
                    $trans_code = $get_transaction->transaction_code;
                    $unique_code = $get_transaction->unique_code;

                    $first_name = '';
                    $customer_email = '';
                    if($get_transaction->customer_id == '' || $get_transaction->customer_id == null){
                        $getFirstName = EmTransactionMeta::getMeta(array('transaction_id' => $get_transaction->transaction_id, 'meta_key' => 'first_name'));
                        $first_name = $getFirstName->meta_description;
                    }else{
                        $getCustomer = EmCustomer::getWhere([['customer_id', '=', $get_transaction->customer_id]], '', false);
                        foreach ($getCustomer as $value) 
                        {
                            $first_name = $value->first_name;
                            $customer_email = $value->email;
                        }
                    }

                    // send email to customer
                    if($customer_email != ''){
                        $message['first_name'] = $first_name;
                        $message['transaction_code'] = $trans_code;
                        $message['unique_code'] = $unique_code;
                        Common_helper::send_email($customer_email, $message, 'Payment Complete #'.$trans_code, 'payment_to_customer');
                    }

                    //order data';
                    $shipping_data = EmTransactionShipping::getWhere([['transaction_id', '=', $get_transaction->transaction_id]], '', false);
                    $getCustomer = EmCustomer::getWhere([['customer_id', '=', $get_transaction->customer_id]], '', false);
                    $getCustomerMeta = [];
                    if(sizeof($getCustomer) == 0){
                        $getCustomerMeta = EmTransactionMeta::getWhere([['transaction_id', '=', $get_transaction->transaction_id]]);
                    }
                    //order data====================

                    // send email to admin
                    $message['invoice'] = $trans_code;
                    $message['first_name'] = $first_name;
                    $message['unique_code'] = $unique_code;
                    $message['data_customer'] = $getCustomer;
                    $message['data_customer_meta'] = $getCustomerMeta;
                    $message['shipping_data'] = $shipping_data;
                    $getAdmin = EmTransactionDetail::getAdmin($trans_id);
                    foreach($getAdmin as $admin){
                        $getDetailTransaction = EmTransactionDetail::transactionDetailPerAdmin([
                            ['em_transaction_detail.transaction_id', '=', $get_transaction->transaction_id]
                        ]);
                        $message['transaction_detail'] = $getDetailTransaction;
                        Common_helper::send_email($admin->email, $message, 'Payment Complete #'.$trans_code, 'payment_to_admin');
                    }
                }
            }
        }
    }
}