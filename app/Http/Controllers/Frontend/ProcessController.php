<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Midtrans\CreateSnapTokenService;
use Illuminate\Http\Request;
use Session;
use Auth;
use Cookie;
use View;
use Validator;

use App\Models\EmSubscribe;
use App\Models\MCurrency;
use App\Models\EmProduct;
use App\Models\EmProductImg;
use App\Models\EmProductSku;
use App\Models\MCountry;
use App\Models\MProvince;
use App\Models\MCity;
use App\Models\MSubdistrict;
use App\Models\MShippingCostDefault;
use App\Models\EmTransaction;
use App\Models\EmTransactionDetail;
use App\Models\EmTransactionMeta;
use App\Models\EmTransactionShipping;
use App\Models\EmConfig;
use App\Models\EmCoupon;
use App\Models\EmCustomer;
use App\Models\EmCustomerShipping;
use App\Helper\Common_helper;

class ProcessController extends Controller
{
    public function process(Request $request, $action = '', $id = '')
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';

        if($action != '' && $id != '')
        {
            switch ($action) {
                case 'change-currency':
                        //check data
                        $checkData = MCurrency::getWhere([['currency_id', '=', $id], ['status', '=', '1']], '', false);
                        if(sizeof($checkData) > 0)
                        {
                            Session::put(env('SES_GLOBAL_CURRENCY'), $checkData[0]->currency_id);
                        }

                        $input = $request->all();
                        if($input['from-js'] != null)
                        {
                            $result['trigger'] = 'yes';
                            $result['notif'] = 'Currency has been changed.';
                            echo json_encode($result);
                        }
                        else
                        {
                            return redirect()->route('shop_page');
                        }
                    break;
                
                default:
                    
                    break;
            }
        }
        else
        {
            $input = $request->all();

            if($input['form_action'] == 'input-subscribe')
            {
                $validator = Validator::make(request()->all(), [
                    'emailSubscribe' => 'required|email',
                ],
                [
                    'emailSubscribe.required' => 'Please input email address.',
                    'emailSubscribe.email' => 'Incorrect e-mail format.',
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
                    //check email exist
                    if(EmSubscribe::getWhereCount([['email', '=', $input['emailSubscribe']]], '') == 0)
                    {
                        $dataInsert = 
                        [
                            'email' => $input['emailSubscribe'],
                            'status' => '1'
                        ];
                        EmSubscribe::insertData($dataInsert);
                        $result['trigger'] = 'yes';
                        $result['notif'] = 'Thank you for being part of us.';
                    }
                    else
                    {
                        $result['notif'] = 'Email already registered.';
                    }
                }
            }

            echo json_encode($result);
        }
    }

    public function cart(Request $request)
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';
        
        $validator = Validator::make(request()->all(), [
            'product_id' => 'required',
            'qty' => 'required',
        ],
        [
            'product_id.required' => 'Sorry, the server was unable to process your request.',
            'qty.required' => 'Select the number of products to order.',
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
            $input = $request->all();
            //check product available

            $getDataSKU = array();
            if(!isset($input['color']) && !isset($input['size']))
            {
                $getDataSKU = EmProductSku::getWhere(
                    [
                        ['product_id', '=', $input['product_id']], 
                        ['status', '=', '1'], 
                    ], '(stock > 0)', false);
            }
            else if(isset($input['color']) && isset($input['size']))
            {
                $getDataSKU = EmProductSku::getWhere(
                    [
                        ['product_id', '=', $input['product_id']], 
                        ['status', '=', '1'], 
                        ['size', '=', $input['size']],
                        ['color_hexa', '=', $input['color']]
                    ], '', false);
            }
            else
            {
                if(isset($input['size']) && !isset($input['color']))
                {
                    $getDataSKU = EmProductSku::getWhere(
                        [
                            ['product_id', '=', $input['product_id']], 
                            ['status', '=', '1'], 
                            ['size', '=', $input['size']],
                        ], '', false);
                }
                else
                {
                    $getDataSKU = EmProductSku::getWhere(
                        [
                            ['product_id', '=', $input['product_id']], 
                            ['status', '=', '1'], 
                            ['color_hexa', '=', $input['color']],
                        ], '', false);
                }
            }

            if(sizeof($getDataSKU) > 0)
            {
                if(($input['qty']+0) > 0)
                {
                    if($getDataSKU[0]->stock < $input['qty'])
                    {
                        if(!isset($input['color']) && !isset($input['size']))
                        {
                            $result['notif'] = 'Sorry, available stock only '.$getDataSKU[0]->stock.'.';
                        }
                        else if(isset($input['color']) && isset($input['size']))
                        {
                            $getOtherStock = EmProductSku::getWhere(
                                [
                                    ['product_id', '=', $input['product_id']], 
                                    ['status', '=', '1'], 
                                    ['size', '=', $input['size']],
                                ], '', false);

                            $tmpData = '';
                            foreach ($getOtherStock as $key) 
                            {
                                $tmpData .= 'Color '.$key->color_name.' : '.$key->stock.'<br>';
                            }

                            $result['notif'] = '
                                Sorry, available stock only '.$getDataSKU[0]->stock.'.
                                <br><br>
                                Other stock with different colors:
                                <br>'.$tmpData;
                        }
                        else
                        {
                            if(isset($input['size']) && !isset($input['color']))
                            {
                                $getOtherStock = EmProductSku::getWhere(
                                    [
                                        ['product_id', '=', $input['product_id']], 
                                        ['status', '=', '1'], 
                                        ['size', '!=', $input['size']],
                                    ], '', false);

                                $tmpData = '';
                                foreach ($getOtherStock as $key) 
                                {
                                    $tmpData .= 'Size '.$key->size.' : '.$key->stock.'<br>';
                                }

                                $result['notif'] = '
                                    Sorry, available stock only '.$getDataSKU[0]->stock.'.
                                    <br><br>
                                    Other stock :
                                    <br>'.$tmpData;
                            }
                            else
                            {
                                $getOtherStock = EmProductSku::getWhere(
                                    [
                                        ['product_id', '=', $input['product_id']], 
                                        ['status', '=', '1'], 
                                        ['color_hexa', '!=', $input['color']],
                                    ], '', false);

                                $tmpData = '';
                                foreach ($getOtherStock as $key) 
                                {
                                    $tmpData .= 'Color '.$key->color_name.' : '.$key->stock.'<br>';
                                }

                                $result['notif'] = '
                                    Sorry, available stock only '.$getDataSKU[0]->stock.'.
                                    <br><br>
                                    Other stock :
                                    <br>'.$tmpData;
                            }
                        }
                    }
                    else
                    {
                        $getNotif = $this->setupCart($getDataSKU[0], $input['qty'], $getDataSKU[0]->stock);
                        if($getNotif == '')
                        {
                            $result['trigger'] = 'yes';
                            $result['notif'] = 'Product has been added to the shopping cart.';
                            $result['count_cart'] = count(Session::get('cart'));
                            $result['right_side_cart'] = $this->resultRightSideCart();
                        }
                        else
                        {
                            $result['notif'] = $getNotif;   
                        }
                    }
                }
                else
                {
                    $result['notif'] = 'Please input the number of products ordered correctly.';
                }
            }
            else
            {
                $result['notif'] = 'Sorry, the server was unable to process your request.';
            }
        }

        echo json_encode($result);
    }

