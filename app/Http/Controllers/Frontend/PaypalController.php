<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;

use App\Helper\Common_helper;
use App\Models\EmTransaction;
use App\Models\EmTransactionDetail;
use App\Models\EmTransactionMeta;
use App\Models\MCurrency;

class PaypalPaymentController extends Controller{
    public function __construct()
    {
        // sandbox or live
        define('PPL_MODE', 'live');
        if(PPL_MODE=='sandbox'){
            // define('PPL_API_USER', 'dikyudikos-facilitator_api1.gmail.com');
            // define('PPL_API_PASSWORD', 'JYX8MTYG7R8ZSCSZ');
            // define('PPL_API_SIGNATURE', 'AtOmrBk8QY9itNabxaTf2-HfoA3wAAu2RFws7jN.7.x.kHtf1a21Gnne');

            define('PPL_API_USER', 'emeraleskateboarding_api1.gmail.com');
            define('PPL_API_PASSWORD', '5JTJUH8QBK4H2XBU');
            define('PPL_API_SIGNATURE', 'AyU.Q95u.--MjTS1t7iCNIWfPuYFAHYltc3asYv0r1uUuYVTbYOP2kDD');
        }else{
            // define('PPL_API_USER', 'dikyudikos-facilitator_api1.gmail.com');
            // define('PPL_API_PASSWORD', 'JYX8MTYG7R8ZSCSZ');
            // define('PPL_API_SIGNATURE', 'AtOmrBk8QY9itNabxaTf2-HfoA3wAAu2RFws7jN.7.x.kHtf1a21Gnne');

            define('PPL_API_USER', 'emeraleskateboarding_api1.gmail.com');
            define('PPL_API_PASSWORD', 'WK3JP2AB7XSSRMUT');
            define('PPL_API_SIGNATURE', 'ApgU2jischpV1V242h9peBX8wGfaAU0S9H9tVMdGQdfU5zgxysSrBGu3');
        }
        
        define('PPL_LANG', 'EN');
        define('PPL_LOGO_IMG', asset(env('URL_IMAGE').'logo_blue_bg.png'));
        define('PPL_RETURN_URL', route('user_process_payment'));
        define('PPL_CANCEL_URL', route('user_cancel_payment'));
    }

