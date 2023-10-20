<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmTransaction;
use App\Models\EmTransactionDetail;
use App\Models\EmTransactionMeta;
use App\Models\EmCustomer;
use App\Models\MCurrency;
use App\Models\EmCoupon;
use App\Models\EmProduct;
use App\Models\EmTransactionShipping;

use Cartalyst\Stripe\Stripe;

class StripePaymentController extends Controller
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
            'cc_number' => 'required',
            'cc_expiry' => 'required',
            'cc_cvc' => 'required'
        ],
        [
            'id.required' => 'Transaction is not exist.',
            'name_on_card.required' => 'Please insert name of card.',
            'billing_address.required' => 'Please insert billing address.',
            'cc_number.required' => 'Please insert card number.',
            'cc_expiry.required' => 'Please insert expiry card.',
            'cc_cvc.required' => 'Please insert cvc number.'
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
                $paymentData = array();
                        
                $currencyCode = 'CAD';
                $stripe = Stripe::make((env('STRIPE_SANDBOX')?env('STRIPE_SECRET_KEY'):env('STRIPE_SECRET_KEY_LIVE')));
                $expiryCard = explode('/',$input['cc_expiry']);

                try {
                    $token = null;
                    $customer = null;
                    $charge = null;

                    $token = $stripe->tokens()->create([
                        'card' => [
                            'number' => $input['cc_number'],
                            'exp_month' => @$expiryCard[0],
                            'exp_year' => @$expiryCard[1],
                            'cvc' => $input['cc_cvc'],
                        ],
                    ]);

                    $customer = $stripe->customers()->create([
                        'email' => $customer_email,
                        'source' => $token['id'],
                    ]);
            
                    if (isset($token['id'])) {
                        $charge = $stripe->charges()->create([
                            'currency' => $currencyCode,
                            'amount' => $get_transaction->total_payment,
                            'customer' => $customer['id'],
                        ]);
                    }

                    if($charge != null){
                        if($charge['status'] == 'succeeded') {
                            $paymentData = array(
                                'payment_status' => $charge['paid'],
                                'payment_id' => $charge['id'],
                                'payment_date' => gmdate('Y-m-d H:i:s'),
                                'payment_meta' => $charge,
                            );
                        }
                    }else{
                        $doNext = false;
                    }
            
                } catch (\Exception $e) {
                    $doNext = false;
                    if(sizeof($e->getTrace()[0]) > 0){
                        if(isset($e->getTrace()[0]['args'])){
                            $result['notif'] = $e->getTrace()[0]['args'][0];
                        }
                    }
                } catch ( \Cartalyst\Stripe\Exception\CardErrorException $e) {
                    $doNext = false;
                    $result['notif'] = 'Sorry, please try again.';
                } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                    $doNext = false;
                    $result['notif'] = 'Sorry, please try again.';
                }

                if($doNext){
                    $trans_id = $get_transaction->transaction_id;
                    EmTransaction::updateData($trans_id, ['payment_status' => '1','status' => '2','type_payment' => 'stripe']);
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
                            $getEmailCustomer = EmTransactionMeta::getMeta(array('transaction_id' => $get_transaction->transaction_id, 'meta_key' => 'email'));
                            $customer_email = $getEmailCustomer->meta_description;
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
                                ['em_product.admin_id', '=', $admin->admin_id]
                            ]);
                            $message['transaction_detail'] = $getDetailTransaction;

                            $email_admin = $admin->email;
                            // $email_admin = env('MAIL_ADMIN');

                            Common_helper::send_email($admin->email, $message, 'Payment Complete #'.$trans_code, 'payment_to_admin');
                        }
                    }

                    // view
                    Session::put(sha1(env('AUTHOR_SITE').'_trans_code_stripe'), $input['id']);
                    Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
                    Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));

                    $result['trigger'] = 'yes';
                    $result['notif'] = 'Payment has been successful, please wait until the process is complete.';
                    $result['direct'] = route('user_success_payment_stripe');
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
                    EmTransaction::updateData($trans_id, ['payment_status' => '1','status' => '2','type_payment' => 'stripe']);
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
                        //order data====================

                        // send email to admin
                        $message['invoice'] = $trans_code;
                        $message['first_name'] = $first_name;
                        $message['unique_code'] = $unique_code;
                        $message['data_customer'] = $getCustomer;
                        $message['shipping_data'] = $shipping_data;
                        $getAdmin = EmTransactionDetail::getAdmin($trans_id);
                        foreach($getAdmin as $admin){
                            $getDetailTransaction = EmTransactionDetail::transactionDetailPerAdmin([
                                ['em_transaction_detail.transaction_id', '=', $get_transaction->transaction_id],
                                ['em_product.admin_id', '=', $admin->admin_id]
                            ]);
                            $message['transaction_detail'] = $getDetailTransaction;
                            Common_helper::send_email($admin->email, $message, 'Payment Complete #'.$trans_code, 'payment_to_admin');
                        }
                    }

                    // view
                    Session::put(sha1(env('AUTHOR_SITE').'_trans_code_stripe'), $input['id']);
                    Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
                    Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));

                    $result['trigger'] = 'yes';
                    $result['notif'] = 'Payment has been successful, please wait until the process is complete.';
                    $result['direct'] = route('user_success_payment_stripe');
                }
                //payment========
            }
        }

        echo json_encode($result);
	}

    public function success(Request $request){

        if(Session::get(sha1(env('AUTHOR_SITE').'_trans_code_stripe')) == null)
        {
            return redirect()->route('shop_page');
        }

        $trans_code = Session::get(sha1(env('AUTHOR_SITE').'_trans_code_stripe'));
        Session::forget(sha1(env('AUTHOR_SITE').'_trans_code_stripe'));

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
}