<?php 
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;

use App\Helper\Common_helper;
use App\Models\EmTransaction;
use App\Models\EmTransactionDetail;
use App\Models\EmTransactionMeta;
use App\Models\EmCustomer;
use App\Models\MCurrency;

class PaypalPaymentController extends Controller{
    public function __construct(){
        // sandbox or live
        define('PPL_MODE', env('PAYPAL_MODE'));
        if(PPL_MODE=='sandbox'){
            define('PPL_API_USER', env('PAYPAL_API_USER'));
            define('PPL_API_PASSWORD', env('PAYPAL_API_PASSWORD'));
            define('PPL_API_SIGNATURE', env('PAYPAL_API_SIGNATURE'));
        }else{
            define('PPL_API_USER', env('PAYPAL_API_USER_LIVE'));
            define('PPL_API_PASSWORD', env('PAYPAL_API_PASSWORD_LIVE'));
            define('PPL_API_SIGNATURE', env('PAYPAL_API_SIGNATURE_LIVE'));
        }
        
        define('PPL_LANG', 'EN');
        define('PPL_LOGO_IMG', asset(env('URL_IMAGE').'logo.png'));
        define('PPL_RETURN_URL', route('paymentPaypalProcess'));
        define('PPL_CANCEL_URL', route('paymentPaypalCancel'));
    }

