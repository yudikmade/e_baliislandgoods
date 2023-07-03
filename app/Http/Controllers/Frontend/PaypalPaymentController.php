<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use Validator;
use Srmklive\PayPal\Services\ExpressCheckout;

use App\Helper\Common_helper;
use App\Models\EmTransaction;
use App\Models\EmTransactionDetail;
use App\Models\EmTransactionMeta;
use App\Models\EmTransactionShipping;
use App\Models\EmCustomer;
use App\Models\MCurrency;
use App\Models\EmProduct;
use App\Models\EmCoupon;

class PaypalPaymentController extends Controller
{
    
    public function index()
    {
        if(Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')) == '')
        {
            return redirect()->route('cart_checkout');
        }

        $products = array();
        $getTrans = EmTransaction::getWhere([['transaction_id', '=', Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'))]], '', false);

        //check limit time payment
        $transaction_id = Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
        $getTimezone = EmTransactionMeta::getMeta(array('transaction_id' => $transaction_id, 'meta_key' => 'timezone'));

        if(isset($getTimezone->meta_description))
        {
            $transDate = '';
            foreach ($getTrans as $value) 
            {
                $transDate = $value->transaction_date;
            }

            $getTimerTrans = \App\Helper\Common_helper::timerCheckout($transDate, $getTimezone->meta_description);
            if(!$getTimerTrans['limit'])
            {
                Session::put('error_payment', 'Time of payment has expired.<br>Thank you.');   
                return redirect()->route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code'));
            }
        }

        //check shipping only for canada
        $getCountry = EmTransactionShipping::where('transaction_id',$transaction_id)->first();
        if(isset($getCountry->shipping_id)){
            if($getCountry->country_id != 30){
                Session::put('error_payment', 'Sorry, shipping not available.<br>Thank you.');   
                return redirect()->route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code'));
            }
        } else {
            Session::put('error_payment', 'Sorry, shipping not available.<br>Thank you.');   
            return redirect()->route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code'));
        }

        $products = array();
        foreach ($getTrans as $value) 
        {
            $getDetailTrans = EmTransactionDetail::transactionDetail([['em_transaction_detail.transaction_id', '=', Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'))]]);
            foreach ($getDetailTrans as $key) 
            {
                $productName = $key->product_name;
                if($key->product_name == '')
                {
                    $productName = $key->product_code;
                }

                $priceTotal = $key->price - ($key->price * $key->discount / 100);
                $priceTotal = Common_helper::convert_to_current_currency($priceTotal);

                $tmpData = array(
                    'name' => $productName.' ('.$key->sku_code.')',
                    'price' => $priceTotal[0],
                    'qty' => $key->qty
                );

                array_push($products, $tmpData);
            }
        }

        $data = [];
        // $data['items'] = $products;
        $data['items'] = [];
        $data['invoice_id'] = $getTrans[0]->transaction_code;
        $data['invoice_description'] = "Order #".$getTrans[0]->transaction_code." Invoice";
        $data['return_url'] = route('user_success_payment');
        $data['cancel_url'] = route('user_cancel_payment');
        $data['total'] = $getTrans[0]->total_payment;

        try {
            $provider = new ExpressCheckout;
  
            $response = $provider->setExpressCheckout($data);

            return redirect($response['paypal_link']);
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->route('user_cancel_payment');
        }
    }

    public function cancel()
    {
        Session::put('error_payment', 'Payment failed, please repeat the payment process using paypal.<br>Thank you.');   
        Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
        Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));
        // header('Location: '.route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code')));
        return redirect()->route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code'));
    }

    public function success(Request $request)
    {
        $provider = new ExpressCheckout;

        $response = $provider->getExpressCheckoutDetails($request->token);
  
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            if(Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')) == null)
            {
                return redirect()->route('shop_page');
            }

            $trans_id = Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'));

            EmTransaction::updateData($trans_id, ['payment_status' => '1','status' => '2','type_payment' => 'paypal']);
            EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_response', 'meta_description' => json_encode($response)));
            EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_date', 'meta_description' => gmdate('Y-m-d H:i:s')));

            $getTransaction = EmTransaction::getWhereLastOne([['transaction_id', '=', Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'))],['transaction_code','=',$response['INVNUM']]]);
            $trans_code = '';
            if(isset($getTransaction->transaction_code))
            {
                $trans_code = $getTransaction->transaction_code;
                $unique_code = $getTransaction->unique_code;

                $first_name = '';
                $customer_email = '';
                if($getTransaction->customer_id == '' || $getTransaction->customer_id == null){
                    $getFirstName = EmTransactionMeta::getMeta(array('transaction_id' => $getTransaction->transaction_id, 'meta_key' => 'first_name'));
                    $first_name = $getFirstName->meta_description;
                }else{
                    $getCustomer = EmCustomer::getWhere([['customer_id', '=', $getTransaction->customer_id]], '', false);
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
                $shipping_data = EmTransactionShipping::getWhere([['transaction_id', '=', $getTransaction->transaction_id]], '', false);
                $getCustomer = EmCustomer::getWhere([['customer_id', '=', $getTransaction->customer_id]], '', false);
                $getCustomerMeta = [];
                if(sizeof($getCustomer) == 0){
                    $getCustomerMeta = EmTransactionMeta::getWhere([['transaction_id', '=', $getTransaction->transaction_id]]);
                }
                //order data====================

                // send email to admin
                $message['invoice'] = $trans_code;
                $message['first_name'] = $first_name;
                $message['shipping_data'] = $shipping_data;
                $message['data_customer'] = $getCustomer;
                $message['data_customer_meta'] = $getCustomerMeta;
                $getAdmin = EmTransactionDetail::getAdmin($trans_id);
                foreach($getAdmin as $admin){
                    $getDetailTransaction = EmTransactionDetail::transactionDetailPerAdmin([
                        ['em_transaction_detail.transaction_id', '=', $getTransaction->transaction_id],
                        ['em_product.admin_id', '=', $admin->admin_id]
                    ]);
                    $message['transaction_detail'] = $getDetailTransaction;
                    $email_admin = $admin->email;
                    // $email_admin = env('MAIL_ADMIN');
                    Common_helper::send_email($admin->email, $message, 'Payment Complete #'.$trans_code, 'payment_to_admin');
                }
            }
            
            // view
            Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
            Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));

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
  
        dd('Something is wrong.');
    }

    public function indexGiftCard(Request $request)
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';

        $input = $request->all();

        $validator = Validator::make(request()->all(), [
            'idGiftCard' => 'required',
            'emailGiftCard' => 'required|email',
        ],
        [
            'idGiftCard.required' => 'Please input gift card.',
            'emailGiftCard.required' => 'Please input email address.',
            'emailGiftCard.email' => 'Incorrect e-mail format.',
        ]);
        
        if($validator->fails()) 
        {
            $notif = '';
            foreach ($validator->errors()->all() as $messages) 
            {
                $notif .= $messages.'<br>';
            }
            $result['notif'] = $notif;
        }
        else
        {
            $getPrice = EmProduct::where('product_id',$input['idGiftCard'])->where('category_id','3')->where('status',1)->first();
            if(isset($getPrice->product_id)){
                $data = [];
                $data['items'] = [];
                $data['invoice_id'] = '';
                $data['invoice_description'] = "Gift Card $".$getPrice->price." to ".$input['emailGiftCard'];
                $data['return_url'] = route('user_success_payment_gift_card');
                $data['cancel_url'] = route('user_cancel_payment_gift_card');
                $data['total'] = $getPrice->price;

                try {
                    $provider = new ExpressCheckout;
        
                    $response = $provider->setExpressCheckout($data);

                    Session::put(sha1(env('AUTHOR_SITE').'_id_gift_card'), $input['idGiftCard']);
                    Session::put(sha1(env('AUTHOR_SITE').'_email_gift_card'), $input['emailGiftCard']);

                    $result['trigger'] = 'yes';
                    $result['notif'] = 'Redirect to paypal.';
                    $result['paypal_link'] = $response['paypal_link'];
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->route('user_cancel_payment_gift_card');
                }
            }
        }

        echo json_encode($result);
    }

    public function cancelGiftCard()
    {
        Session::put('error_payment_gift_card', 'Payment failed, please repeat the payment process using paypal.<br>Thank you.');   
        return redirect()->route('gift_card');
    }

    public function successGiftCard(Request $request)
    {
        $provider = new ExpressCheckout;

        $response = $provider->getExpressCheckoutDetails($request->token);
  
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {

            if(Session::get(sha1(env('AUTHOR_SITE').'_id_gift_card')) == null)
            {
                return redirect()->route('gift_card');
            }

            $email = Session::get(sha1(env('AUTHOR_SITE').'_email_gift_card'));
            $coupon = 'coupon-'.$response['PAYERID'];
            $expired = gmdate("Y-m-d H:i:s", strtotime('+ 12 MONTH'));

            // save coupon
            $dataCoupon = new EmCoupon;
            $dataCoupon->coupon_code = $coupon;
            $dataCoupon->use_count = 1;
            $dataCoupon->discount = $response['AMT'];
            $dataCoupon->status = 1;
            $dataCoupon->expired = $expired;
            $dataCoupon->save();

            // save transaction 
            $dataSave = new EmTransaction;
            $dataSave->transaction_code = Common_helper::create_invoice_number();
            $dataSave->total_price = $response['AMT'];
            $dataSave->shipping_cost = 0;
            $dataSave->additional_price = 0;
            $dataSave->tax = 0;
            $dataSave->total_payment = $response['AMT'];
            $dataSave->type_payment = 'paypal';
            $dataSave->payment_status = 1;
            $dataSave->status = 2;
            $dataSave->transaction_date = strtotime(gmdate('Y-m-d H:i:s'));
            if($dataSave->save()){
                $trans_id = $dataSave->transaction_id;

                $dataDetail = new EmTransactionDetail;
                $dataDetail->transaction_id = $trans_id;
                $dataDetail->product_id = Session::get(sha1(env('AUTHOR_SITE').'_id_gift_card'));
                $dataDetail->qty = 1;
                $dataDetail->price = $response['AMT'];
                $dataDetail->status = 1;
                $dataDetail->save();

                EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_response', 'meta_description' => json_encode($response)));
                EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_date', 'meta_description' => gmdate('Y-m-d H:i:s')));
                EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'email_recipient', 'meta_description' => $email));
            }

            // view
            Session::forget(sha1(env('AUTHOR_SITE').'_id_gift_card'));
            Session::forget(sha1(env('AUTHOR_SITE').'_email_gift_card'));

            // send email
            $current_currency = Common_helper::get_current_currency();
            $giftCardDefault = Common_helper::convert_to_current_currency($response['AMT']);

            $message['first_name'] = $email;
            $message['coupon'] = $coupon;
            $message['price'] = $current_currency[1].$giftCardDefault[1].' '.$current_currency[2];
            Common_helper::send_email($email, $message, 'Gift Card', 'gift_card');

            $data = array(
                'share_page' => array(
                    'description' => env('META_DESCRIPTION'),
                    'keyword' => env('META_KEYWORD'),
                    'title' => env('AUTHOR_SITE'),
                    'image' => asset(env('URL_IMAGE').'logo.png')
                ),
                'title' => 'Payment Complete | '.env('AUTHOR_SITE'),
                'description' => env('META_DESCRIPTION'),
                'email_gift_card' => $email,
                'is_page' => 'shop',
            );
            return view('frontend.payment_complete_gift_card', $data);

            // dd($response);
        }

        dd('Something is wrong.');
    }
}