    private function setupCart($productSKU, $qtyBuy, $stockProduct)
    {
        if($productSKU)
        {
            $notifOutOfStock = '';
            $addNewItem = true;
            $item = array();

            $itemTmp = array();
            if(Session::get('cart') != null)
            {
                $itemTmp = Session::get('cart');
            }

            $current_currency = Common_helper::get_current_currency();
            
            foreach ($itemTmp as $key) 
            {
                $tmpCartData = array();

                $getProduct = EmProduct::select('product_name','price','discount')->where('product_id',$key['product_id'])->first();
                $getProductImage = EmProductImg::select('image')->where('product_id',$key['product_id'])->orderBy('img_id','DESC')->first();

                $setDiscount = Common_helper::set_discount($getProduct->price, $getProduct->discount);
                $priceAfterDisc = $setDiscount[0];
                $discount = $setDiscount[1];

                $priceInCurrencyFormat = Common_helper::convert_to_current_currency($priceAfterDisc);
                $showPriceAfterDisc = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];

                $actionAddItem = true;
                if($key['sku_id'] == $productSKU->sku_id && $key['product_id'] == $productSKU->product_id)
                {
                    $addNewItem = false;

                    if(($key['qty'] + $qtyBuy) > $stockProduct)
                    {
                        $notifOutOfStock = 'Sorry, the number of orders exceeds the available stock.<br>Only '.$stockProduct.' product(s) available.';
                    }
                    else
                    {
                        $notif = '';
                        if(isset($key['notif']))
                        {
                            $notif = $key['notif'];
                        }

                        $tmpCartData = array(
                            'product_id' => $key['product_id'],
                            'product_name' => $getProduct->product_name,
                            'product_img' => $getProductImage->image,
                            'sku_id' => $key['sku_id'],
                            'size' => $key['size'],
                            'color_name' => $key['color_name'],
                            'color_hexa' => $key['color_hexa'],
                            'qty' => ($key['qty'] + $qtyBuy),
                            'price_text' => $showPriceAfterDisc,
                            'price' => $priceInCurrencyFormat,
                            'stock' => $stockProduct,
                            'notif' => $notif,
                        );
                        $actionAddItem = false;
                    }
                }

                if($actionAddItem)
                {
                    $tmpCartData = array(
                        'product_id' => $key['product_id'],
                        'product_name' => $getProduct->product_name,
                        'product_img' => $getProductImage->image,
                        'sku_id' => $key['sku_id'],
                        'size' => $key['size'],
                        'color_name' => $key['color_name'],
                        'color_hexa' => $key['color_hexa'],
                        'qty' => $key['qty'],
                        'price_text' => $showPriceAfterDisc,
                        'price' => $priceInCurrencyFormat,
                        'stock' => $stockProduct
                    );
                }

                if(sizeof($tmpCartData) > 0)
                {
                    array_push($item, $tmpCartData);
                }
            }
            
            if($addNewItem)
            {
                $getProduct = EmProduct::select('product_name','price','discount')->where('product_id',$productSKU->product_id)->first();
                $getProductImage = EmProductImg::select('image')->where('product_id',$productSKU->product_id)->orderBy('img_id','DESC')->first();

                $setDiscount = Common_helper::set_discount($getProduct->price, $getProduct->discount);
                $priceAfterDisc = $setDiscount[0];
                $discount = $setDiscount[1];

                $priceInCurrencyFormat = Common_helper::convert_to_current_currency($priceAfterDisc);
                $showPriceAfterDisc = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];

                $dataArray = array(
                    'product_id' => $productSKU->product_id,
                    'product_name' => $getProduct->product_name,
                    'product_img' => $getProductImage->image,
                    'sku_id' => $productSKU->sku_id,
                    'size' => $productSKU->size,
                    'color_name' => $productSKU->color_name,
                    'color_hexa' => $productSKU->color_hexa,
                    'qty' => $qtyBuy,
                    'price_text' => $showPriceAfterDisc,
                    'price' => $priceInCurrencyFormat,
                    'stock' => $stockProduct
                );
                array_push($item, $dataArray);
            }

            Session::put('cart', $item);
            // Cookie::queue(sha1(env('AUTHOR_SITE')), serialize($item), env('SESSION_LIFE_TIME'));