    public function index(){
        if(Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')) == ''){
            return redirect()->route('cart_checkout');
        }

        $products = array();
        $getTrans = EmTransaction::getWhere([['transaction_id', '=', Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'))]], '', false);
        foreach ($getTrans as $value) {
            $getDetailTrans = EmTransactionDetail::transactionDetail([['em_transaction_detail.transaction_id', '=', Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'))]]);
            $counter = 0;
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
                    'ItemName' => $productName.' ('.$key->sku_code.')',
                    'ItemPrice' => $priceTotal,
                    'ItemQty' => $key->qty
                );

                array_push($products, $tmpData);
                $counter++;
            }
            
            $charges = [];
            $charges['TotalTaxAmount'] = $value->tax;
            $charges['ShippinCost'] = $value->shipping_cost;

            return redirect()->to(self::SetExpressCheckOut($products, $charges));
        }
        return redirect()->route('cart_checkout');
    }
    
    private function SetExpressCheckout($products, $charges, $noshipping='1'){
        $trans_id = Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
        $getTrans = EmTransaction::getWhere([['transaction_id', '=', $trans_id]], '', false);
        $getCurrency = EmTransactionMeta::getMeta(array('transaction_id' => $trans_id, 'meta_key' => 'currency_id'));
        $getCurrency = MCurrency::getWhere([['currency_id', '=', $getCurrency->meta_description]], '', false);
        $currency = $getCurrency[0]->code;
        $transaction_code = $getTrans[0]->transaction_code;
        $total_payment = $getTrans[0]->total_payment;

        //convert to USD
        if($getCurrency[0]->currency_id == '1'){
            $get_convertion = MCurrency::where('currency_id', $getCurrency[0]->currency_id)->first();
            $total_payment = round(($total_payment / $get_convertion->covertion), 2);
            $currency = "USD";
        }
        EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'amount_usd', 'meta_description' => $total_payment));
        //convert to USD================

        $nvpstr = "&PAYMENTREQUEST_0_PAYMENTACTION=". urlencode('Sale');
        $nvpstr .= "&LOCALECODE=".PPL_LANG;
        $nvpstr .= "&L_BILLINGAGREEMENTDESCRIPTION0=".urlencode("Invoice : ".$transaction_code);
        $nvpstr .= "&BRANDNAME=BALISLANDGOODS";
        $nvpstr .= "&PAYMENTREQUEST_0_AMT=". $total_payment;
        $nvpstr .= "&PAYMENTREQUEST_0_ITEMAMT=". $total_payment;
        $nvpstr .= "&PAYMENTREQUEST_0_DESC=".urlencode("Invoice : ".$transaction_code);
        $nvpstr .= "&PAYMENTREQUEST_0_CURRENCYCODE=" . urlencode($currency);
        $nvpstr .= "&L_PAYMENTREQUEST_0_NAME0=".urlencode("Invoice : ".$transaction_code);
        $nvpstr .= "&L_PAYMENTREQUEST_0_QTY0=1";
        $nvpstr .= "&L_PAYMENTREQUEST_0_AMT0=". $total_payment;

        $nvpstr .= "&RETURNURL=" . urlencode(PPL_RETURN_URL);
        $nvpstr .= "&CANCELURL=" . urlencode(PPL_CANCEL_URL);
        $nvpstr .= "&BUTTONSOURCE=" . urlencode('PP-ECWizard');
        
        
        Session::put('ppl_product', array(
            'item' => "Invoice : ".$transaction_code,
            'qty' => 1,
            'price' => $total_payment,
            'description' => "Invoice : ".$transaction_code
        ));
        Session::put('ppl_total_payment', $total_payment);
        Session::put('ppl_charges', 0);
        Session::put('ppl_currency', $currency);
        
        $httpParsedResponseAr = self::PPHttpPost('SetExpressCheckout', $nvpstr);
        //Respond according to message we receive from Paypal
        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
            $paypalmode = (PPL_MODE=='sandbox') ? '.sandbox' : '';
            $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
            return $paypalurl;
        }else{
            //error message
            Session::put('error_payment', urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));   
            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'error_message', 'meta_description' => urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])));
            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'error_meta', 'meta_description' => serialize($httpParsedResponseAr)));
            return route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code'));
        }   
    }  

    function process(){
        if($_GET['token'] !='' && $_GET['PayerID'] != ''){
            return redirect()->to(self::DoExpressCheckoutPayment());
        }
    }
    
    function DoExpressCheckoutPayment(){
        if(Session::get('ppl_total_payment') != null && Session::get('ppl_product') != null){
            
            $products = Session::get('ppl_product');
            $charges = Session::get('ppl_charges');
            
            $padata  =  '&TOKEN='.urlencode($_GET['token']);
            $padata .=  '&PAYERID='.urlencode($_GET['PayerID']);
            $padata .=  '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
            
            //set item info here, otherwise we won't see product details later  
            $padata .=  '&L_PAYMENTREQUEST_0_NAME0='.urlencode($products['item']);
            $padata .=  '&L_PAYMENTREQUEST_0_NUMBER0='.urlencode('1');
            $padata .=  '&L_PAYMENTREQUEST_0_DESC0='.urlencode($products['description']);
            $padata .=  '&L_PAYMENTREQUEST_0_AMT0='.urlencode($products['price']);
            $padata .=  '&L_PAYMENTREQUEST_0_QTY0='. urlencode($products['qty']);
            
            $padata .=  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode(Session::get('ppl_total_payment'));
            $padata .=  '&PAYMENTREQUEST_0_TAXAMT='.urlencode('0');
            $padata .=  '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode('0');
            $padata .=  '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode('0');
            $padata .=  '&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode('0');
            $padata .=  '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode('0');
            $padata .=  '&PAYMENTREQUEST_0_AMT='.urlencode(Session::get('ppl_total_payment'));
            $padata .=  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode(Session::get('ppl_currency'));
            
            //We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
            
            $httpParsedResponseAr = $this->PPHttpPost('DoExpressCheckoutPayment', $padata);
                
            //vdump($httpParsedResponseAr);

            //Check if everything went ok..
            if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
                if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
                    EmTransaction::updateData(Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), array('payment_status' => '1', 'status' => '2'));
                    EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'payment_paypal', 'meta_description' => 'Complete')); 
                }else if('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
                    EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'payment_paypal', 'meta_description' => 'Pending'));
                }
                EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'paypal_meta', 'meta_description' => serialize ($httpParsedResponseAr)));
                
                self::GetTransactionDetails();
                EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'transaction_id', 'meta_description' => urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"])));
                return route('payment_complete');
            }
            else{
                EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'payment_paypal', 'meta_description' => 'Failed')); 
                EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'paypal_meta', 'meta_description' => serialize ($httpParsedResponseAr)));
                return self::setCancel();
            }
        }
        else{
            // Request Transaction Details
            self::GetTransactionDetails();
            return self::setCancel();
        }
    }
    
    private function GetTransactionDetails(){
		// we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
        // GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
        $padata =   '&TOKEN='.urlencode($_GET['token']);
		
		$httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $padata, PPL_API_USER, PPL_API_PASSWORD, PPL_API_SIGNATURE, PPL_MODE);

		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
            // echo '<br /><b>Stuff to store in database :</b><br /><pre>';
            // print_r($httpParsedResponseAr);

            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'first_name_buyer', 'meta_description' => urldecode($httpParsedResponseAr["FIRSTNAME"])));
            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'last_name_buyer', 'meta_description' => $httpParsedResponseAr["LASTNAME"]));

            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'email_buyer', 'meta_description' => $httpParsedResponseAr["EMAIL"]));
            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'payerid', 'meta_description' => $httpParsedResponseAr["PAYERID"]));

            $getTransaction = EmTransaction::getWhereLastOne([['transaction_id', '=', Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'))]]);
            $customer_id = '';
            $transaction_code = '';
            $emailCustomer = '';
            $message['first_name'] = '';
            $message['last_name'] = '';
            $message['transaction_code'] = '';
            $message['unique_code'] =  '';
            if(isset($getTransaction->transaction_code)){
                $message['transaction_code'] = $getTransaction->transaction_code;
                $message['unique_code'] = $getTransaction->unique_code;
                $customer_id = $getTransaction->customer_id;
            }

            if($customer_id == ''){
                $getMeta = EmTransactionMeta::getMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'email'));
                if(isset($getMeta->meta_description))
                {
                    if($getMeta->meta_description != '')
                    {
                        $emailCustomer = $getMeta->meta_description;
                    }
                }

                $getMeta = EmTransactionMeta::getMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'first_name'));
                if(isset($getMeta->meta_description))
                {
                    if($getMeta->meta_description != '')
                    {
                        $message['first_name'] = $getMeta->meta_description;
                        $getMeta = EmTransactionMeta::getMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'last_name'));
                        $message['last_name'] = $getMeta->meta_description;
                    }
                }
            }else{
                $getCustomer = EmCustomer::getWhere([['customer_id', '=', $customer_id]], '', false);
                foreach ($getCustomer as $key) {
                    $emailCustomer = $key->email;
                    $message['first_name'] = $key->first_name;
                    $message['last_name'] = $key->last_name;
                }
            }
        } else  {
            //error message
            Session::put('error_payment', urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));   
            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'error_message', 'meta_description' => urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])));
            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'error_meta', 'meta_description' => serialize($httpParsedResponseAr)));
        }
	}

    public function cancel(){
        Session::put('error_payment', 'Payment failed, please try again.<br>Thank you.');  
        return redirect()->to(self::setCancel());
    }

    private function setCancel(){
        return route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code'));
    }
    
    private function PPHttpPost($methodName_, $nvpStr_) {
        // Set up your API credentials, PayPal end point, and API version.
        $API_UserName = urlencode(PPL_API_USER);
        $API_Password = urlencode(PPL_API_PASSWORD);
        $API_Signature = urlencode(PPL_API_SIGNATURE);
        
        $paypalmode = (PPL_MODE=='sandbox') ? '.sandbox' : '';

        $API_Endpoint = "https://api-3t".$paypalmode.".paypal.com/nvp";
        $version = urlencode('86');
        //$version = urlencode('109.0');
    
        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        //curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
        
        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
    
        // Set the API operation, version, and API signature in the request.
        $nvpreq = 
                "METHOD=".$methodName_.
                "&VERSION=".$version.
                "&PWD=".$API_Password.
                "&USER=".$API_UserName.
                "&SIGNATURE=".$API_Signature.
                $nvpStr_;
    
        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
    
        // Get response from the server.
        $httpResponse = curl_exec($ch);
    
        if(!$httpResponse) {
            exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
        }
    
        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);
    
        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            
            $tmpAr = explode("=", $value);
            
            if(sizeof($tmpAr) > 1) {
                
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }
    
        if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }
        
        return $httpParsedResponseAr;
    }
    public function paymentComplete(){
        if(Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')) == null){
            return redirect()->route('home_page');
        }

        $getTransaction = EmTransaction::getWhereLastOne([['transaction_id', '=', Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'))]]);
        $trans_code = '';
        if(isset($getTransaction->transaction_code))
        {
            $trans_code = $getTransaction->transaction_code;
            $unique_code = $getTransaction->unique_code;
            $trans_id = $getTransaction->transaction_id;

            $first_name = '';
            $customer_email = '';
            if($getTransaction->customer_id == '' || $getTransaction->customer_id == null){
                $getFirstName = EmTransactionMeta::getMeta(array('transaction_id' => $getTransaction->transaction_id, 'meta_key' => 'first_name'));
                $first_name = $getFirstName->meta_description;
                $getEmailCustomer = EmTransactionMeta::getMeta(array('transaction_id' => $getTransaction->transaction_id, 'meta_key' => 'email'));
                $customer_email = $getEmailCustomer->meta_description;
            }else{
                $getCustomer = EmCustomer::getWhere([['customer_id', '=', $getTransaction->customer_id]], '', false);
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

        Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
        Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));

        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => 'Payment complete | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'trans_code' => $trans_code,
        );
        return view('frontend.payment_complete', $data);
    }

}