    public function index(){
        if(Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')) == ''){
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
        
        foreach ($getTrans as $value) 
        {
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
            
            //------------------SetExpressCheckOut-------------------
            //We need to execute the "SetExpressCheckOut" method to obtain paypal token
            $this->SetExpressCheckOut($products, $charges);   
        }
    }

    // function GetItemTotalPrice($item){
    
    //     //(Item Price x Quantity = Total) Get total amount of product;
    //     return $item['ItemPrice'] * $item['ItemQty']; 
    // }
    
    // function GetProductsTotalAmount($products){
    
    //     $ProductsTotalAmount=0;

    //     foreach($products as $p => $item){
            
    //         $ProductsTotalAmount = $ProductsTotalAmount + $this -> GetItemTotalPrice($item);    
    //     }
        
    //     return $ProductsTotalAmount;
    // }
    
    // function GetGrandTotal($products, $charges){
        
    //     //Grand total including all tax, insurance, shipping cost and discount
        
    //     $GrandTotal = $this -> GetProductsTotalAmount($products);
        
    //     foreach($charges as $charge){
            
    //         $GrandTotal = $GrandTotal + $charge;
    //     }
        
    //     return $GrandTotal;
    // }
    
    function SetExpressCheckout($products, $charges, $noshipping='1')
    {
        $trans_id = Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
        $getTrans = EmTransaction::getWhere([['transaction_id', '=', $trans_id]], '', false);
        $getCurrency = EmTransactionMeta::getMeta(array('transaction_id' => $trans_id, 'meta_key' => 'currency_id'));
        $getCurrency = MCurrency::getWhere([['currency_id', '=', $getCurrency->meta_description]], '', false);
        $currency = $getCurrency[0]->code;
        $transaction_code = $getTrans[0]->transaction_code;
        $total_payment = $getTrans[0]->total_payment;

        $nvpstr = "&PAYMENTREQUEST_0_PAYMENTACTION=". urlencode('Sale');
        $nvpstr .= "&LOCALECODE=".PPL_LANG;
        $nvpstr .= "&L_BILLINGAGREEMENTDESCRIPTION0=".urlencode("Invoice : ".$transaction_code);
        $nvpstr .= "&BRANDNAME=EMERALE";
        $nvpstr .= "&PAYMENTREQUEST_0_AMT=". $total_payment;
        // $nvpstr .= "&PAYMENTREQUEST_0_TAXAMT=0";
        $nvpstr .= "&PAYMENTREQUEST_0_ITEMAMT=". $total_payment;
        $nvpstr .= "&PAYMENTREQUEST_0_DESC=".urlencode("Invoice : ".$transaction_code);
        // $nvpstr .= "&PAYMENTREQUEST_0_CUSTOM=custom1";
        $nvpstr .= "&PAYMENTREQUEST_0_CURRENCYCODE=" . urlencode($currency);
        // $nvpstr .= "&L_PAYMENTREQUEST_0_NUMBER0=1";
        $nvpstr .= "&L_PAYMENTREQUEST_0_NAME0=".urlencode("Invoice : ".$transaction_code);
        // $nvpstr .= "&L_PAYMENTREQUEST_0_DESC0=description";
        $nvpstr .= "&L_PAYMENTREQUEST_0_QTY0=1";
        $nvpstr .= "&L_PAYMENTREQUEST_0_AMT0=". $total_payment;
        // $nvpstr .= "&L_PAYMENTREQUEST_0_TAXAMT0=0";

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


        // $nvpStr = "&PAYMENTREQUEST_0_AMT=".$total_payment;
        // $nvpStr = $nvpStr . "&PAYMENTREQUEST_0_PAYMENTACTION=" .urlencode("SALE");
        // $nvpStr = $nvpStr . "&L_BILLINGTYPE0=MerchantInitiatedBilling";
        // $nvpStr = $nvpStr . "&L_BILLINGAGREEMENTDESCRIPTION0=".urlencode("Invoice : ".$transaction_code);
        // $nvpStr = $nvpStr . "&RETURNURL=" . urlencode(PPL_RETURN_URL);
        // $nvpStr = $nvpStr . "&CANCELURL=" . urlencode(PPL_CANCEL_URL);
        // $nvpStr = $nvpStr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . urlencode($currency);

        //new
        // $nvpStr .=  '&L_PAYMENTREQUEST_0_NAME0='.urlencode($getTrans[0]->transaction_code);
        //$nvpStr .=    '&L_PAYMENTREQUEST_0_DESC0='.urlencode($item['ItemDesc']);
        // $nvpStr .=  '&L_PAYMENTREQUEST_0_AMT0='.urlencode($getTrans[0]->total_payment);
        // $nvpStr .=  '&L_PAYMENTREQUEST_0_QTY0='. urlencode('1');

        // $padata = '';
        // $padata .=  '&PAYMENTREQUEST_0_TAXAMT='.urlencode($getTrans[0]->tax);
        // $padata .=  '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($getTrans[0]->shipping_cost);
        //$padata .=    '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode('0');
        // $padata .=  '&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode('0');
        //$padata .=    '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode('0');
        // $padata .=  '&PAYMENTREQUEST_0_AMT='.urlencode($getTrans[0]->total_payment);
        // $padata .=  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($currency);
        /////

        //$padata = "&METHOD=" . urlencode('SetExpressCheckout'); 
        //$padata = $padata . "&VERSION=" . urlencode('86');
        //$padata = $padata . "&PWD=" . urlencode($this->API_Password);
        //$padata = $padata . "&USER=" . urlencode($this->API_UserName);
        //$padata = $padata . "&SIGNATURE=" . urlencode($this->API_Signature);
        // $padata = $padata . $nvpStr;
        // $padata = $padata . "&BUTTONSOURCE=" . urlencode('PP-ECWizard');
        ////////////



        //Parameters for SetExpressCheckout, which will be sent to PayPal
        // $version = urlencode('109.0');
        // $nvpreq = "METHOD=$methodName_
        // VERSION=$version
        // PWD=$API_Password
        // USER=$API_UserName
        // SIGNATURE=$API_Signature$nvpStr_

        // $padata  =   '&METHOD=SetExpressCheckout';
        
        // $padata .=   '&RETURNURL='.urlencode(PPL_RETURN_URL);
        // $padata .=   '&CANCELURL='.urlencode(PPL_CANCEL_URL);
        // $padata .=   '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
        
        // foreach($products as $p => $item){
            
        //  $padata .=  '&L_PAYMENTREQUEST_0_NAME'.$p.'='.urlencode($item['ItemName']);
        //  $padata .=  '&L_PAYMENTREQUEST_0_NUMBER'.$p.'='.urlencode($item['ItemNumber']);
        //  $padata .=  '&L_PAYMENTREQUEST_0_DESC'.$p.'='.urlencode($item['ItemDesc']);
        //  $padata .=  '&L_PAYMENTREQUEST_0_AMT'.$p.'='.urlencode($item['ItemPrice']);
        //  $padata .=  '&L_PAYMENTREQUEST_0_QTY'.$p.'='. urlencode($item['ItemQty']);
        // }        

        // /* 
        
        // //Override the buyer's shipping address stored on PayPal, The buyer cannot edit the overridden address.
        
        // $padata .=   '&ADDROVERRIDE=1';
        // $padata .=   '&PAYMENTREQUEST_0_SHIPTONAME=J Smith';
        // $padata .=   '&PAYMENTREQUEST_0_SHIPTOSTREET=1 Main St';
        // $padata .=   '&PAYMENTREQUEST_0_SHIPTOCITY=San Jose';
        // $padata .=   '&PAYMENTREQUEST_0_SHIPTOSTATE=CA';
        // $padata .=   '&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=US';
        // $padata .=   '&PAYMENTREQUEST_0_SHIPTOZIP=95131';
        // $padata .=   '&PAYMENTREQUEST_0_SHIPTOPHONENUM=408-967-4444';
        
        // */
                    
        // $padata .=   '&NOSHIPPING='.$noshipping; //set 1 to hide buyer's shipping address, in-case products that does not require shipping
                    
        // $padata .=   '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($this -> GetProductsTotalAmount($products));
        
        // $padata .=   '&PAYMENTREQUEST_0_TAXAMT='.urlencode($charges['TotalTaxAmount']);
        // $padata .=   '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($charges['ShippinCost']);
        // $padata .=   '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($charges['HandalingCost']);
        // $padata .=   '&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($charges['ShippinDiscount']);
        // $padata .=   '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($charges['InsuranceCost']);
        // $padata .=   '&PAYMENTREQUEST_0_AMT='.urlencode($this->GetGrandTotal($products, $charges));
        // $padata .=   '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode(PPL_CURRENCY_CODE);
        
        // //paypal custom template
        
        // $padata .=   '&LOCALECODE='.PPL_LANG; //PayPal pages to match the language on your website;
        // $padata .=   '&LOGOIMG='.PPL_LOGO_IMG; //site logo
        // $padata .=   '&CARTBORDERCOLOR=FFFFFF'; //border color of cart
        // $padata .=   '&ALLOWNOTE=1';
                    
        // ############# set session variable we need later for "DoExpressCheckoutPayment" #######
        
        // $_SESSION['ppl_products'] =  $products;
        // $_SESSION['ppl_charges']     =  $charges;
        
        $httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $nvpstr);
        
        //Respond according to message we receive from Paypal
        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){

            $paypalmode = (PPL_MODE=='sandbox') ? '.sandbox' : '';
        
            //Redirect user to PayPal store with Token received.
            
            $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
            
            header('Location: '.$paypalurl);
        }
        else
        {
            //error message
            Session::put('error_payment', urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));   
            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'error_message', 'meta_description' => urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])));
            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'error_meta', 'meta_description' => serialize($httpParsedResponseAr)));

            header('Location: '.route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code')));
        }   
    }  
    
    function DoExpressCheckoutPayment(){
        if(Session::get('ppl_total_payment') != null && Session::get('ppl_product') != null){
            
            $products=Session::get('ppl_product');
            
            $charges=Session::get('ppl_charges');
            
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
                
                // echo serialize ($httpParsedResponseAr);
                // echo '<h2>Success</h2>';
                // echo 'Your Transaction ID : '.urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
                
                if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
                    
                    EmTransaction::updateData(Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), array('payment_status' => '1', 'status' => '2'));
                    EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'payment_paypal', 'meta_description' => 'Complete')); 
                }
                elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
                    EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'payment_paypal', 'meta_description' => 'Pending'));
                    // echo '<div style="color:red">Transaction Complete, but payment may still be pending! '.
                    // 'If that\'s the case, You can manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
                }
                
                EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'paypal_meta', 'meta_description' => serialize ($httpParsedResponseAr)));
                
                $this->GetTransactionDetails();
                EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'transaction_id', 'meta_description' => urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"])));
                header('Location: '.route('payment_complete'));
            }
            else{
                EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'payment_paypal', 'meta_description' => 'Failed')); 
                EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'paypal_meta', 'meta_description' => serialize ($httpParsedResponseAr)));
                self::cancel();
            }
        }
        else{
            
            // Request Transaction Details
            $this->GetTransactionDetails();
            self::cancel();
        }
    }
    
        
    function GetTransactionDetails()
    {
		// we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
        // GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
        $padata =   '&TOKEN='.urlencode($_GET['token']);
		
		$httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $padata, PPL_API_USER, PPL_API_PASSWORD, PPL_API_SIGNATURE, PPL_MODE);

		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        {
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
            if(isset($getTransaction->transaction_code))
            {
                $message['transaction_code'] = $getTransaction->transaction_code;
                $message['unique_code'] = $getTransaction->unique_code;
                $customer_id = $getTransaction->customer_id;
            }

            if($customer_id == '')
            {
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
            }
            else
            {
                $getCustomer = EmCustomer::getWhere([['customer_id', '=', $customer_id]], '', false);
                foreach ($getCustomer as $key) 
                {
                    $emailCustomer = $key->email;
                    $message['first_name'] = $key->first_name;
                    $message['last_name'] = $key->last_name;
                }
            }
        } 
        else  
        {
            //error message
            Session::put('error_payment', urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));   
            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'error_message', 'meta_description' => urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])));
            EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'error_meta', 'meta_description' => serialize($httpParsedResponseAr)));
            header('Location: '.route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code')));
        }
	}

    public function cancel()
    {
        Session::put('error_payment', 'Payment failed, please repeat the payment process using paypal.<br>Thank you.');   
        header('Location: '.route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code')));
    }
            
    function process()
    {
        if($_GET['token'] !='' && $_GET['PayerID'] != '')
        {
            self::DoExpressCheckoutPayment();
        }
        
        // // we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
        // // GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
        // $padata =   '&TOKEN='.urlencode($_GET['token']);
        
        // $httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $padata, PPL_API_USER, PPL_API_PASSWORD, PPL_API_SIGNATURE, PPL_MODE);

        // if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        // {
        //     // echo '<br /><b>Stuff to store in database :</b><br /><pre>';
        //     print_r($httpParsedResponseAr);

        //     EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'first_name_buyer', 'meta_description' => urldecode($httpParsedResponseAr["FIRSTNAME"])));
        //     EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'last_name_buyer', 'meta_description' => $httpParsedResponseAr["LASTNAME"]));

        //     EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'email_buyer', 'meta_description' => $httpParsedResponseAr["EMAIL"]));
        //     EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'payerid', 'meta_description' => $httpParsedResponseAr["PAYERID"]));

        //     EmTransaction::updateData(Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), array('payment_status' => '1', 'status' => '2'));

        //     $getTransaction = EmTransaction::getWhereLastOne([['transaction_id', '=', Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id'))]]);
        //     $customer_id = '';
        //     $transaction_code = '';
        //     $emailCustomer = '';
        //     $message['first_name'] = '';
        //     $message['last_name'] = '';
        //     $message['transaction_code'] = '';
        //     $message['unique_code'] =  '';
        //     if(isset($getTransaction->transaction_code))
        //     {
        //         $message['transaction_code'] = $getTransaction->transaction_code;
        //         $message['unique_code'] = $getTransaction->unique_code;
        //         $customer_id = $getTransaction->customer_id;
        //     }

        //     if($customer_id == '')
        //     {
        //         $getMeta = EmTransactionMeta::getMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'email'));
        //         if(isset($getMeta->meta_description))
        //         {
        //             if($getMeta->meta_description != '')
        //             {
        //                 $emailCustomer = $getMeta->meta_description;
        //             }
        //         }

        //         $getMeta = EmTransactionMeta::getMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'first_name'));
        //         if(isset($getMeta->meta_description))
        //         {
        //             if($getMeta->meta_description != '')
        //             {
        //                 $message['first_name'] = $getMeta->meta_description;
        //                 $getMeta = EmTransactionMeta::getMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'last_name'));
        //                 $message['last_name'] = $getMeta->meta_description;
        //             }
        //         }
        //     }
        //     else
        //     {
        //         $getCustomer = EmCustomer::getWhere([['customer_id', '=', $customer_id]], '', false);
        //         foreach ($getCustomer as $key) 
        //         {
        //             $emailCustomer = $key->email;
        //             $message['first_name'] = $key->first_name;
        //             $message['last_name'] = $key->last_name;
        //         }
        //     }

        //     if($emailCustomer != '' && sizeof($message) > 0)
        //     {
        //         Common_helper::send_email($emailCustomer, $message, 'Payment complete '.$message['transaction_code'].' | '.env('AUTHOR_SITE'), 'payment_complete');
        //     }

        //     // header('Location: '.route('payment_complete'));
        // } 
        // else  
        // {
        //     //error message
        //     Session::put('error_payment', urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));   
        //     EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'error_message', 'meta_description' => urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])));
        //     EmTransactionMeta::updateMeta(array('transaction_id' => Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_id')), 'meta_key' => 'error_meta', 'meta_description' => serialize($httpParsedResponseAr)));
        //     header('Location: '.route('cart_checkout').'/'.Session::get(sha1(env('AUTHOR_SITE').'_payment_trans_code')));
        // }
    }
    
    private function PPHttpPost($methodName_, $nvpStr_) 
    {
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
    
        if(!$httpResponse) 
        {
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
    
        if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) 
        {
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

            $first_name = '';
            if($getTransaction->customer_id == '' || $getTransaction->customer_id == null){
                $getFirstName = EmTransactionMeta::getMeta(array('transaction_id' => $getTransaction->transaction_id, 'meta_key' => 'first_name'));
                $first_name = $getFirstName->meta_description;
            }else{
                $getCustomer = EmCustomer::getWhere([['customer_id', '=', $getTransaction->customer_id]], '', false);
                foreach ($getCustomer as $value) 
                {
                    $first_name = $value->first_name;
                }
            }
            $message['invoice'] = $trans_code;
            $message['first_name'] = $first_name;
            $message['total_payment'] = $getTransaction->total_payment;
            $getCurrency = EmTransactionMeta::getMeta(array('transaction_id' => $getTransaction->transaction_id, 'meta_key' => 'currency_id'));
            if($getCurrency){
                $checkCurrency = MCurrency::getWhere([['currency_id', '=', $getCurrency->meta_description]], '', false);
                $message['total_payment'] = $checkCurrency[0]->symbol.$getTransaction->total_payment.' '.$checkCurrency[0]->code;
            }
            Common_helper::send_email(env('MAIL_REPLAY_TO'), $message, 'PAYPAL - Payment for transaction '.$trans_code, 'payment_paypal');
        }

        Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
        Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));

        $data = array(
            'title' => 'Payment complete | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'trans_code' => $trans_code,
        );
        return view('frontend.payment_complete', $data);
    }

}