            return $notifOutOfStock;
        }

    }

    public function checkoutProcess(Request $request)
    {   
        if(!Session::get('cart')){
            $result['trigger'] = 'no';
            $result['notif'] = 'There are no orders in your cart, please refresh your page.';
        }else{
            if(sizeof(Session::get('cart')) == 0)
            {
                $result['trigger'] = 'no';
                $result['notif'] = 'There are no orders in your cart.';
            }
            else
            {
                $result['trigger'] = 'no';
                $result['notif'] = 'Sorry, the server was unable to process your request.';

                $validator = Validator::make(request()->all(), [
                    'data_qty' => 'required',
                ],
                [
                    'data_qty.required' => 'Please check the number of products ordered.',
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
                    $input = $request->all();
                    $tmpQty = $input['data_qty'];

                    if(sizeof(Session::get('cart')) == count($tmpQty))
                    {
                        $getData = $this->checkCartBeforePayment($tmpQty);
                        if($getData[0])
                        {
                            if(isset($input['data_shipping_cost']))
                            {
                                $getShipping = Session::get('delivery');
                                Session::put('delivery', array($getShipping[0], $input['data_shipping_cost']));
                            }


                            // print_r(Session::get('delivery'));
                            // print_r(Session::get('cart'));

                            $getCurrentCurrency = Common_helper::get_current_currency();

                            //shipping
                            $shipping = Session::get('delivery');
                            $shippingCost = 0;
                            $shippingEstimate = 0;
                            $rupiahCost = 0;
                            if($shipping != null){
                                if(count($shipping) == 2){
                                    $tmpData = explode('_', $shipping[1]);
                                    $shippingCost = $tmpData[1];
                                    $shippingEstimate = $tmpData[3];
                                    $rupiahCost = $tmpData[2];
                                }
                            }


                            //save cart
                            $tmpItem = array();
                            $totalCost = 0;
                            foreach (Session::get('cart') as $key) 
                            {
                                $getProduct = EmProduct::getWhere([['product_id', '=', $key['product_id']], ['status', '=', '1']], '', false);
                                $price = 0;
                                $discount = 0;
                                foreach ($getProduct as $value) 
                                {
                                    $tmpPrice = Common_helper::convert_to_current_currency($value->price);
                                    $price = $tmpPrice[0];
                                    $discount = $value->discount;
                                }

                                $tmpData = array(
                                    'product_id' => $key['product_id'],
                                    'sku_id' => $key['sku_id'],
                                    'qty' => $key['qty'],
                                    'price' => $price,
                                    'discount' => $discount,
                                );
                                array_push($tmpItem, $tmpData);

                                $getSubTotal = Common_helper::set_discount(($key['qty'] * $price), $discount);
                                $totalCost += $getSubTotal[0];

                            }

                            //set up tax
                            $tax = EmConfig::getData(array('meta_key' => 'tax'));
                            $taxTotal = ($tax->meta_value * $totalCost) / 100;

                            $newInvoice = Common_helper::create_invoice_number();
                            $additional_price = 0;
                            if($getCurrentCurrency[3] == '1'){
                                // $additional_price = rand(111,999);
                            }

                            $customer_id = null;
                            if(Session::get(env('SES_FRONTEND_ID')) != null)
                            {
                                $customer_id = Session::get(env('SES_FRONTEND_ID'));
                            }

                            $totalPayment = ($totalCost + $shippingCost + $additional_price + $taxTotal);

                            //check totalPayment 

                            $dataInsertHeaderTrans = [
                                'transaction_code' => $newInvoice,
                                'customer_id' => $customer_id,
                                'total_price' => $totalCost,
                                'shipping_cost' => $shippingCost,
                                'additional_price' => $additional_price,
                                'tax' => $taxTotal,
                                'total_payment' => Common_helper::check_decimal($totalPayment),
                                'payment_status' => '0',
                                'status' => '1',
                                'transaction_date' => strtotime(Common_helper::date_time_now()),
                                'unique_code' => Common_helper::generateRandomString(),
                            ];                        
                            $getIDTrans = EmTransaction::insertData($dataInsertHeaderTrans);

                            //save shipping
                            if($shipping != null){
                                if(count($shipping) == 2){
                                    EmTransactionMeta::updateMeta(array('transaction_id' => $getIDTrans, 'meta_key' => 'national', 'meta_description' => $shipping[0]['national']));


                                    // insert type shipping /packet
                                    $tmpPacketShipping = explode('_', $shipping[1]);
                                    $tmp = explode(':', $tmpPacketShipping[0]);

                                    $dataInsertShipping = [];
                                    if($shipping[0]['national'] == '1')
                                    {
                                        $dataInsertShipping = [
                                            'transaction_id' => $getIDTrans,
                                            'country_id' => $shipping[0]['country'],
                                            'country_name' => $shipping[0]['country_name'],
                                            'city_id' => $shipping[0]['city'],
                                            'city_name' => $shipping[0]['city_name'],
                                            'province_id' => $shipping[0]['province'],
                                            'province_name' => $shipping[0]['province_name'],
                                            'subdistrict_id' => $shipping[0]['subdistrict'],
                                            'subdistrict_name' => $shipping[0]['subdistrict_name'],
                                            'postal_code' => $shipping[0]['postalcode'],
                                            'shipping_estimate' => $shippingEstimate,
                                            'rupiah_cost' => $rupiahCost
                                        ];
                                    }
                                    else
                                    {
                                        $dataInsertShipping = [
                                            'transaction_id' => $getIDTrans,
                                            'country_id' => $shipping[0]['country'],
                                            'country_name' => $shipping[0]['country_name'],
                                            'city_id' => $shipping[0]['city'],
                                            'city_name' => $shipping[0]['city_name'],
                                            'province_id' => $shipping[0]['province'],
                                            'province_name' => $shipping[0]['province_name'],
                                            'postal_code' => $shipping[0]['postalcode'],
                                            'detail_address' => $shipping[0]['address'],
                                            'shipping_estimate' => $shippingEstimate,
                                            'rupiah_cost' => $rupiahCost
                                        ];  
                                    }
                                    $counter = 0;
                                    foreach ($tmp as $key) 
                                    {
                                        if($counter == 0)
                                        {
                                            $dataInsertShipping['shipping_packet'] = $key;
                                        }
                                        else
                                        {
                                            $dataInsertShipping['shipping_description'] = $key;
                                        }
                                        $counter++;
                                    }

                                    EmTransactionShipping::insertData($dataInsertShipping);
                                }
                            }
                            if($shipping == null || sizeof($shipping) != 2)
                            {
                                if(Session::get(env('SES_FRONTEND_ID')) != null)
                                {
                                    $getCusShipping = EmCustomerShipping::getWhere([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))]], '', false);
                                    foreach ($getCusShipping as $value) 
                                    {
                                        $dataInsertShipping = [];
                                        if($value->country_id == '236')
                                        {
                                            $dataInsertShipping = [
                                                'transaction_id' => $getIDTrans,
                                                'country_id' => $value->country_id,
                                                'country_name' => $value->country_name,
                                                'city_id' => $value->city_id,
                                                'city_name' => $value->city_name,
                                                'province_id' => $value->province_id,
                                                'province_name' => $value->province_name,
                                                'subdistrict_id' => $value->subdistrict_id,
                                                'subdistrict_name' => $value->subdistrict_name,
                                                'postal_code' => $value->postal_code,
                                                'detail_address' => $value->detail_address
                                            ];
                                        }
                                        else
                                        {
                                            $dataInsertShipping = [
                                                'transaction_id' => $getIDTrans,
                                                'country_id' => $value->country_id,
                                                'country_name' => $value->country_name,
                                                'postal_code' => $value->postal_code,
                                                'detail_address' => $value->detail_address
                                            ];  
                                        }

                                        EmTransactionShipping::insertData($dataInsertShipping);
                                    }
                                }
                            }

                            $weight = 0;
                            //detail transaction
                            foreach ($tmpItem as $key) 
                            {
                                $dataInsertDetail = [
                                    'transaction_id' => $getIDTrans,
                                    'product_id' => $key['product_id'],
                                    'sku_id' => $key['sku_id'],
                                    'qty' => $key['qty'],
                                    'price' => $key['price'],
                                    'discount' => $key['discount'],
                                    'status' => '1',
                                ];
                                EmTransactionDetail::insertData($dataInsertDetail);   

                                $getProduct = EmProduct::getWhere([['product_id', '=', $key['product_id']]], '', false);
                                foreach ($getProduct as $value) 
                                {
                                    $weight += ($value->weight * $key['qty']);
                                }

                                //change stock
                                $model = EmProduct::find($key['product_id']);
                                $model->stock -= $key['qty'];
                                $model->save();

                                $model = EmProductSku::find($key['sku_id']);
                                $model->stock -= $key['qty'];
                                $model->save();
                            }

                            //insert meta transaction
                            EmTransactionMeta::updateMeta(array('transaction_id' => $getIDTrans, 'meta_key' => 'weight', 'meta_description' => $weight));

                            EmTransactionMeta::updateMeta(array('transaction_id' => $getIDTrans, 'meta_key' => 'last_update', 'meta_description' => Common_helper::date_time_now()));
                            EmTransactionMeta::updateMeta(array('transaction_id' => $getIDTrans, 'meta_key' => 'currency_id', 'meta_description' => $getCurrentCurrency[3]));
                            EmTransactionMeta::updateMeta(array('transaction_id' => $getIDTrans, 'meta_key' => 'rate', 'meta_description' => $getCurrentCurrency[0]));
                            EmTransactionMeta::updateMeta(array('transaction_id' => $getIDTrans, 'meta_key' => 'tax', 'meta_description' => $tax->meta_value));

                            EmTransactionMeta::updateMeta(array('transaction_id' => $getIDTrans, 'meta_key' => 'timezone_offset_minutes', 'meta_description' => $input['timezone_offset_minutes']));
                            EmTransactionMeta::updateMeta(array('transaction_id' => $getIDTrans, 'meta_key' => 'timezone', 'meta_description' => Common_helper::getTimezone($input['timezone_offset_minutes'])));

                            Session::forget('cart');
                            Session::forget('delivery');
                            // Cookie::queue(Cookie::forget(sha1(env('AUTHOR_SITE'))));

                            //delete session on checkout page
                            Session::forget(sha1(env('AUTHOR_SITE').'_checkout_customer'));
                            Session::forget(sha1(env('AUTHOR_SITE').'_checkout_shipping'));
                            Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
                            Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));
                            ////------------------------------
                            
                            if(Session::get(env('SES_FRONTEND_ID')) == null)
                            {
                                Session::put(sha1(env('AUTHOR_SITE').'_transaction'), $newInvoice);
                                Cookie::queue(sha1(env('AUTHOR_SITE').'_transaction'), $newInvoice, env('SESSION_LIFE_TIME'));
                            }

                            $result['trigger'] = 'yes';
                            $result['notif'] = route('cart_checkout');
                        }
                        else
                        {
                            $result['notif'] = $getData[1];
                        }
                    }
                    else
                    {
                        $result['notif'] = 'System can\'t process your order.<br>Please refresh the page and try again.';
                    }
                }
            }
        }

        echo json_encode($result);
    }

    private function checkCartBeforePayment($newQty = array())
    {
        $notification = true;
        $dataNotification = '';

        $counterQty = 0;
        $tmpItem = array();
        foreach (Session::get('cart') as $cart) 
        {
            $product_id = $cart['product_id'];
            $sku_id = $cart['sku_id'];
            $size = $cart['size'];
            $color_name = $cart['color_name'];
            $color_hexa = $cart['color_hexa'];

            $qty = $cart['qty'];
            if(sizeof($newQty) > 0)
            {
                $qty = $newQty[$counterQty];
            }

            $getProduct = EmProduct::getWhere([['product_id', '=', $product_id]], '', false);
            $getProductSku = EmProductSku::getWhere([['product_id', '=', $product_id], ['sku_id', '=', $sku_id]], '', false);

            // print_r($getProductSku);
            $stock = $getProductSku[0]->stock;

            $notif = '';
            $disable = false;
            if($getProductSku[0]->stock == 0)
            {
                $notif = $getProduct[0]->product_name.' - Sold out.';
                $dataNotification .= $notif.'<br>';
                $notification = false;
            }
            else
            {
                if($qty > $getProductSku[0]->stock)
                {
                    $notif = '('.$getProduct[0]->product_name.') - Only available '.$getProductSku[0]->stock.' stock product.';
                    $dataNotification .= $notif.'<br>';
                    $notification = false;
                }
            }

            $tmpCartData = array(
                    'product_id' => $product_id,
                    'sku_id' => $sku_id,
                    'size' => $size,
                    'color_name' => $color_name,
                    'color_hexa' => $color_hexa,
                    'qty' => $qty,
                    'stock' => $getProductSku[0]->stock,
                    'notif' => $notif,
                );
            
            array_push($tmpItem, $tmpCartData);
            $counterQty++;
        }
        Session::put('cart', $tmpItem);

        return array($notification, $dataNotification);
    }

    public function deleteItemCart($product_id = '', $sku_id = '')
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';
        if($product_id != '' && $sku_id != '')
        {
            if(Session::get('cart') != null)
            {

                $current_currency = Common_helper::get_current_currency();

                $item = array();
                $itemTmp = Session::get('cart');
                foreach ($itemTmp as $key) 
                {
                    $tmpCartData = array();
                    if($key['sku_id'] == $sku_id && $key['product_id'] == $product_id){}
                    else
                    {
                        $getProduct = EmProduct::select('product_name','price','discount')->where('product_id',$key['product_id'])->first();
                        $getProductImage = EmProductImg::select('image')->where('product_id',$key['product_id'])->orderBy('img_id','DESC')->first();

                        $setDiscount = Common_helper::set_discount($getProduct->price, $getProduct->discount);
                        $priceAfterDisc = $setDiscount[0];
                        $discount = $setDiscount[1];

                        $priceInCurrencyFormat = Common_helper::convert_to_current_currency($priceAfterDisc);
                        $showPriceAfterDisc = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];

                        $tmpCartData = array(
                            'product_id' => $key['product_id'],
                            'product_name' => $getProduct->product_name,
                            'product_img' => $getProductImage->image,
                            'sku_id' => $key['sku_id'],
                            'size' => $key['size'],
                            'color_name' => $key['color_name'],
                            'color_hexa' => $key['color_hexa'],
                            'qty' => $key['qty'],
                            'price_text' => $showPriceAfterDisc,
                            'price' => $priceInCurrencyFormat,
                            'stock' => $key['stock']
                        );
                    }

                    if(sizeof($tmpCartData) > 0)
                    {
                        array_push($item, $tmpCartData);
                    }
                }

                Session::put('cart', $item);
                // Cookie::queue(sha1(env('AUTHOR_SITE')), serialize($item), env('SESSION_LIFE_TIME'));
                $result['trigger'] = 'yes';
                $result['notif'] = 'Product has been removed from the cart.';
                $result['right_side_cart'] = $this->resultRightSideCart();
            }
        }

        echo json_encode($result);
    }

    private function resultRightSideCart()
    {
        $html = '';

        if(Session::get('cart') != null)
        {
            $itemTmp = Session::get('cart');
            $price_in_right_side = 0;
            foreach ($itemTmp as $key) 
            {
                $html .= '
                <div class="row">
                    <div class="col-md-3 col-4"><img class="img-fluid" src="'.asset(env('URL_IMAGE').'product/thumb/'.$key['product_img']).'"></div>
                    <div class="col-md-7 col-6">
                    <p><b>'.$key['product_name'].'</b></p>
                    <p>'.$key['qty'].' x '.$key['price_text'].'</p>
                    </div>
                    <div class="col-md-2 col-2">
                    <a href="'.route('process_delete_item_cart').'/'.$key['product_id'].'/'.$key['sku_id'].'" class="btn btn-remove-product btn-remove-product-right-side d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                        </svg>
                    </a>
                    </div>
                </div>
                <hr>
                ';

                $price_in_right_side = $price_in_right_side + $key['price'][0];

            }

            $current_currency_in_right_side = Common_helper::get_current_currency();
            $price_in_right_side = Common_helper::convert_to_current_currency($price_in_right_side);

            $html .= '
                <div class="row">
                    <div class="col-md-6 col-6">
                    <p><b>Cart Subtotal:</b></p>
                    </div>
                    <div class="col-md-6 col-6 sub-total"><p>'.$current_currency_in_right_side[1].$price_in_right_side[1].' '.$current_currency_in_right_side[2].'</p></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12"><a href="'.url('/cart').'" class="btn btn-view-cart full-width">VIEW CART</a></div>
                </div>
            ';
        }

        return $html;
    }

    public function shippingLocation(Request $request)
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';

        $validator = Validator::make(request()->all(), [
            'data_id' => 'required',
            'trigger' => 'required',
        ],
        [
            'data_id.required' => 'Sorry, the server was unable to process your request.',
            'trigger.required' => 'Sorry, the server was unable to process your request.',
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
            $input = $request->all();

            if($input['trigger'] == 'country')
            {
                $getData = MProvince::getWhere([['country_id', '=', $input['data_id']], ['status', '=', '1']], '', false);
                $htmlBuilder = '<option value="">Choose Province</option>';
                foreach ($getData as $key) 
                {
                    $htmlBuilder .= '<option value="'.$key->province_id.'">'.$key->province_name.'</option>';
                }
                $result['trigger'] = 'yes';
                $result['notif'] = $htmlBuilder;
            }

            if($input['trigger'] == 'province')
            {
                $getData = MCity::getWhere([['province_id', '=', $input['data_id']], ['status', '=', '1']], '', false);
                $htmlBuilder = '<option value="">Choose City</option>';
                foreach ($getData as $key) 
                {
                    $htmlBuilder .= '<option value="'.$key->city_id.'">'.$key->city_name.'</option>';
                }
                $result['trigger'] = 'yes';
                $result['notif'] = $htmlBuilder;
            }

            if($input['trigger'] == 'city')
            {
                $getData = MSubdistrict::getWhere([['city_id', '=', $input['data_id']], ['status', '=', '1']], '', false);
                $htmlBuilder = '<option value="">Choose Subdistrict</option>';
                foreach ($getData as $key) 
                {
                    $htmlBuilder .= '<option value="'.$key->subdistrict_id.'">'.$key->subdistrict_name.'</option>';
                }
                $result['trigger'] = 'yes';
                $result['notif'] = $htmlBuilder;
            }
        }

        echo json_encode($result);
    }

    public function editCartProcess(Request $request)
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';

        $validator = Validator::make(request()->all(), [
            'trans_id' => 'required',
        ],
        [
            'trans_id.required' => 'Transaction not found.',
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
            $input = $request->all();
            $trans_id = $input['trans_id'];

            $current_currency = Common_helper::get_current_currency();
            
            //get trans id
            $getTransID = EmTransaction::getWhere([['unique_code', '=', $trans_id], ['status', '=', '1']], '', false);
            if(sizeof($getTransID) == 0)
            {
                $result['notif'] = 'You can\'t change this transaction.';
            }
            else
            {
                foreach($getTransID as $key)
                {
                    $trans_id = $key->transaction_id;
                }
                
                $getDetailTrans = EmTransactionDetail::transactionDetail([['em_transaction_detail.transaction_id', '=', $trans_id]]);
                if(sizeof($getDetailTrans) > 0)
                {
                    $item = array();

                    foreach ($getDetailTrans as $key) 
                    {
                        //change stock
                        $model = EmProduct::find($key->product_id);
                        $model->stock += $key->qty;
                        $model->save();

                        $model = EmProductSku::find($key->sku_id);
                        $model->stock += $key->qty;
                        $model->save();
                        //------------

                        $getProduct = EmProduct::select('product_name','price','discount')->where('product_id',$key->product_id)->first();
                        $getProductImage = EmProductImg::select('image')->where('product_id',$key->product_id)->orderBy('img_id','DESC')->first();

                        $setDiscount = Common_helper::set_discount($getProduct->price, $getProduct->discount);
                        $priceAfterDisc = $setDiscount[0];
                        $discount = $setDiscount[1];

                        $priceInCurrencyFormat = Common_helper::convert_to_current_currency($priceAfterDisc);
                        $showPriceAfterDisc = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];

                        $dataArray = array(
                                'product_id' => $key->product_id,
                                'product_name' => $getProduct->product_name,
                                'product_img' => $getProductImage->image,
                                'sku_id' => $key->sku_id,
                                'size' => $key->size,
                                'color_name' => $key->color_name,
                                'color_hexa' => $key->color_hexa,
                                'qty' => $key->qty,
                                'price_text' => $showPriceAfterDisc,
                                'price' => $priceInCurrencyFormat,
                                'stock' => $key->stock
                            );
                        array_push($item, $dataArray);
                    }

                    // print_r($item);

                    //update status transaction
                    EmTransaction::updateData($trans_id, ['status' => '6']);

                    Session::put('cart', $item);
                    // Cookie::queue(sha1(env('AUTHOR_SITE')), serialize($item), env('SESSION_LIFE_TIME'));
                }

                $result['trigger'] = 'yes';
                $result['notif'] = route('cart_page');
            }
        }

        echo json_encode($result);
    }

    public function checkoutGuestProcess(Request $request)
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';

        $validator = Validator::make(request()->all(), [
            'emailGuest' => 'required',
            'trans_id' => 'required',
        ],
        [
            'trans_id.required' => 'Sorry, the server was unable to process your request.',
            'emailGuest.required' => 'Please input your email.',
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
            $input = $request->all();
            $emailGuest = $input['emailGuest'];
            $trans_id = $input['trans_id'];

            EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'email', 'meta_description' => $emailGuest));
            Session::put(sha1(env('AUTHOR_SITE').'_checkout_customer'), array($emailGuest));

            $result['trigger'] = 'yes';
            $result['notif'] = $emailGuest;
        }

        echo json_encode($result);
    }

    private function getSnapToken($trans_code, $dataOrder, $dataCustomer, $addPrice){
        $midtrans = new CreateSnapTokenService($trans_code, $dataOrder, $dataCustomer, $addPrice);
        $snapToken = $midtrans->getSnapToken();
        return $snapToken;
    }

    public function checkoutShippingProcess(Request $request)
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';
        $notif = '';
        $triggerValidation = true;

        //validation user
        if(Session::get(env('SES_FRONTEND_ID')) == null)
        {
            $validator = Validator::make(request()->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_prefix' => 'required',
                'phone_number' => 'required',
            ],
            [
                'first_name.required' => 'Please input first name.',
                'last_name.required' => 'Please input last name.',
                'phone_prefix.required' => 'Please choose prefix.',
                'phone_number.required' => 'Please input phone number.',
            ]);
            
            if($validator->fails()) 
            {
                foreach ($validator->errors()->all() as $messages) 
                {
                    $notif .= $messages.'<br>';
                }

                $triggerValidation = false;
            }
        }

        $validator = Validator::make(request()->all(), [
            'country' => 'required',
            'address' => 'required',
            'postalcode' => 'required',
            'shipping_choose' => 'required',
        ],
        [
            'country.required' => 'Please choose country.',
            'address.required' => 'Please input address.',
            'postalcode.required' => 'Please input postal code.',
            'shipping_choose.required' => 'Please select delivery. Change the shipping address if no shipping method options are available.',
        ]);
        
        if($validator->fails()) 
        {
            foreach ($validator->errors()->all() as $messages) 
            {
                $notif .= $messages.'<br>';
            }

            $triggerValidation = false;
        }

        if($triggerValidation)
        {
            $validator = Validator::make(request()->all(), [
                'province' => 'required',
                'city' => 'required',
            ],
            [
                'province.required' => 'Please choose province.',
                'city.required' => 'Please choose city.',
            ]);

            if($validator->fails()) 
            {
                foreach ($validator->errors()->all() as $messages) 
                {
                    $notif .= $messages.'<br>';
                }

                $triggerValidation = false;
            }

            $input = $request->all();
            if($triggerValidation)
            {
                $trans_id = $input['trans_id'];

                $arraySession = array();
                if(Session::get(env('SES_FRONTEND_ID')) == null)
                {
                    $first_name = $input['first_name'];
                    $last_name = $input['last_name'];
                    $phone_prefix = $input['phone_prefix'];
                    $phone_number = $input['phone_number'];

                    $result['profile_cus'] = '';
                    EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'name', 'meta_description' => $first_name.', '.$last_name));
                    EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'first_name', 'meta_description' => $first_name));
                    EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'last_name', 'meta_description' => $last_name));
                    EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'phone_number', 'meta_description' => $phone_prefix.$phone_number));
                    EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'phone_prefix', 'meta_description' => $phone_prefix));

                    $arraySession['first_name'] = $first_name;
                    $arraySession['last_name'] = $last_name;
                    $arraySession['phone_prefix'] = $phone_prefix;
                    $arraySession['phone_number'] = $phone_number;
                }

                //update shipping address
                $shipping_choose = $input['shipping_choose'];
                $tmpData = explode('_', $shipping_choose);
                $tmpShippingPacket = explode(':', $tmpData[0]);
                $shippingPacket = $tmpData[0];
                $shippingPacketDescription = '';
                if(sizeof($tmpShippingPacket) == 2)
                {
                    $shippingPacket = $tmpShippingPacket[0];
                    $shippingPacketDescription = $tmpShippingPacket[1];
                }

                $shippingCost = $tmpData[1];
                $shippingEstimate = $tmpData[3];
                $rupiahCost = $tmpData[2];

                $dataUpdateShipping = [];

                $result['province'] = '';
                $result['city'] = '';
                $result['subdistrict'] = '';
                $getCountry = MCountry::getWhere([['country_id', '=', $input['country']]], '', false);
                $getProvince = MProvince::getWhere([['province_id', '=', $input['province']]], '', false);
                $getCity = MCity::getWhere([['city_id', '=', $input['city']]], '', false);
                $result['country'] = $getCountry[0]->country_name;
                if($input['country'] == '236')
                {
                    $getSubdistrict = MSubdistrict::getWhere([['subdistrict_id', '=', $input['subdistrict']]], '', false);

                    $dataUpdateShipping = [
                        'country_id' => $input['country'],
                        'country_name' => $getCountry[0]->country_name,
                        'city_id' => $input['city'],
                        'city_name' => $getCity[0]->city_name,
                        'province_id' => $input['province'],
                        'province_name' => $getProvince[0]->province_name,
                        'subdistrict_id' => $input['subdistrict'],
                        'subdistrict_name' => $getSubdistrict[0]->subdistrict_name,
                        'postal_code' => $input['postalcode'],
                        'detail_address' => $input['address'],
                        'shipping_estimate' => $shippingEstimate,
                        'rupiah_cost' => $rupiahCost
                    ];

                    $result['province'] = $getProvince[0]->province_name;
                    $result['city'] = $getCity[0]->city_name;
                    $result['subdistrict'] = $getSubdistrict[0]->subdistrict_name;

                    $arraySession['country_id'] = $input['country'];
                    $arraySession['country_name'] = $getCountry[0]->country_name;
                    $arraySession['city_id'] = $input['city'];
                    $arraySession['city_name'] = $getCity[0]->city_name;
                    $arraySession['province_id'] = $input['province'];
                    $arraySession['province_name'] = $getProvince[0]->province_name;
                    $arraySession['subdistrict_id'] = $input['subdistrict'];
                    $arraySession['subdistrict_name'] = $getSubdistrict[0]->subdistrict_name;
                    $arraySession['postal_code'] = $input['postalcode'];
                    $arraySession['address'] = $input['address'];
                }
                else
                {
                    $dataUpdateShipping = [
                        'country_id' => $input['country'],
                        'country_name' => $getCountry[0]->country_name,
                        'city_id' => $input['city'],
                        'city_name' => $getCity[0]->city_name,
                        'province_id' => $input['province'],
                        'province_name' => $getProvince[0]->province_name,
                        'postal_code' => $input['postalcode'],
                        'detail_address' => $input['address'],
                        'shipping_estimate' => $shippingEstimate,
                        'rupiah_cost' => $rupiahCost
                    ]; 

                    $result['province'] = $getProvince[0]->province_name;
                    $result['city'] = $getCity[0]->city_name; 

                    $arraySession['country_id'] = $input['country'];
                    $arraySession['country_name'] = $getCountry[0]->country_name;
                    $arraySession['city_id'] = $input['city'];
                    $arraySession['city_name'] = $getCity[0]->city_name;
                    $arraySession['province_id'] = $input['province'];
                    $arraySession['province_name'] = $getProvince[0]->province_name;
                    $arraySession['subdistrict_id'] = "";
                    $arraySession['subdistrict_name'] = "";
                    $arraySession['postal_code'] = $input['postalcode'];
                    $arraySession['address'] = $input['address'];
                }

                $getShipping = EmTransactionShipping::getWhere([['transaction_id', '=', $trans_id]], '', false);
                $dataUpdateShipping['shipping_packet'] = $shippingPacket;
                $dataUpdateShipping['shipping_description'] = $shippingPacketDescription;
                $dataUpdateShipping['shipping_estimate'] = $shippingEstimate;
                $dataUpdateShipping['rupiah_cost'] = $rupiahCost;
                if(sizeof($getShipping) > 0)
                {
                    EmTransactionShipping::updateDataByTransaction($trans_id, $dataUpdateShipping);
                }
                else
                {
                    $dataUpdateShipping['transaction_id'] = $trans_id;
                    EmTransactionShipping::insertData($dataUpdateShipping);   
                }
                $result['shipping_cost'] = Common_helper::convert_to_format_currency(Common_helper::set_two_nominal_after_point($shippingCost));


                //update shipping cost
                $getTransaction = EmTransaction::getWhere([['transaction_id', '=', $trans_id]], '', false);
                $totalPayment = 0;
                $_coupon = 0;
                foreach ($getTransaction as $key) 
                {
                    if(!is_null($key->coupon)){
                        $_coupon = $key->coupon;
                    }
                    $totalPayment = ($key->total_payment + $shippingCost - $key->shipping_cost) - $_coupon;
                }

                if($totalPayment < 0){
                    $totalPayment = 0;
                } else {
                    $totalPayment = Common_helper::check_decimal($totalPayment);
                }

                // echo $shippingCost;
                EmTransaction::updateData($trans_id, array('shipping_cost' => $shippingCost, 'total_payment' => $totalPayment));
                $result['total_payment'] = Common_helper::convert_to_format_currency(Common_helper::set_two_nominal_after_point($totalPayment));

                Session::put(sha1(env('AUTHOR_SITE').'_checkout_shipping'), $arraySession);


                // send invoice
                $getTransaction = EmTransaction::getWhereLastOne([['transaction_id', '=', $trans_id]]);
                $pajak = 0;
                $additional_price = 0;
                if(isset($getTransaction->transaction_id))
                {
                    $pajak = $getTransaction->tax;
                    $additional_price = $getTransaction->additional_price;

                    $message['unique_code'] = $getTransaction->unique_code;
                    $message['invoice_number'] = $getTransaction->transaction_code;
                    $message['first_name'] = '';
                    $message['last_name'] = '';
                    $message['phone_number'] = '';
                    $tmpEmailCustomer = '';
                    if(Session::get(env('SES_FRONTEND_ID')) == null)
                    {
                        $message['first_name'] = $input['first_name'];
                        $message['last_name'] = $input['last_name'];
                        $message['phone_number'] = $input['phone_prefix'].$input['phone_number'];
                    }
                    else
                    {
                        $getCustomer = EmCustomer::getWhere([['customer_id', '=', $getTransaction->customer_id]], '', false);
                        foreach ($getCustomer as $key) 
                        {
                            $message['first_name'] = $key->first_name;
                            $message['last_name'] = $key->last_name;
                            $tmpEmailCustomer = $key->email;
                            $message['phone_number'] = $key->phone_number;
                        }
                    }

                    $getDetails = EmTransactionDetail::transactionDetail([['em_transaction_detail.transaction_id', '=', $getTransaction->transaction_id]]);
                    $tmpData = array();
                    foreach ($getDetails as $key) 
                    {
                        $tmp = array(
                            'product_id' => $key->product_id,
                            'product_name' => $key->product_name,
                            'size' => $key->size,
                            'color_name' => $key->color_name,
                            'price' => $key->price,
                            'discount' => $key->discount,
                            'qty' => $key->qty,
                        );

                        array_push($tmpData, $tmp);
                    }

                    $message['details'] = $tmpData;

                    $emailCustomer = '';

                    $getMeta = EmTransactionMeta::getMeTa(array('transaction_id' => $getTransaction->transaction_id, 'meta_key' => 'email'));
                    if(isset($getMeta->meta_description))
                    {
                        $emailCustomer = $getMeta->meta_description;
                    }
                    if($emailCustomer == '' || $emailCustomer == null)
                    {
                        $emailCustomer = $tmpEmailCustomer;
                    }

                    if($getTransaction->status == '1')
                    {
                        if($emailCustomer != '' && sizeof($message) > 0)
                        {
                            // Common_helper::send_email($emailCustomer, $message, 'Selesaikan pesanan anda dengan nomor pesanan '.env('AUTHOR_SITE'), 'invoice');
                            // Common_helper::send_email(env('MAIL_REPLAY_TO'), $message, 'ORDER BARU DARI '.$message['first_name'].' '.$message['last_name'], 'invoice_to_admin');
                        }
                    }
                }

                // midtrans
                // $result['snap_token'] = self::getSnapToken(
                //     $message['invoice_number'],
                //     $message['details'], 
                //     array(
                //         'first_name' => $message['first_name'].' '.$message['last_name'],
                //         'email' => $emailCustomer,
                //         'phone' => $message['phone_number'],
                //     ),
                //     array(
                //         'shipping' => $shippingCost,
                //         'pajak' => $pajak,
                //         'kode_bayar' => $additional_price,
                //     ),
                // );

                $result['snap_token'] = '';
                $result['trigger'] = 'yes';
            }
        }

        $result['notif'] = $notif;

        echo json_encode($result);
    }

    public function checkBeforePayment(Request $request)
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, system can\'t process your payment. Please input customer and shipping info correctly.';

        if(Session::get(sha1(env('AUTHOR_SITE').'_checkout_customer')) != '' && Session::get(sha1(env('AUTHOR_SITE').'_checkout_shipping')) != '')
        {
            $validator = Validator::make(request()->all(), [
                'trans_id' => 'required',
            ],
            [
                'trans_id.required' => 'Please refresh the page. System can\'t found the transaction.',
            ]);

            $notif = '';
            if($validator->fails()) 
            {
                foreach ($validator->errors()->all() as $messages) 
                {
                    $notif .= $messages.'<br>';
                }

                $result['notif'] = $notif;
            }
            else
            {
                $input = $request->all();

                $getTransaction = EmTransaction::getWhere([['transaction_id', '=', $input['trans_id']]], '', false);
                foreach ($getTransaction as $key) 
                {
                    if($key->payment_status == '0')
                    {
                        $validation = true;
                        $notif = '';
                        if(Session::get(env('SES_FRONTEND_ID')) == null)
                        {
                            $getMeta = EmTransactionMeta::getMeTa(array('transaction_id' => $key->transaction_id, 'meta_key' => 'name'));
                            if(!isset($getMeta->meta_description))
                            {
                                $validation = false;
                                $notif .= 'Please fill customer form.<br>';
                                Session::forget(sha1(env('AUTHOR_SITE').'_checkout_customer'));
                            }
                        }

                        $getShipping = EmTransactionShipping::getWhere([['transaction_id', '=', $key->transaction_id]], '', false);
                        if(sizeof($getShipping) == 0)
                        {
                            $validation = false;
                            $notif .= 'Please fill shipping form.<br>';
                            Session::forget(sha1(env('AUTHOR_SITE').'_checkout_shipping'));
                        }

                        if($validation)
                        {
                            Session::put(sha1(env('AUTHOR_SITE').'_payment_trans_id'), $key->transaction_id);

                            if(Session::get(env('SES_FRONTEND_ID')) == null)
                            {
                                Session::put(sha1(env('AUTHOR_SITE').'_payment_trans_code'), $key->unique_code);
                            }
                            else
                            {
                                Session::put(sha1(env('AUTHOR_SITE').'_payment_trans_code'), $key->transaction_code);   
                            }

                            // Session::forget(sha1(env('AUTHOR_SITE').'_checkout_customer'));
                            // Session::forget(sha1(env('AUTHOR_SITE').'_checkout_shipping'));

                            $result['trigger'] = 'yes';
                            $result['notif'] = route('user_payment');  
                        }
                        else
                        {
                            $result['trigger'] = 'no';
                            $result['notif'] = $notif;  
                            $result['url'] = '';
                        } 
                    }
                }
            }
        }

        echo json_encode($result);
    }

    public function couponVerification(Request $request, $action = '')
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';

        if($action != '')
        {
            $validator = Validator::make(request()->all(), [
                'unique_code' => 'required',
                'coupon_code' => 'required',
            ],
            [
                'unique_code.required' => 'Sorry, the server was unable to process your request.',
                'coupon_code.required' => 'Please input coupon.',
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
                $input = $request->all();
                $unique_code = $input['unique_code'];
                $coupon_code = $input['coupon_code'];

                $getTransaction = EmTransaction::getWhere([['unique_code', '=', $unique_code]], '', false);

                if(sizeof($getTransaction) > 0)
                {
                    
                    $getCoupon = EmCoupon::getWhere([['coupon_code', '=', $coupon_code], ['status', '=', '1']], '', false);
                    if(sizeof($getCoupon) > 0)
                    {
                        if($action == 'verification-coupon')
                        {
                            if($getCoupon[0]->use_count > 0)
                            {   
                                $totalPrice = $getTransaction[0]->total_price;
                                $discount = $getCoupon[0]->discount;

                                $discNominal = $discount;
                                $totalPayment = (($getTransaction[0]->total_price + $getTransaction[0]->additional_price + $getTransaction[0]->tax) + $getTransaction[0]->shipping_cost) - $discNominal;
                                if($totalPayment < 0){
                                    $totalPayment = 0;
                                }
                                $totalPayment = Common_helper::check_decimal($totalPayment);

                                $totalPayment = Common_helper::convert_to_format_currency(Common_helper::set_two_nominal_after_point($totalPayment));

                                $discNominal = Common_helper::convert_to_format_currency(Common_helper::set_two_nominal_after_point($discNominal));

                                EmTransaction::updateData($getTransaction[0]->transaction_id, ['coupon' => $discNominal, 'total_payment' => $totalPayment]);
                                EmTransactionMeta::updateMeta(array('transaction_id' => $getTransaction[0]->transaction_id, 'meta_key' => 'coupon_id', 'meta_description' => $getCoupon[0]->coupon_id));
                                EmTransactionMeta::updateMeta(array('transaction_id' => $getTransaction[0]->transaction_id, 'meta_key' => 'coupon_price', 'meta_description' => $getCoupon[0]->discount));

                                //change amount of usage
                                Common_helper::manageAmoutofUsage($getCoupon, (($getTransaction[0]->total_price + $getTransaction[0]->additional_price + $getTransaction[0]->tax) + $getTransaction[0]->shipping_cost), 'minus');

                                $result['trigger'] = 'yes';
                                $result['notif'] = 'Coupon verified.';
                                $result['discount'] = $discount;
                                $result['discount_nominal'] = $discNominal;
                                $result['total_payment'] =  $totalPayment;
                            }
                            else
                            {
                                $result['notif'] = 'Coupon is no longer valid.';      
                            }
                        }

                        if($action == 'delete-coupon')
                        {
                            $totalPrice = $getTransaction[0]->total_price;
                            $discount = '0';
                            $discNominal = '0.00';
                            $remaining_coupon = $getTransaction[0]->coupon;

                            $totalPayment = $getTransaction[0]->total_price + $getTransaction[0]->shipping_cost + $getTransaction[0]->additional_price + $getTransaction[0]->tax;
                            // $totalPayment = Common_helper::convert_to_format_currency(Common_helper::set_two_nominal_after_point($totalPayment));
                            // $totalPayment = Common_helper::check_decimal($totalPayment);

                            EmTransaction::updateData($getTransaction[0]->transaction_id, ['coupon' => 0, 'total_payment' => $totalPayment]);
                            EmTransactionMeta::updateMeta(array('transaction_id' => $getTransaction[0]->transaction_id, 'meta_key' => 'coupon_id', 'meta_description' => ''));
                            EmTransactionMeta::updateMeta(array('transaction_id' => $getTransaction[0]->transaction_id, 'meta_key' => 'coupon_price', 'meta_description' => ''));

                            //change amount of usage
                            Common_helper::manageAmoutofUsage($getCoupon, $remaining_coupon, 'plus');

                            $result['trigger'] = 'yes';
                            $result['notif'] = 'Coupon has been deleted.';
                            $result['discount'] = $discount;
                            $result['discount_nominal'] = $discNominal;
                            $result['total_payment'] = $totalPayment;
                        }
                    }
                    else
                    {
                        $result['notif'] = 'Coupon not found.';       
                    }
                }
                else
                {
                    $result['notif'] = 'Transaction not found.';
                }
            }
        }

        echo json_encode($result);
    }

    public function processPayment(Request $request){
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';
        
        $validator = Validator::make(request()->all(), [
            'trans_id' => 'required',
            'trigger' => 'required',
            'payment' => 'required',
        ],
        [
            'trans_id.required' => 'Please input transaction',
            'trigger.required' => 'Please input trigger',
            'payment.required' => 'Please input data payment',
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
                $input = $request->all();

                $getTransaction = EmTransaction::getWhere([['unique_code', '=', $input['trans_id']]], '', false);

                if(sizeof($getTransaction) > 0)
                {
                    if($input['trigger'] == 'success'){
                        $trans_id = $getTransaction[0]->transaction_id;
                        
                        EmTransaction::updateData($trans_id, ['payment_status' => '1']);
                        EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'midtrans_data', 'meta_description' => json_encode($input['payment'])));
                        EmTransactionMeta::updateMeta(array('transaction_id' => $trans_id, 'meta_key' => 'payment_date', 'meta_description' => gmdate('Y-m-d H:i:s')));

                        Session::put(sha1(env('AUTHOR_SITE').'_payment_trans_id'), $trans_id);

                        $result['trigger'] = 'yes';
                        $result['notif'] = route('payment_complete');


                    } else {
                        $result['notif'] = 'Sorry, your payment cannot be continued at this time, please contact customer service.';
                    }
                }
                else
                {
                    $result['notif'] = 'Transaction not found.';
                }
            }

        echo json_encode($result);
    }

    public function logout() 
    {
        Common_helper::check_session_frontend(true);

        Session::forget('cart');
        Session::forget('delivery');
        Session::forget(sha1(env('AUTHOR_SITE').'_transaction'));
        // Cookie::queue(Cookie::forget(sha1(env('AUTHOR_SITE').'_transaction')));

        // Session::forget(sha1(env('AUTHOR_SITE').'_checkout_customer'));
        // Session::forget(sha1(env('AUTHOR_SITE').'_checkout_shipping'));
        // Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
        // Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));

        // Session::forget(env('SES_FRONTEND_ID'));
        // Session::forget(env('SES_FRONTEND_NAME'));
        // Session::forget(env('SES_FRONTEND_EMAIL'));
        // Session::forget(env('SES_FRONTEND_CATEGORY'));

        Auth::guard('frontend')->logout();
        Session::flush();
        return redirect()->route('shop_page');
    }
}