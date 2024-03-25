<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Cookie;
use Auth;
use View;
use Validator;
use Lang;

use App\Helper\Common_helper;
use App\Models\EmProductCategory;
use App\Models\EmProduct;
use App\Models\EmProductSku;
use App\Models\EmProductImg;
use App\Models\EmConfig;
use App\Models\MBank;
use App\Models\EmProofOfPayment;
use App\Models\EmCustomerShipping;
use App\Models\MCurrency;
use App\Models\EmTransaction;
use App\Models\EmTransactionDetail;
use App\Models\EmTransactionShipping;
use App\Models\EmTransactionMeta;
use App\Models\EmCustomer;
use App\Models\MCountryPhone;
use App\Models\MShippingCostDefault;
use App\Models\MCountry;
use App\Models\TgiCountry;
use App\Models\TgiCity;
use App\Models\MProvince;
use App\Models\MCity;
use App\Models\MSubdistrict;
use App\Models\EmCoupon;

class ShopController extends Controller
{
    private $limitPage = 200;
    private $need_login = '0';

    private $freeShipping = true;

    public function index($category = 'all', $search = '')
    {
        Common_helper::check_currency_country();
        $category_selected = $category;
        
        $getDataProduct = array();
        $tmpCategory = $category;

        $text_category = 'Collections';
        $desc_category = '';

        if($category == 'new')
        {

            $startDate = date('Y-m')."-01 00:00:00";
            $startDate = strtotime(date('Y-m-d', strtotime($startDate. ' -2 months')).' 00:00:00');

            $thisMonth = strtotime(date('Y-m')."-01 00:00:00");
            $endDate = strtotime(date("Y-m-t", $thisMonth).' 23:59:59');

            $getDataProduct = EmProduct::getWithImage("(em_product.date_in between '".$startDate."' AND '".$endDate."' AND em_product.product_name like '%" . $search . "%')", 0, $this->limitPage);
        }
        else if($category == 'all')
        {
            $getDataProduct = EmProduct::getWithImage("(em_product.product_name like '%" . $search . "%')", 0, $this->limitPage);
        }
        else
        {
            $category_id = '';
            $category = explode('-', $category);
            if(sizeof($category) > 0)
            {
                $category_id = $category[(count($category) - 1)];
            }
            $getDataProduct = EmProduct::getWithImage("(em_product_category.category_id = '".$category_id."' AND em_product.product_name like '%" . $search . "%')", 0, $this->limitPage);

            $category = '';
            $getCategory = EmProductCategory::getWhere([['category_id', '=', $category_id]], '', false);
            if(sizeof($getCategory) > 0)
            {
                foreach ($getCategory as $key) 
                {
                    $category = $key->category;
                    $text_category = $category;
                    $desc_category = $key->description;

                }
            }
        }

        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Product | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Product | '.env('AUTHOR_SITE'),
            'nav_page' => 'shop',
            'category_selected' => $category_selected,
            'data_product' => $this->show_product($getDataProduct, $tmpCategory, $search, 2),
            'data_product_category' => EmProductCategory::where('status', '1')->where("parent", null)->orWhere("parent", '')->get(),
            'search' => $search,
            'text_category' => $text_category,
            'desc_category' => $desc_category,
            'is_page' => 'shop',
        );
        return view('frontend.shop', $data);
    }

    public function loadMore($offset = 0, $category = 'all', $search = '')
    {
        $newPage = $offset+1;
        $tmpCategory = $category;
        if($offset!=0)
        {
            $offset=$this->limitPage *($offset-1);
        }
        else 
        {
            $offset=0;    
        }

        $getDataProduct = array();
        if($category == 'new')
        {
            $startDate = date('Y-m')."-01 00:00:00";
            $startDate = strtotime(date('Y-m-d', strtotime($startDate. ' -2 months')).' 00:00:00');

            $thisMonth = strtotime(date('Y-m')."-01 00:00:00");
            $endDate = strtotime(date("Y-m-t", $thisMonth).' 23:59:59');

            $getDataProduct = EmProduct::getWithImage("(em_product.date_in between '".$startDate."' AND '".$endDate."' AND em_product.product_name like '%" . $search . "%')", $offset, $this->limitPage);
        }
        else if($category == 'all')
        {
            $getDataProduct = EmProduct::getWithImage("(em_product.product_name like '%" . $search . "%')", $offset, $this->limitPage);
        }
        else
        {
            $tmpSplitCategory = explode('-', $category);
            if(sizeof($tmpSplitCategory) == 2){
                $getDataProduct = EmProduct::getWithImage("(em_product.category_id = '".$tmpSplitCategory[1]."' AND em_product.product_name like '%" . $search . "%')", $offset, $this->limitPage);
            }else{
                $getDataProduct = EmProduct::getWithImage("(em_product.category_id = '' AND em_product.product_name like '%" . $search . "%')", $offset, $this->limitPage);
            }
        }
        echo $this->show_product($getDataProduct, $tmpCategory, $search, $newPage);
    }

    private function show_product($dataProduct, $category, $search, $newPage)
    {
        $htmlBuilder = '';
        if(sizeof($dataProduct) > 0)
        {
            $current_currency = \App\Helper\Common_helper::get_current_currency();
            foreach ($dataProduct as $key) 
            {
                $detail = \App\Helper\Common_helper::generateProduct($key);

                $htmlDiscount = '';
                if($detail['discount'] != '0'){
                    $htmlDiscount = '<div class="product-label product-label-save">Save '.$detail['discount'].'%</div>';
                }

                $htmlCarousel = '';
                foreach($detail['image'] as $index => $value){
                    $carousel_active = '';
                    if($index == '0'){$carousel_active = 'active';}
                    $htmlCarousel .= '<button type="button" data-bs-target="#product'.$detail['id'].'" data-bs-slide-to="'.$index.'" aria-label="Slide '.$index.'" class="'.$carousel_active.'" style="background-color:'.$value['color'].'"></button>';
                }

                $htmlCarouselImage = '';
                foreach($detail['image'] as $index => $value){
                    $carousel_img_active = '';
                    if($index == '0'){$carousel_img_active = 'active';}
                    $htmlCarouselImage .= '
                        <div class="carousel-item '.$carousel_img_active.'">
                            <div class="product-image">
                                <a href="'.$detail['link'].'" class="image">';
                                foreach($value['image'] as $idximg => $img){
                                    $list_img_no = $idximg+1;
                                    $htmlCarouselImage .= '<img class="pic-'.$list_img_no.'" src="'.asset(env('URL_IMAGE').'product/thumb/'.$img['image']).'">';
                                }
                    $htmlCarouselImage .='
                                </a>
                            </div>
                        </div>
                    ';
                }
                
                
                $htmlBuilder .= '
                <div class="col-md-3 col-6 mb-5">
                    <div class="product-grid">
                        '.$htmlDiscount.'
                        <div id="product'.$detail['id'].'" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-indicators">'.$htmlCarousel.'</div>
                            <div class="carousel-inner">'.$htmlCarouselImage.'</div>
                        </div>
                        <div class="featurette-divider"></div>
                        <div class="product-content">
                            <h3 class="title"><a href="'.$detail['link'].'">'.$detail['product'].'</a></h3>
                            <div class="price">'.$detail['description'].'</div>
                            <br>
                            <center>
                                <a class="btn btn-white" href="'.$detail['link'].'">'.$detail['showPriceHTML'].'</a>
                            </center>
                        </div>
                    </div>
                </div>
                ';
            }

            $htmlBuilder .= '<div class="pagination"><a href="'.route('shop_more_page').'/'.$newPage.'/'.$category.'/'.$search.'" class="jscroll-next"></a></div>';
        }

        return $htmlBuilder;
    }

    public function detail($product = '')
    {
        Common_helper::check_currency_country();

        if($product == '')
        {
            return redirect()->route('shop_page');
        }

        $product_id = '';
        $tmpData = explode('-', $product);
        $product_code = @$tmpData[(count($tmpData) - 1)];

        $getDataImage = array();
        $getDataProduct = array();
        $productName = 'Product empty';
        $productDescription = '....';
        $productImg = asset(env('URL_IMAGE').'logo.png');
        $breadcrumbs = '
        <div class="breadcrumb">
            <a href="'.url('/shop').'">Shop</a>
        </div>';
        $categoriesHtml = '';

        $getDataProduct = EmProduct::getWhere([['product_id', '=', $product_code], ['status', '=', '1']], '', false);

        $also_like =  array();

        if(sizeof($getDataProduct) > 0)
        {
            $product_id = $getDataProduct[0]->product_id;

            $getDataImage = EmProductImg::getWhere([['em_product_img.product_id', '=', $product_id]], '', false);

            $productName = $getDataProduct[0]->product_name;
            $productDescription = $getDataProduct[0]->description;
            $productImg = asset(env('URL_IMAGE').'product/thumb/'.$getDataImage[0]->image);

            $productCategory = EmProductCategory::getWhere([['category_id', '=', $getDataProduct[0]->category_id]], '', false);

            $urlCategory = preg_replace('/[^\w ]/', '', $productCategory[0]->category);
            $breadcrumbs = '
                <div class="breadcrumb">
                    <a href="'.url('/shop').'">Shop</a>
                    <a>></a>
                    <a href="'.url('/shop/'.str_replace(' ', '-', strtolower($productCategory[0]->category)).'-'.$productCategory[0]->category_id).'">'.$productCategory[0]->category.'</a>
                    <a>></a>
                    <a class="active">'.$getDataProduct[0]->product_name.'</a>
                </div>';
            $categoriesHtml = '<p class="attr"><a href="'.url('/shop/'.str_replace(' ', '-', strtolower($productCategory[0]->category)).'-'.$productCategory[0]->category_id).'">'.$productCategory[0]->category.'</a></p>';
            
            $also_like = EmProduct::getWithImage("(em_product_category.category_id = '".$productCategory[0]->category_id."' AND em_product.product_id != '".$product_id."' )", 0, 4);
        }

        $checkStockProduct = EmProductSku::whereRaw("(stock > 0)")->where('product_id', $product_id)->get();
        $soldOut = true;
        if(sizeof($checkStockProduct) > 0){
            $soldOut = false;
        }

        $data = array(
            'share_page' => array(
                'description' => $productDescription,
                'keyword' => env('META_KEYWORD'),
                'title' => 'Product - '.strtoupper($productName).' | '.env('AUTHOR_SITE'),
                'image' => $productImg
            ),
            'title' => 'Product - '.strtoupper($productName).' | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Product | '.env('AUTHOR_SITE'),
            'nav_page' => 'shop',
            'data_image' => $getDataImage,
            'data_product' => $getDataProduct,
            'breadcrumbs' => $breadcrumbs,
            'data_product_sku' => EmProductSku::getWhere([['product_id', '=', $product_id], ['status', '=', '1']], '(stock > 0)', false),
            'current_currency' => \App\Helper\Common_helper::get_current_currency(),
            'soldOut' => $soldOut,
            'categoriesHtml' => $categoriesHtml,
            'also_like' => $also_like,
            'is_page' => 'shop',
        );

        return view('frontend.detail', $data);
    }

    public function cart()
    {
        Common_helper::check_currency_country();
        Common_helper::checkCart();
        $dataCart = array('page' => 'cart');
        $displayCart = $this->show_carts($dataCart);

        $shippingAddress = array();
        if(Session::get(env('SES_FRONTEND_ID')) != null)
        {
            $shippingAddress = EmCustomerShipping::getWhere([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))], ['status', '=', '1'], ['order', '=', '1']], '', false);
        }

        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => 'Cart ('.(is_array(Session::get('cart'))?sizeof(Session::get('cart')):'0').') | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Cart | '.env('AUTHOR_SITE'),
            'nav_page' => 'shop',
            'data_cart' => $displayCart,
            'tax' => EmConfig::getData(array('meta_key' => 'tax')),
            'country' => TgiCountry::getWhere([], '', false),
            'shipping_address' => $shippingAddress,
            'current_currency' => \App\Helper\Common_helper::get_current_currency(),
            'phone_prefix' => MCountryPhone::getWhere([['status', '=', '1']], '', false),
            'from' => 'cart',
            'is_page' => 'shop',
            // 'also_like' => EmProduct::getWithImage("", 0, 7)
        );
        return view('frontend.cart', $data);
    }

    public function shippingEstimate(Request $request)
    {
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';

        $input = $request->all();

        if(isset($input['trans_id']))
        {
            $validator = Validator::make(request()->all(), [
                'trans_id' => 'required',
                'country' => 'required',
                'city' => 'required',
            ],
            [
                'trans_id.required' => 'Please input transaction id.',
                'country.required' => 'Please choose region.',
                'city.required' => 'Please choose city.',
            ]);

            $notif = '';
            if($validator->fails()) 
            {
                foreach ($validator->errors()->all() as $messages) 
                {
                    $notif .= $messages.'<br>';
                }
            }
            else
            {
                $triggerProcess = true;
                if($input['country'] == '236')
                {
                    $validator = Validator::make(request()->all(), [
                        'province' => 'required',
                        'city' => 'required',
                        // 'subdistrict' => 'required',
                    ],
                    [
                        'province.required' => 'Please choose province.',
                        'city.required' => 'Please choose city.',
                        // 'subdistrict.required' => 'Please choose subdistrict.',
                    ]);

                    if($validator->fails()) 
                    {
                        foreach ($validator->errors()->all() as $messages) 
                        {
                            $notif .= $messages.'<br>';
                        }

                        $triggerProcess = false;
                    }
                }
                $result['notif'] = $notif;

                if($triggerProcess){
                    $subdistrict = "";
                    if(isset($input['subdistrict'])){
                        $subdistrict = $input['subdistrict'];
                    }

                    $getWeight = EmTransactionMeta::getMeta(array('transaction_id' => $input['trans_id'], 'meta_key' => 'weight'));
                    $getCountry = Common_helper::setLocation('country', isset($input['country']) ? $input['country'] : "");
                    // $getProvince = Common_helper::setLocation('province', isset($input['province']) ? $input['province'] : "");;
                    $getCity = Common_helper::setLocation('city', isset($input['city']) ? $input['city'] : "");;
                    // $getSubdistrict = Common_helper::setLocation('subdistrict', isset($input['subdistrict']) ? $input['subdistrict'] : "");;

                    $dataShipping = array(
                        'country' => $getCountry['id'],
                        'country_name' => $getCountry['name'],
                        'city' => $getCity['id'],
                        'city_name' => $getCity['name'],
                        'subdistrict_name' => $input['subdistrict'],

                        'postalcode' => $input['postalcode'],
                        'address' => $input['address'],
                        'weight' => $getWeight->meta_description
                    );

                    $getShipping = Common_helper::check_shipping_tgiexpress($getWeight->meta_description, $getCity['code']);
                    $result = $this->get_shipping_cost_tgi($getShipping, $getCountry['standard'], $getWeight->meta_description, true);
                }
            }
        }
        // else
        // {
        //     $validator = Validator::make(request()->all(), [
        //         'type_shipping' => 'required',
        //         'country' => 'required',
        //         'postalcode' => 'required',
        //         'weight_total' => 'required',
        //     ],
        //     [
        //         'country.required' => 'Please choose country.',
        //         'postalcode.required' => 'Please input postal code.',
        //         'type_shipping.required' => 'Please input type shipping.',
        //         'weight_total.required' => 'Please input weight.',
        //     ]);
            
        //     if($validator->fails()) 
        //     {
        //         $notif = '';
        //         foreach ($validator->errors()->all() as $messages) 
        //         {
        //             $notif .= $messages.'<br>';
        //         }
        //         $result['notif'] = $notif;
        //     }
        //     else
        //     {
        //         $input = $request->all();
        //         if($input['type_shipping'] == 'national')
        //         {
        //             $validator = Validator::make(request()->all(), [
        //                 'province' => 'required',
        //                 'city' => 'required'
        //             ],
        //             [
        //                 'province.required' => 'Please select the province of shipping.',
        //                 'city.required' => 'Please select the city of shipping.',
        //             ]);

        //             if($validator->fails()) 
        //             {
        //                 $notif = '';
        //                 foreach ($validator->errors()->all() as $messages) 
        //                 {
        //                     $notif .= $messages.'<br>';
        //                 }
        //                 $result['notif'] = $notif;
        //             }
        //             else
        //             {
        //                 $getCountry = Common_helper::setLocation('country', isset($input['country']) ? $input['country'] : "");
        //                 $getProvince = Common_helper::setLocation('province', isset($input['province']) ? $input['province'] : "");
        //                 $getCity = Common_helper::setLocation('city', isset($input['city']) ? $input['city'] : "");
        //                 $getSubdistrict = Common_helper::setLocation('subdistrict', isset($input['subdistrict']) ? $input['subdistrict'] : "");

        //                 $dataShipping = array(
        //                     'national' => true,
        //                     'origin_type' => 'city',
        //                     'weight' => $input['weight_total'],
        //                     'postalcode' => $input['postalcode'],

        //                     'country' => $getCountry['id'],
        //                     'country_name' => $getCountry['name'],
        //                     'province' => $getProvince['id'],
        //                     'province_name' => $getProvince['name'],
        //                     'city' => $getCity['id'],
        //                     'city_name' => $getCity['name'],
        //                     'subdistrict' => $getSubdistrict['id'],
        //                     'subdistrict_name' => $getSubdistrict['name']
        //                 );

        //                 Session::put('delivery', array(0 => $dataShipping));

        //                 $getShipping = Common_helper::check_shipping($dataShipping);
        //                 $result = $this->get_shipping_cost($getShipping, true, $input['weight_total']);
        //             }
        //         }
        //         else
        //         {
        //             $validator = Validator::make(request()->all(), [
        //                 'address' => 'required',
        //             ],
        //             [
        //                 'address.required' => 'Please input detail address of shipping.',
        //             ]);
        //             if($validator->fails()) 
        //             {
        //                 $notif = '';
        //                 foreach ($validator->errors()->all() as $messages) 
        //                 {
        //                     $notif .= $messages.'<br>';
        //                 }
        //                 $result['notif'] = $notif;
        //             }
        //             else
        //             {
        //                 $getCountry = Common_helper::setLocation('country', isset($input['country']) ? $input['country'] : "");
        //                 $getProvince = Common_helper::setLocation('province', isset($input['province']) ? $input['province'] : "");
        //                 $getCity = Common_helper::setLocation('city', isset($input['city']) ? $input['city'] : "");
        //                 $getSubdistrict = Common_helper::setLocation('subdistrict', isset($input['subdistrict']) ? $input['subdistrict'] : "");

        //                 $dataShipping = array(
        //                     'national' => false,
        //                     'postalcode' => $input['postalcode'],
        //                     'country' => $getCountry['id'],
        //                     'country_name' => $getCountry['name'],
        //                     'province' => $getProvince['id'],
        //                     'province_name' => $getProvince['name'],
        //                     'city' => $getCity['id'],
        //                     'city_name' => $getCity['name'],
        //                     'subdistrict' => $getSubdistrict['id'],
        //                     'subdistrict_name' => $getSubdistrict['name'],
        //                     'address' => $input['address'],
        //                     'weight' => $input['weight_total']
        //                 );
        //                 Session::put('delivery', array(0 => $dataShipping));

        //                 $getShipping = Common_helper::check_shipping($dataShipping);
        //                 $result = $this->get_shipping_cost($getShipping, false, $input['weight_total']);
        //             }
        //         }
        //     }
        // }
        echo json_encode($result);
    }

    // private function get_shipping_cost($getShipping)
    // {
    //     $current_currency = \App\Helper\Common_helper::get_current_currency();

    //     $result['trigger'] = 'no';
    //     $result['notif'] = '
    //     <tr>
    //         <td colspan="2">
    //             Sorry, estimated shipping costs are not available.
    //             <br>Please contact us for more information.
    //         </td>
    //     </tr>';

    //     $getShippingCost = MShippingCostDefault::where('shipping_cost_id','1')->get();
    //     if(count($getShippingCost)>0){
    //         $htmlBuilder = '';

    //         $shippingCostInCurrencyFormat = Common_helper::convert_to_current_currency($getShippingCost[0]->cost);
    //         $showShippingCost = $current_currency[1].$shippingCostInCurrencyFormat[1].' '.$current_currency[2];

    //         $counter = 1;
    //         $shippingCost = $getShippingCost[0]->cost;
    //         $etd = 0;
    //         $valueData = 'Default shipping cost_'.$shippingCostInCurrencyFormat[0].'_'.$shippingCost.'_'.$etd;
    //         $htmlBuilder .= '
    //         <tr>
    //             <td style="width:30px">
    //                 <div class="pretty p-default p-round p-thick p-bigger">
    //                     <input type="radio" name="shipping_choose" id="shipping_choose" value="'.$valueData.'" checked="true"/>
    //                     <div class="state p-primary-o">
    //                         <label></label>
    //                     </div>
    //                 </div>
    //             </td>
    //             <td for="shipping_choose">
    //                 <div><b>Standard Shipping</b></div>
    //                 <div>'.$showShippingCost.'</div>
    //             </td>
    //         </tr>';

    //         $result['trigger'] = 'yes';
    //         $result['notif'] = $htmlBuilder;
    //     }

    //     return $result;
    // }

    private function get_shipping_cost_tgi($getShipping, $isStandard, $weight, $showButtonUpdate = true){
        $current_currency = \App\Helper\Common_helper::get_current_currency();

        $result['trigger'] = 'no';
        $result['notif'] = '
        <tr>
            <td colspan="2">
                Sorry, estimated shipping costs are not available.
                <br>Please contact us for more information.
            </td>
        </tr>';

        $dataShipping = json_decode($getShipping[1], true);
        $btnUpdateShippingCost = '';
        if($showButtonUpdate){
            // $btnUpdateShippingCost = '
            //     <tr>
            //         <td colspan="2" align="right">
            //             <button class="btn-default btn btn-sm btn-white" id="update-shipping-cost">CHANGE SHIPPING COSTS</button>
            //         </td>
            //     </tr>
            // ';
        }

        $getShippingCost = false;
        if($getShipping[0]){
            $getAdditinalShiipingCost = Common_helper::getAdditionalShippingCost();
            if($isStandard){
                $dataShipping = isset($dataShipping['STANDARD']) ? $dataShipping['STANDARD'] : [];
            }else{
                $dataShipping = isset($dataShipping['GLOBAL']) ? $dataShipping['GLOBAL'] : [];
            }

            $tmpEtd = 0;
            $htmlBuilder = '';
            $counter = 1;
            foreach ($dataShipping as $key => $value) {
                // print_r($value);
                if($value['commodity_code'] == 'SHTCO' && $value['commodity_name'] == 'Commercial' && $value['price'] > 0){
                    $getShippingCost = true;

                    $shippingCostInCurrencyFormat = Common_helper::convert_to_current_currency($value['price']);
                    $showShippingCost = $current_currency[1].$shippingCostInCurrencyFormat[1].' '.$current_currency[2];

                    $htmlBuilder .= '
                        <tr>
                            <td width="35px">
                                <div class="pretty p-default p-round p-thick p-bigger">
                                    <input type="radio" name="shipping_choose" id="shipping_choose'.$counter.'" value="'.$value['commodity_code'].':'.$value['commodity_name'].'_'.$shippingCostInCurrencyFormat[0].'_'.($value['price']+ $getAdditinalShiipingCost).'_"/>
                                    <div class="state p-primary-o">
                                        
                                        <label></label>
                                    </div>
                                </div>
                            </td>
                            <td for="shipping_choose'.$counter.'">
                                <div><b>'.$value['commodity_code'].'-'.$value['commodity_name'].'</b></div>
                                <div>'.$showShippingCost.'</div>
                            </td>
                        </tr>';
                }
                $counter++;
            }

            $htmlBuilder .= $btnUpdateShippingCost;

            $result['trigger'] = 'yes';
            $result['notif'] = $htmlBuilder;
        }
        if(!$getShippingCost)
        {
            $etd = '30';
            $getShippingCost = MShippingCostDefault::getWhere([['shipping_cost_id', '=', '2']], '', false);
            if($isStandard){
                $getShippingCost = MShippingCostDefault::getWhere([['shipping_cost_id', '=', '1']], '', false);
                $etd = '7';
            }

            $weightinKG = ceil($weight / 1000);
            
            $shippingCost = ($getShippingCost[0]->cost + $getAdditinalShiipingCost) * $weightinKG;
            $shippingCostInCurrencyFormat = Common_helper::convert_to_current_currency($shippingCost + $getAdditinalShiipingCost);
            $showShippingCost = $current_currency[1].$shippingCostInCurrencyFormat[1].' '.$current_currency[2];

            $valueData = 'Default shipping cost_'.$shippingCostInCurrencyFormat[0].'_'.$shippingCost.'_'.$etd;
            $htmlBuilder = '
                <tr>
                    <td>
                        <div class="pretty p-default p-round p-thick p-bigger">
                            <input type="radio" name="shipping_choose" id="shipping_choose" value="'.$valueData.'"/>
                            <div class="state p-primary-o">
                                
                                <label></label>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><b>Default Shipping</b></div>
                        <div>Estimated delivery time : <b>'.$etd.' day(s)</b></div>
                        <div>'.$showShippingCost.'</div>
                    </td>
                </tr>'.$btnUpdateShippingCost;

            $result['trigger'] = 'yes';
            $result['notif'] = $htmlBuilder;

        }
        return $result;
    }

    // private function get_shipping_cost($getShipping, $national, $weight, $showButtonUpdate = true){
    //     $current_currency = \App\Helper\Common_helper::get_current_currency();

    //     $result['trigger'] = 'no';
    //     $result['notif'] = '
    //     <tr>
    //         <td colspan="2">
    //             Sorry, estimated shipping costs are not available.
    //             <br>Please contact us for more information.
    //         </td>
    //     </tr>';

    //     $dataShipping = json_decode($getShipping[1], true);
    //     $btnUpdateShippingCost = '';
    //     if($showButtonUpdate)
    //     {
    //         $btnUpdateShippingCost = '
    //             <tr>
    //                 <td colspan="2" align="right">
    //                     <button class="btn-default btn btn-sm btn-white" id="update-shipping-cost">CHANGE SHIPPING COSTS</button>
    //                 </td>
    //             </tr>
    //         ';
    //     }

    //     $getAdditinalShiipingCost = Common_helper::getAdditionalShippingCost();

    //     $getShippingCost = false;
    //     if(isset($dataShipping['rajaongkir']['status']['code']))
    //     {
    //         if($dataShipping['rajaongkir']['status']['code'] == 200)
    //         {
    //             // print_r($dataShipping['rajaongkir']);
    //             $code = $dataShipping['rajaongkir']['results'][0]['code'];
    //             $name = $dataShipping['rajaongkir']['results'][0]['name'];

    //             $tmpEtd = 0;
    //             $htmlBuilder = '';
    //             $counter = 1;
    //             foreach ($dataShipping['rajaongkir']['results'][0]['costs'] as $key => $value) 
    //             {
    //                 $getShippingCost = true;
    //                 // print_r($value);
    //                 if($national)
    //                 {
    //                     //free ongkir
    //                     // $value['cost'][0]['value'] = 0;
    //                     // $getAdditinalShiipingCost = 0;

    //                     $shippingCostInCurrencyFormat = Common_helper::convert_to_current_currency($value['cost'][0]['value']);
    //                     $showShippingCost = $current_currency[1].$shippingCostInCurrencyFormat[1].' '.$current_currency[2];

    //                     //one shipping
    //                     if(substr($value['cost'][0]['etd'], -1) > $tmpEtd){
    //                         $tmpEtd = substr($value['cost'][0]['etd'], -1);
    //                         $htmlBuilder .= '
    //                             <tr>
    //                                 <td>
    //                                     <div class="pretty p-default p-round p-thick p-bigger">
    //                                         <input type="radio" name="shipping_choose" id="shipping_choose'.$counter.'" value="'.$value['service'].':'.$value['description'].'_'.$shippingCostInCurrencyFormat[0].'_'.($value['cost'][0]['value']+ $getAdditinalShiipingCost).'_'.$value['cost'][0]['etd'].'"/>
    //                                         <div class="state p-primary-o">
                                                
    //                                             <label></label>
    //                                         </div>
    //                                     </div>
    //                                 </td>
    //                                 <td for="shipping_choose'.$counter.'">
    //                                     <div><b>'.$value['service'].'</b></div>
    //                                     <div>Estimated delivery time : <b>'.$value['cost'][0]['etd'].' day(s)</b></div>
    //                                     <div>'.$showShippingCost.'</div>
    //                                 </td>
    //                             </tr>';
    //                     }
                            

    //                     // more than one shipping
    //                     $htmlBuilder .= '
    //                         <tr>
    //                             <td>
    //                                 <div class="pretty p-default p-round p-thick p-bigger">
    //                                     <input type="radio" name="shipping_choose" id="shipping_choose" value="'.$value['service'].':'.$value['description'].'_'.$shippingCostInCurrencyFormat[0].'_'.($value['cost'][0]['value']+ $getAdditinalShiipingCost).'_'.$value['cost'][0]['etd'].'"/>
    //                                     <div class="state p-primary-o">
                                            
    //                                         <label></label>
    //                                     </div>
    //                                 </div>
    //                             </td>
    //                             <td>
    //                                 <div><b>'.$value['service'].'</b></div>
    //                                 <div>Estimated delivery time : <b>'.$value['cost'][0]['etd'].' day(s)</b></div>
    //                                 <div>'.$showShippingCost.'</div>
    //                             </td>
    //                         </tr>';
    //                 }
    //                 else
    //                 {
    //                     $shippingCostInCurrencyFormat = Common_helper::convert_to_current_currency(($value['cost'] + $getAdditinalShiipingCost));
    //                     $showShippingCost = $current_currency[1].$shippingCostInCurrencyFormat[1].' '.$current_currency[2];

    //                     $htmlBuilder .= '
    //                         <tr>
    //                             <td>
    //                                 <div class="pretty p-default p-round p-thick p-bigger">
    //                                     <input type="radio" name="shipping_choose" id="shipping_choose'.$counter.'" value="'.$value['service'].'_'.$shippingCostInCurrencyFormat[0].'_'.($value['cost']+ $getAdditinalShiipingCost).'_'.$value['etd'].'"/>
    //                                     <div class="state p-primary-o">
                                            
    //                                         <label></label>
    //                                     </div>
    //                                 </div>
    //                             </td>
    //                             <td for="shipping_choose'.$counter.'">
    //                                 <div><b>'.$value['service'].'</b></div>
    //                                 <div>Estimated delivery time : <b>'.$value['etd'].' day(s)</b></div>
    //                                 <div>'.$showShippingCost.'</div>
    //                             </td>
    //                         </tr>';
    //                 }
    //                 $counter++;
    //             }

    //             $htmlBuilder .= $btnUpdateShippingCost;

    //             $result['trigger'] = 'yes';
    //             $result['notif'] = $htmlBuilder;
    //         }
    //     }
    //     if(!$getShippingCost)
    //     {
    //         $etd = '30';
    //         $getShippingCost = MShippingCostDefault::getWhere([['shipping_cost_id', '=', '2']], '', false);
    //         if($national)
    //         {
    //             $getShippingCost = MShippingCostDefault::getWhere([['shipping_cost_id', '=', '1']], '', false);
    //             $etd = '7';
    //         }

    //         $weightinKG = ceil($weight / 1000);
            
    //         $shippingCost = ($getShippingCost[0]->cost + $getAdditinalShiipingCost) * $weightinKG;
    //         $shippingCostInCurrencyFormat = Common_helper::convert_to_current_currency($shippingCost + $getAdditinalShiipingCost);
    //         $showShippingCost = $current_currency[1].$shippingCostInCurrencyFormat[1].' '.$current_currency[2];

    //         $valueData = 'Default shipping cost_'.$shippingCostInCurrencyFormat[0].'_'.$shippingCost.'_'.$etd;
    //         $htmlBuilder = '
    //             <tr>
    //                 <td>
    //                     <div class="pretty p-default p-round p-thick p-bigger">
    //                         <input type="radio" name="shipping_choose" id="shipping_choose" value="'.$valueData.'"/>
    //                         <div class="state p-primary-o">
                                
    //                             <label></label>
    //                         </div>
    //                     </div>
    //                 </td>
    //                 <td>
    //                     <div><b>Default Shipping</b></div>
    //                     <div>Estimated delivery time : <b>'.$etd.' day(s)</b></div>
    //                     <div>'.$showShippingCost.'</div>
    //                 </td>
    //             </tr>'.$btnUpdateShippingCost;

    //         $result['trigger'] = 'yes';
    //         $result['notif'] = $htmlBuilder;

    //     }
    //     return $result;
    // }

    private function checking_trans_code($trans_code, $allStatus = false)
    {
        $transCode = '';
        if(Session::get(env('SES_FRONTEND_ID')) == null)
        {
            if($trans_code != '')
            {
                $getTransaction = EmTransaction::getWhereLastOne([['unique_code', '=', $trans_code], ['status', '=', '1']]);
                if($allStatus)
                {
                    $getTransaction = EmTransaction::getWhereLastOne([['unique_code', '=', $trans_code]]);
                }
                if(isset($getTransaction->transaction_id))
                {
                    $transCode = $getTransaction->transaction_code;
                    if($getTransaction->customer_id != '')
                    {
                        $this->need_login = '1';
                    }
                }
                else
                {
                    $transCode = '';
                }
            }
            else
            {
                if(!$allStatus)
                {
                    $transCode = Session::get(sha1(env('AUTHOR_SITE').'_transaction'));
                    if(Cookie::get(sha1(env('AUTHOR_SITE').'_transaction')) != null)
                    {
                        $transCode = Cookie::get(sha1(env('AUTHOR_SITE').'_transaction'));
                    }
                }
            }
        }
        else
        {
            if($trans_code != '')
            {
                $getTransaction = EmTransaction::getWhereLastOne([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))], ['transaction_code', '=', $trans_code], ['status', '=', '1']]);
                if($allStatus)
                {
                    $getTransaction = EmTransaction::getWhereLastOne([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))], ['transaction_code', '=', $trans_code]]);
                }
                if(isset($getTransaction->transaction_id))
                {
                    $transCode = $getTransaction->transaction_code;
                }
                else
                {
                    $getTransaction = EmTransaction::getWhereLastOne([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))], ['unique_code', '=', $trans_code], ['status', '=', '1']]);
                    if($allStatus)
                    {
                        $getTransaction = EmTransaction::getWhereLastOne([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))], ['unique_code', '=', $trans_code]]);
                    }   
                    if(isset($getTransaction->transaction_id))
                    {
                        $transCode = $getTransaction->transaction_code;
                    }
                    else
                    {
                        $transCode = '';
                    }
                }
            }
            else
            {
                //get last one transaction
                if(!$allStatus)
                {
                    $getTransaction = EmTransaction::getWhereLastOne([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))], ['status', '=', '1']]);
                    if(isset($getTransaction->transaction_id))
                    {
                        $transCode = $getTransaction->transaction_code;
                    }
                }
            }
        }
        return $transCode;
    }

    public function invoice($trans_code = '')
    {
        $transCode = $this->checking_trans_code($trans_code, true);

        $dataCart['trans_code'] = $transCode;
        $getTransactionHeader = EmTransaction::getWhereLastOne([['transaction_code', '=', $transCode]]);
        $shipping_data = array();
        $getDetailTransaction = array();
        $currency_id = '';
        if(isset($getTransactionHeader->transaction_id))
        {
            $shipping_data = EmTransactionShipping::getWhere([['transaction_id', '=', $getTransactionHeader->transaction_id]], '', false);
            $getDetailTransaction = EmTransactionDetail::transactionDetail([['em_transaction_detail.transaction_id', '=', $getTransactionHeader->transaction_id]]);
            $getCurrency = EmTransactionMeta::getMeta(array('transaction_id' => $getTransactionHeader->transaction_id, 'meta_key' => 'currency_id'));
            $currency_id = $getCurrency->meta_description;
        }

        $data = array(
            'title' => 'Invoice '.$transCode.' | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),

            'current_currency' => \App\Helper\Common_helper::get_current_currency($currency_id),
            'header_transaction' => $getTransactionHeader,
            'shipping_data' => $shipping_data,
            'detail_transaction' => $getDetailTransaction,
        );
        return view('frontend.account.invoice', $data);
    }

    public function checkout($trans_code = '')
    {
        $dataCart = array(
            'page' => 'checkout',
            'trans_code' => $trans_code,
        );

        $getTransactionHeader = array();
        $getProfile = array();
        $transCode = $this->checking_trans_code($trans_code);
        if(Session::get(env('SES_FRONTEND_ID')) != null)
        {
            $getProfile = EmCustomer::getWhere([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))]], '', false);
            $tmpData = array();
            foreach ($getProfile as $value) 
            {
                $tmpData['email'] = $value->email;
                $tmpData['first_name'] = $value->first_name;
                $tmpData['last_name'] = $value->last_name;
                $tmpData['phone_number'] = $value->phone_number;
            }
            Session::put(sha1(env('AUTHOR_SITE').'_checkout_customer'), $tmpData);
        }

        //check data session check out
        $dataCheck = array();
        if(Session::get(sha1(env('AUTHOR_SITE').'_checkout_customer')) != null)
        {
            array_push($dataCheck, 'customer');
            if(Session::get(sha1(env('AUTHOR_SITE').'_checkout_shipping')) != null)   
            {
                array_push($dataCheck, 'shipping');
            }
        }

        // print_r($dataCheck);


        $dataCart['trans_code'] = $transCode;
        $getTransactionHeader = EmTransaction::getWhereLastOne([['transaction_code', '=', $transCode], ['status', '=', '1']]);
        $shipping_data = array();
        $listShipping = "";
        if(isset($getTransactionHeader->transaction_id)){
            $shipping_data = EmTransactionShipping::getWhere([['transaction_id', '=', $getTransactionHeader->transaction_id]], '', false);

            $getCurrency = EmTransactionMeta::getMeta(array('transaction_id' => $getTransactionHeader->transaction_id, 'meta_key' => 'currency_id'));
            Session::put(env('SES_GLOBAL_CURRENCY'), $getCurrency->meta_description);

            if(sizeof($shipping_data) > 0){
                $getWeight = EmTransactionMeta::getMeta(array('transaction_id' => $getTransactionHeader->transaction_id, 'meta_key' => 'weight'));
                foreach ($shipping_data as $key) {
                    if($key->city_id){
                        $getCcountry = TgiCountry::getWhere([['id', '=', $key->country_id]], "", false);
                        $getCity = TgiCity::getWhere([['id', '=', $key->city_id]], "", false);
                        $getShipping = Common_helper::check_shipping_tgiexpress($getWeight->meta_description, $getCity[0]->city_code);
                        $listShipping = $this->get_shipping_cost_tgi($getShipping, $getCcountry[0]->standard, $getWeight->meta_description, false)['notif'];
                    }
                }
            }
        }

        $displayCart = $this->show_carts($dataCart);

        $payment_failed = '';
        if(Session::get('error_payment') != null){
            $payment_failed = '<strong>Payment Failed</strong>.<br>';
            $payment_failed .= Session::get('error_payment');
            Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_id'));
            Session::forget(sha1(env('AUTHOR_SITE').'_payment_trans_code'));
            Session::forget('error_payment');
        }
        if(!isset($getTransactionHeader->transaction_id)){
            return redirect()->route('shop_page');
        }
        $couponData = Common_helper::getCouponTransaction($getTransactionHeader->transaction_id);

        $phone_prefix_data = MCountryPhone::getWhere([['status', '=', '1']], '', false);
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => 'Payment | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'data_cart' => $displayCart,
            'header_transaction' => $getTransactionHeader,
            'current_currency' => \App\Helper\Common_helper::get_current_currency(),
            'country' => TgiCountry::getWhere([], '', false),
            // 'province_data' => MProvince::getWhere([['status', '=', '1']], '', false),
            // 'city_data' => MCity::getWhere([['status', '=', '1']], '', false),
            // 'subdistrict_data' => MSubdistrict::getWhere([['status', '=', '1']], '', false),
            'data_bank' => MBank::getWhere([['status', '=', '1']], '', false),
            'phone_prefix_data' => $phone_prefix_data,
            'phone_prefix' => $phone_prefix_data,
            'shipping_data' => $shipping_data,
            'shipping_list' => $listShipping,
            'profile_data' => $getProfile,
            'check_info' => $dataCheck,
            'need_login' => $this->need_login,
            'payment_failed' => $payment_failed,
            'coupon_data' => $couponData,
            'from' => 'cart',
            'timezone' => array(
                'timezone' => EmTransactionMeta::getMeta(array('transaction_id' => $getTransactionHeader->transaction_id, 'meta_key' => 'timezone')),
                'timezone_offset_minutes' => EmTransactionMeta::getMeta(array('transaction_id' => $getTransactionHeader->transaction_id, 'meta_key' => 'timezone_offset_minutes')),
            ),
            'alt_image' => 'Payment | '.env('AUTHOR_SITE'),
            'nav_page' => 'shop',
            'is_page' => 'shop',
        );

        return view('frontend.checkout', $data);
    }

    private function show_carts($dataCart = array())
    {
        $displayCart = array();
        $tmpDataCart = array();

        if($dataCart['page'] == 'cart')
        {
            if(Session::get('cart') != null)
            {
                if(sizeof(Session::get('cart')) > 0)
                {
                    $tmpDataCart = Session::get('cart');
                }
            }
        }

        if($dataCart['page'] == 'checkout')
        {
            $transCode = $dataCart['trans_code'];
            $getTransaction = EmTransaction::getWhereLastOne([['transaction_code', '=', $transCode], ['status', '=', '1']]);
            if(isset($getTransaction->transaction_id))
            {
                $getDetailTransaction = EmTransactionDetail::transactionDetail([['em_transaction_detail.transaction_id', '=', $getTransaction->transaction_id]]);

                if(sizeof($getDetailTransaction) > 0)
                {
                    $dataItems = array();
                    foreach ($getDetailTransaction as $key) 
                    {
                        $dataItems = array(
                            'product_id' => $key->product_id,
                            'sku_id' => $key->sku_id,
                            'size' => $key->size,
                            'color_name' => $key->color_name,
                            'color_hexa' => $key->color_hexa,
                            'qty' => $key->qty,
                            'stock' => $key->stock
                        );
                        array_push($tmpDataCart, $dataItems);
                    }
                }
            }
        }

        if(sizeof($tmpDataCart) > 0)
        {
            foreach ($tmpDataCart as $cart) 
            {
                $product_id = $cart['product_id'];
                $sku_id = $cart['sku_id'];
                $size = $cart['size'];
                $color_name = $cart['color_name'];
                $color_hexa = $cart['color_hexa'];
                $qty = $cart['qty'];
                $stock = $cart['stock'];

                $getImg = EmProductImg::getWhere([['em_product_img.product_id', '=', $product_id], ['em_product_img.order', '=', '1']], '', false);
                $getProduct = EmProduct::getWhere([['product_id', '=', $product_id]], '', false);
                $getProductSku = EmProductSku::getWhere([['product_id', '=', $product_id], ['sku_id', '=', $sku_id]], '', false);

                $notif = '';
                $disable = false;
                if($getProductSku[0]->stock == 0)
                {
                    $disable = true;
                    $notif = $getProduct[0]->product_name.' - Sold out.';
                }
                else
                {
                    if($qty > $getProductSku[0]->stock)
                    {
                        $disable = true;
                        $notif = '('.$getProduct[0]->product_name.') - Only '.$getProductSku[0]->stock.' product stock(s) are available.';
                    }
                }

                $dataArray = array(
                    'product_id' => $product_id,
                    'product_code' => $getProduct[0]->product_code,
                    'product_name' => $getProduct[0]->product_name,
                    'price' => $getProduct[0]->price,
                    'discount' => $getProduct[0]->discount,
                    'weight' => $getProduct[0]->weight,
                    'img' => $getImg[0]->image,

                    'stock' => $stock,

                    'sku_id' => $sku_id,
                    'size' => $size,
                    'color_name' => $color_name,
                    'color_hexa' => $color_hexa,
                    'qty' => $qty,
                    'notif' => $notif,
                    'disable' => $disable
                );

                array_push($displayCart, $dataArray);
            }
        }

        return $displayCart;
    }

    public function paymentConfirmation()
    {
        $data = array(
            'title' => 'Payment Confirmation | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'data_bank' => MBank::getWhere([['status', '=', '1']], '', false),
        );
        return view('frontend.payment_confirmation', $data);
    }

    public function processPaymentConfirmation(Request $request)
    {
        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'full_name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'invoice' => 'required',
            'total' => 'required',
            'payment_date' => 'required',
            'from_bank' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
            'bank' => 'required',
            'proof_of_payment' => 'required|image|mimes:jpeg,jpg,png|max:10240',
            // 'g-recaptcha-response' => 'required|captcha',
        ],
        [
            'full_name.required' => 'Please insert full name.',
            'email.required' => 'Please insert email address.',
            'phone_number.required' => 'Please insert phone number.',
            'invoice.required' => 'Please insert invoice number.',
            'total.required' => 'Please insert total of payment.',
            'payment_date.required' => 'Please insert payment date.',
            'from_bank.required' => 'Please insert bank name.',
            'account_name.required' => 'Please insert account name.',
            'account_number.required' => 'Please insert account number.',
            'proof_of_payment.required' => 'Please upload proof of payment.',
            'proof_of_payment.mimes' => 'Format proof of payment is wrong.',
            'proof_of_payment.max' => 'Upload image max 50MB.',
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
            
            //upload image
            $newSize = array(
                'crop' => false,
                'width' => 100,
                'height' => 0
            );
            $getImageName = Common_helper::upload_image('proofofpayment/', 'proofofpayment/thumb/', $newSize, $request->file('proof_of_payment'));
            if($getImageName != '')
            {
                $dataInsert = 
                [
                    'full_name' => $input['full_name'],
                    'email' => $input['email'],
                    'phone_number' => $input['phone_number'],
                    'transaction_code' => $input['invoice'],
                    'total_payment' => $input['total'],
                    'payment_date' => strtotime($input['payment_date']),
                    'bank_id' => $input['bank'],
                    'from_bank' => $input['from_bank'],
                    'account_name' => $input['account_name'],
                    'account_number' => $input['account_number'],
                    'proof_of_payment' => $getImageName,
                    'upload_date' => strtotime(Common_helper::date_time_now()),
                    'status' => '1'
                ];
                EmProofOfPayment::insertData($dataInsert);


                $message['name'] = $input['full_name'];
                $message['total_payment'] = $input['total'];
                $message['invoice'] = $input['invoice'];
                $message['proof_of_payment'] = $getImageName;
                Common_helper::send_email(env('MAIL_REPLAY_TO'), $message, 'Payment confirmation from '.$input['full_name'], 'proof_of_payment');

                $result['trigger'] = 'yes';
                $result['notif'] = 'Proof of payment has been sent successfully.<br>We will verify your payment.';
            }
            else
            {
                $result['notif'] = 'Please upload proof of payment.';
            }
        }

        echo json_encode($result);
    }
}