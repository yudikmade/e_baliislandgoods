<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmProduct;
use App\Models\EmProductImg;
use App\Models\EmProductSku;
use App\Models\EmProductCategory;
use App\Models\ExportProduct;
use App\Models\ExportProductSku;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    protected $limiPage = 20;
    private $menu_order = 2;

    //product
    public function index($search = '')
    {
        Common_helper::check_session_backend(true);

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $category_admin = Session::get(env('SES_BACKEND_CATEGORY'));

        if(is_null($category_admin)){
            $data_result = EmProduct::whereNotNull('category_id')->where('status', '!=', '2')->whereRaw("(product_name like '%".$search."%' OR product_code like '%".$search."%')")->orderBy('date_in', 'DESC')->paginate($this->limiPage);
        } else {
            $data_result = EmProduct::where('status', '!=', '2')->where('admin_id',Session::get(env('SES_BACKEND_ID')))->whereRaw("(product_name like '%".$search."%' OR product_code like '%".$search."%')")->orderBy('date_in', 'DESC')->paginate($this->limiPage);
        }
        $view_content = View::make('admin.product.product', compact('data_result'));

        $data = array(
            'title' => 'Product | Administrator',
            'title_page' => 'Product',
            'title_form' => 'Data product',
            'information' => 'The following data product has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> Product</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterProduct' => 'active',
            'search' => $search,
            'view_content' => $view_content,
            'url_search' => route('control_products'),
            'url_export' => route('control_products_export')
        );
        return view('admin.table_view_template', $data);
    }

    public function exportProduct($search = '')
    {
        Common_helper::check_session_backend(true);
        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        return Excel::download(new ExportProduct($search,Session::get(env('SES_BACKEND_ID'))), 'product'.date('YmdHis').'.xlsx');
    }

    public function sku($search = '')
    {
        Common_helper::check_session_backend(true);

        $data_result = EmProductSku::getWhereJoin($search);
        $view_content = View::make('admin.product.sku', compact('data_result'));

        $data = array(
            'title' => 'Product - SKU | Administrator',
            'title_page' => 'Product - SKU',
            'title_form' => 'Data product - SKU',
            'information' => 'The following data product - sku has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> Product SKU</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterProduct' => 'active',
            'search' => $search,
            'view_content' => $view_content,
            'url_search' => route('control_products_sku'),
            'url_export' => route('control_products_sku_export')
        );
        return view('admin.table_view_template', $data);
    }

    public function exportSku($search = '')
    {
        Common_helper::check_session_backend(true);
        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }
        return Excel::download(new ExportProductSku($search), 'product-sku'.date('YmdHis').'.xlsx');
    }

    public function getCategory(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'id' => 'required',
        ],
        [
            'id.required' => 'Please choose category correctly.',
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
            
            $data_categories = EmProductCategory::getWhere([['status', '!=', '2'], ['parent', '=', $input['id']]], '', false);
            $result['trigger'] = 'yes';
            // $result['notif'] = 'New category has been added.';

            $tmpData = '<option value="">Choose sub category</option>';
            foreach ($data_categories as $key) 
            {
                $tmpData .= '<option value="'.$key->category_id.'">'.$key->category.'</option>';
            }

            $result['notif'] = $tmpData;
        }

        echo json_encode($result);
    }

    public function editProduct($id)
    {
        Common_helper::check_session_backend(true);

        // $getProduct = EmProduct::getWhere([['product_id', '=', $id], ['status', '!=', '2'], ['admin_id', '=', Session::get(env('SES_BACKEND_ID'))]], '', false);
        $getProduct = EmProduct::getWhere([['product_id', '=', $id], ['status', '!=', '2']], '', false);

        if(sizeof($getProduct) > 0){
            $getProductCategory = EmProductCategory::getOneHierarchy($getProduct[0]->category_id);
            $categoryArray = array();

            if(count($getProductCategory['parent']) > 0)
            {
                for ($i=0; $i < count($getProductCategory['parent']); $i++) 
                { 
                    array_push($categoryArray, array($getProductCategory['parent'][$i]->category_id, $getProductCategory['parent'][$i]->category));
                }
            }
            array_push($categoryArray, array($getProductCategory[0]->category_id, $getProductCategory[0]->category));

            $data = array(
                'title' => 'Product | Administrator',
                'title_page' => 'Product',
                'title_form' => 'Form edit product',
                'breadcrumbs' => '
                                    <li class=""><a href="'.route('control_products').'"><i class="fa fa-file-text-o"></i> Product</a></li>
                                    <li class="active"><i class="fa fa-edit"></i> Edit product</li>
                                ',
                'data_categories' => EmProductCategory::getWhere([['status', '=', '1']], '(parent = \'\' or parent is null)', false),
                'data_image' => EmProductImg::getWhere([['product_id', '=', $id]], '', false),
                'data_result' => $getProduct,
                'data_product_category' => $categoryArray,
                'data_product_sku' => EmProductSku::getWhere([['product_id', '=', $id], ['status', '!=', '2']], '', false),
                'data_book' => array(),
                'menu_order' => $this->menu_order,
                'masterProduct' => 'active',
                'new_order' => EmProductSku::newOrder($id),
            );
            return view('admin.product.product_edit', $data);
        }
    }

    public function editProductProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, server can\'t response.';

        $input = $request->all();

        if($input['form_action'] == "delete-image")
        {
            $validator = Validator::make(request()->all(), [
                'product_id' => 'required',
                'id' => 'required',
            ],
            [
                'product_id.required' => 'Sorry, server can\'t response.',
                'id.required' => 'Please select image first.',
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
                $getData = EmProductImg::getWhere([['img_id', '=', $input['id']], ['product_id', '=', $input['product_id']]], '', false);
                foreach ($getData as $key) 
                {
                    @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/product/'.$key->image);
                    @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/product/thumb/'.$key->image);
                    @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/product/thumb_sm/'.$key->image);
                }
                EmProductImg::deleteData($input['id']);
                $result['trigger'] = 'yes';
                $result['notif'] = 'Image has been deleted.';    
            }
        }
        else if($input['form_action'] == "main-image")
        {
            $validator = Validator::make(request()->all(), [
                'product_id' => 'required',
                'id' => 'required',
            ],
            [
                'product_id.required' => 'Sorry, server can\'t response.',
                'id.required' => 'Please select image first.',
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
                $dataUpdate = ['order' => '1'];
                EmProductImg::updateData($input['id'], $dataUpdate, $input['product_id']);
                
                $result['trigger'] = 'yes';
                $result['notif'] = 'Main image has been selected.';    
            }
        }
        else if($input['form_action'] == "upload-image")
        {
            $validator = Validator::make(request()->all(), [
                'product_id' => 'required',
                'up_image' => 'required',
                'up_image.*' => 'mimes:jpeg,png,jpg|max:2048'
            ],
            [
                'product_id.required' => 'Sorry, server can\'t response.',
                'up_image.required' => 'Please upload product\'s image.',
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
                //upload image
                $newSize = array('crop' => false, 'width' => 600, 'height' => 0);
                $smallSize = array('crop' => false, 'width' => 80, 'height' => 0);
                $getImageName = Common_helper::upload_image('product/', 'product/thumb/', $newSize, $request->file('up_image'), true, 'product/thumb_sm/', $smallSize);
                $tmpData = '';
                if(sizeof($getImageName) > 0)
                {
                    foreach ($getImageName as $key => $value) 
                    {
                        $dataInsert = 
                        [
                            'product_id' => $input['product_id'],
                            'image' => $value
                        ];
                        $getLastId = EmProductImg::insertData($dataInsert);

                        $tmpData .= '
                            <div class="col-sm-2 data-image">
                                <img class="img-responsive select-main-image" data-id="'.$getLastId.'" src="'.asset(env('URL_IMAGE').'product/thumb/'.$value).'">
                                <button type="button" class="btn btn-danger no-radius delete-image" data-id="'.$getLastId.'">Delete <i class="fa fa-trash"></i></button>
                            </div>
                        ';
                    }
                }
                //-------------------------
                
                $result['trigger'] = 'yes';
                $result['notif'] = 'New images has been uploaded.';  
                $result['image']  = $tmpData;
            }
        }
        else if($input['form_action'] == "update-data")
        {
            $validator = Validator::make(request()->all(), [
                'product_id' => 'required',
                // 'code_product' => 'required',
                'product_name' => 'required',
                // 'basic_price' => 'required',
                'price' => 'required',
                'main_category' => 'required',
                'unit' => 'required',
                // 'description' => 'required',
                // 'book' => 'required',
                'weight' => 'required',
                'order' => 'required',
            ],
            [
                'product_id.required' => 'Sorry, server can\'t response.',
                // 'code_product.required' => 'Please insert product code.',
                'product_name.required' => 'Please insert product name.',
                // 'basic_price.required' => 'Please insert basic price.',
                'price.required' => 'Please insert price.',
                'main_category.required' => 'Please insert main category.',
                'unit.required' => 'Please insert unit name.',
                'weight.required' => 'Please insert weight of product.',
                'order.required' => 'Please insert order of product.',
                // 'description.required' => 'Please insert description of product.',
                // 'book.required' => 'Please choose look book.',
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
                // $checkProductCode = EmProduct::getWhere([['product_code', '=', $input['code_product']], ['product_id', '!=', $input['product_id']], ['status', '!=', '2']], '', false);
                // if(sizeof($checkProductCode) > 0)
                // {
                //     $result['notif'] = 'Product code already exist.';
                // }
                // else
                // {
                    

                    //size cart
                    $newSize = array('crop' => false, 'width' => 600, 'height' => 0);
                    $smallSize = array('crop' => false, 'width' => 80, 'height' => 0);

                    $getSizeChart = "";
                    if(isset($input['size_chart'])){
                        if(!empty($input['size_chart'])){
                            $getSizeChart = Common_helper::upload_image('product/', 'product/thumb/', $newSize, $request->file('size_chart'), false, 'product/thumb_sm/', $smallSize);

                            $result['new_size_chart'] = asset(env('URL_IMAGE').'product/'.$getSizeChart);
                            $result['new_size_chart_thumb'] = asset(env('URL_IMAGE').'product/thumb/'.$getSizeChart);

                            $getData = EmProduct::getWhere([['product_id', '=', $input['product_id']]], '', false);
                            foreach ($getData as $key) 
                            {
                                if($key->size_chart != ""){
                                    @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/product/'.$key->size_chart);
                                    @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/product/thumb/'.$key->size_chart);
                                    @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/product/thumb_sm/'.$key->size_chart);
                                }
                            }
                        }
                    }
                    //========

                    $productCategory = Common_helper::set_product_category($input, 'insert');
                    $dataUpdate = 
                    [
                        'category_id' => $productCategory,
                        'product_name' => $input['product_name'],
                        'product_code' => $input['code_product'],
                        'description' => $input['description_text'],
                        'description_html' => $input['description'],
                        'price' => $input['price'],
                        // 'price_basic' => $input['basic_price'],
                        'discount' => $input['discount'],
                        'last_update' => strtotime(Common_helper::date_time_now()),
                        'unit' => $input['unit'],
                        'status' => $input['status'],
                        // 'book_id' => $input['book'],
                        'weight' => $input['weight'],
                        'order' => $input['order'],
                        // 'size_chart' => $getSizeChart,
                    ];
                    EmProduct::updateData($input['product_id'], $dataUpdate);
                    $result['trigger'] = 'yes';
                    $result['notif'] = 'Product has been changed.';
                // }
            }
        }

        echo json_encode($result);
    }

    public function addProduct()
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Product | Administrator',
            'title_page' => 'Product',
            'title_form' => 'Form add product',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_products').'"><i class="fa fa-file-text-o"></i> Product</a></li>
                                <li class="active"><i class="fa fa-plus"></i> Add product</li>
                            ',
            'data_categories' => EmProductCategory::getWhere([['status', '=', '1']], '(parent = \'\' or parent is null)', false),
            'data_book' => array(),
            'menu_order' => $this->menu_order,
            'masterProduct' => 'active',
            'new_order' => EmProduct::newOrder(),
        );
        return view('admin.product.product_add', $data);
    }

    public function addProductProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'product_name' => 'required',
            // 'basic_price' => 'required',
            // 'book' => 'required',
            'price' => 'required',
            'main_category' => 'required',
            'unit' => 'required',
            'weight' => 'required',
            'order' => 'required',
            // 'description' => 'required',
            'up_image' => 'required',
            'up_image.*' => 'mimes:jpeg,png,jpg|max:2048'
        ],
        [
            'product_name.required' => 'Please insert product name.',
            // 'basic_price.required' => 'Please insert basic price.',
            'price.required' => 'Please insert price.',
            'main_category.required' => 'Please insert main category.',
            'unit.required' => 'Please insert unit name.',
            'weight.required' => 'Please insert weight of product.',
            // 'description.required' => 'Please insert description of product.',
            'up_image.required' => 'Please upload product\'s image.',
            // 'book.required' => 'Please choose look book.',
            'order.required' => 'Please insert order of product.',
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
            // $productCode = Common_helper::create_product_code($input['code_product']);
            $productCode = $input['code_product'];

            //check product code
            // $checkProductCode = EmProduct::getWhere([['product_code', '=', $productCode], ['status', '!=', '2']], '', false);
            // if(sizeof($checkProductCode) > 0)
            // {
            //     $result['notif'] = 'Product code already exist.';
            // }
            // else
            // {
                $productCategory = Common_helper::set_product_category($input, 'insert');

                $newSize = array('crop' => false, 'width' => 600, 'height' => 0);
                $smallSize = array('crop' => false, 'width' => 80, 'height' => 0);

                //size cart
                $getSizeChart = "";
                if(isset($input['size_chart'])){
                    if(!empty($input['size_chart'])){
                        $getSizeChart = Common_helper::upload_image('product/', 'product/thumb/', $newSize, $request->file('size_chart'), false, 'product/thumb_sm/', $smallSize);
                    }
                }
                //========

                $dataInsert = 
                [
                    'category_id' => $productCategory,
                    'admin_id' => Session::get(env('SES_BACKEND_ID')),
                    'product_name' => $input['product_name'],
                    'product_code' => $productCode,
                    'description' => $input['description_text'],
                    'description_html' => $input['description'],
                    'price' => $input['price'],
                    // 'price_basic' => $input['basic_price'],
                    'discount' => $input['discount'],
                    'date_in' => strtotime(Common_helper::date_time_now()),
                    'last_update' => strtotime(Common_helper::date_time_now()),
                    'stock' => 0,
                    'unit' => $input['unit'],
                    'status' => '1',
                    // 'book_id' => $input['book'],
                    'weight' => $input['weight'],
                    'order' => $input['order'],
                    // 'size_chart' => $getSizeChart,
                ];
                $getLastID = EmProduct::insertData($dataInsert);

                //upload image
                $getImageName = Common_helper::upload_image('product/', 'product/thumb/', $newSize, $request->file('up_image'), true, 'product/thumb_sm/', $smallSize);
                if(sizeof($getImageName) > 0)
                {
                    $order = '1';
                    foreach ($getImageName as $key => $value) 
                    {
                        $dataInsert = 
                        [
                            'product_id' => $getLastID,
                            'image' => $value,
                            'order' => $order
                        ];
                        $order = null;
                        EmProductImg::insertData($dataInsert);
                    }
                }
                //-------------------------
                
                $result['trigger'] = 'yes';
                $result['notif'] = 'New product has been added.';
                $result['product_id'] = $getLastID;

                $result['order'] = EmProduct::newOrder();
            // }
        }
        echo json_encode($result);
    }

    public function addSkuProduct($id)
    {
        Common_helper::check_session_backend(true);

        $getProduct = EmProduct::getWhere([['product_id', '=', $id]], '', false);
        $productName = @$getProduct[0]->product_name;
        $productID = @$getProduct[0]->product_id;

        $data = array(
            'title' => 'Product SKU | Administrator',
            'title_page' => 'Product - SKU | <small>product : <i>"'.$productName.'"</i></small>',
            'title_form' => 'Form add product - SKU',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_products').'"><i class="fa fa-file-text-o"></i> Product</a></li>
                                <li class=""><a href="'.route('control_add_products').'"><i class="fa fa-plus"></i> Add product</a></li>
                                <li class="active"><i class="fa fa-plus"></i> Add SKU</li>
                            ',
            'new_order' => EmProductSku::newOrder($id),
            'menu_order' => $this->menu_order,
            'masterProduct' => 'active',
            'product_id' => $productID,
        );
        return view('admin.product.sku_add', $data);
    }

    public function addProductSkuProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'product_id' => 'required',
            'stock' => 'required',
        ],
        [
            'stock.required' => 'Please insert stock of sku.',
            'product_id.required' => 'Sorry, server can\'t response.',
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
           
            $skuCode = Common_helper::create_product_sku_code($input['sku_code'], $input['product_id']);
            $newOrder = $input['new_order'];
            if($input['new_order'] == '')
            {
                $newOrder = EmProductSku::newOrder();
            }

            $dataInsert = 
            [
                'sku_code' => $skuCode,
                'product_id' => $input['product_id'],
                'color_name' => strtoupper($input['color_name']),
                'color_hexa' => strtoupper($input['color']),
                'stock' => $input['stock'],
                'size' => strtoupper($input['size']),
                'order' => $newOrder,
                'date_in' => strtotime(Common_helper::date_time_now()),
                'last_update' => strtotime(Common_helper::date_time_now()),
                'status' => '1'
            ];
            $getLastID = EmProductSku::insertData($dataInsert);

            //add stock
            $model = EmProduct::find($input['product_id']);
            $model->stock += $input['stock'];
            $model->save();

            $result['trigger'] = 'yes';
            $result['notif'] = 'New sku has been added.';
            $result['new_order'] = EmProductSku::newOrder($input['product_id']);
        }
        echo json_encode($result);
    }

    public function editProductSkuProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'product_id' => 'required',
            'sku_id' => 'required',
            'stock' => 'required',
        ],
        [
            'stock.required' => 'Please insert stock of sku.',
            'product_id.required' => 'Sorry, server can\'t response.',
            'sku_id.required' => 'Sorry, server can\'t response.',
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

            //get old stock sku
            $getData = EmProductSku::getWhere([['product_id', '=', $input['product_id']], ['sku_id', '=', $input['sku_id']]], '', false);

            foreach ($getData as $key) 
            {
                $dataUpdate = 
                [
                    'sku_code' => $input['sku_code'],
                    'color_name' => strtoupper($input['color_name']),
                    'color_hexa' => strtoupper($input['color']),
                    'size' => strtoupper($input['size']),
                    'stock' => $input['stock'],
                    'order' => $input['new_order'],
                    'last_update' => strtotime(Common_helper::date_time_now()),
                    'status' => $input['status']
                ];
                EmProductSku::updateData($key->sku_id, $dataUpdate);

                //update stock
                $model = EmProduct::find($input['product_id']);
                $model->stock -= $key->stock;
                $model->stock += $input['stock'];
                $model->save();

                $result['trigger'] = 'yes';
                $result['notif'] = 'Product sku has been changed.';
            }
        }

        echo json_encode($result);
    }


    public function actionDataProduct(Request $request, $id = '')
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';

        if($id != '')
        {
            $dataUpdate = ['status' => '2'];
            EmProduct::updateData($id, $dataUpdate);
            $result['trigger'] = 'yes';
            $result['notif'] = 'Data has been deleted.';
        }
        else
        {
            $validator = Validator::make(request()->all(), [
                'data' => 'required',
                'status' => 'required',
            ],
            [
                'data.required' => 'Please choose data.',
                'status.required' => 'Server can\'t response.',
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
                $status = $input['status'];
                if($input['status'] == 'delete')
                {
                    $status = '2';
                    $result['notif'] = 'Data has been deleted.';
                }
                else
                {
                    $result['notif'] = 'Status data has been changed.';
                }

                foreach ($input['data'] as $key) 
                {
                    $dataUpdate = ['status' => $status];
                    EmProduct::updateData($key[0], $dataUpdate);
                }
                $result['trigger'] = 'yes';
            }
        }

        echo json_encode($result);
    }

    public function actionDataProductSku(Request $request, $id = '')
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';

        $deleteData = false;
        $idSku = array();
        if($id != '')
        {
            array_push($idSku, $id);

            $dataUpdate = ['status' => '2'];
            EmProductSku::updateData($id, $dataUpdate);
            $result['trigger'] = 'yes';
            $result['notif'] = 'Data has been deleted.';
            $deleteData = true;
        }
        else
        {
            $validator = Validator::make(request()->all(), [
                'data' => 'required',
                'status' => 'required',
            ],
            [
                'data.required' => 'Please choose data.',
                'status.required' => 'Server can\'t response.',
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
                $status = $input['status'];
                if($input['status'] == 'delete')
                {
                    $status = '2';
                    $result['notif'] = 'Data has been deleted.';
                    $deleteData = true;
                }
                else
                {
                    $result['notif'] = 'Status data has been changed.';
                }

                foreach ($input['data'] as $key) 
                {
                    $dataUpdate = ['status' => $status];
                    EmProductSku::updateData($key[0], $dataUpdate);
                    array_push($idSku, $key[0]);
                }
                $result['trigger'] = 'yes';
            }
        }

        //min stock
        if($deleteData)
        {
            for ($i=0; $i < count($idSku); $i++) 
            { 
                //get product id
                $getData = EmProductSku::getWhere([['sku_id', '=', $idSku[$i]]], '', false);

                $model = EmProduct::find($getData[0]->product_id);
                $model->stock -= $getData[0]->stock;
                $model->save();
            }
        }

        echo json_encode($result);
    }

    //product category
    public function listAll()
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Tree view - Product category | Administrator',
            'title_page' => 'Product category - tree view',
            'title_form' => 'Data product category',
            'information' => 'The following product category data has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> Product category - tree view</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterProductCategory' => 'active',
            'data_result' => EmProductCategory::where('status', '1')->where("parent", null)->orWhere("parent", '')->get(),
        );
        return view('admin.product.tree_view', $data);
    }

    public function categories($search = '')
    {
        Common_helper::check_session_backend(true);

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $data_categories = EmProductCategory::getWhere([['status', '!=', '2']], "(category like '%" . $search . "%')", true);
        $view_content = View::make('admin.product.category', compact('data_categories'));

        $data = array(
            'title' => 'Product category | Administrator',
            'title_page' => 'Product category',
            'title_form' => 'Data product category',
            'information' => 'The following product category data has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> Product category</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterProductCategory' => 'active',
            'search' => $search,
            'view_content' => $view_content,
            'url_search' => route('control_product_categories')
        );
        return view('admin.table_view_template', $data);
    }
    public function addCategory()
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Product category | Administrator',
            'title_page' => 'Product category',
            'title_form' => 'Form add product category',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_product_categories').'"><i class="fa fa-file-text-o"></i> Product category</a></li>
                                <li class="active"><i class="fa fa-plus"></i> Add category</li>
                            ',
            'data_categories' => EmProductCategory::getWhere([['status', '!=', '2']], '', false),
            'menu_order' => $this->menu_order,
            'masterProductCategory' => 'active',
        );
        return view('admin.product.category_add', $data);
    }

    public function addCategoryProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'category' => 'required',
        ],
        [
            'category.required' => 'Please insert category.',
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
            
            $getImage = null;
            if(isset($input['up_image'])){
                //upload image
                $newSize = array(
                    'crop' => false,
                    'width' => 480,
                    'height' => 0
                );
                $getImage = Common_helper::upload_image('category/', 'category/thumb/', $newSize, $request->file('up_image'));
            }

            $dataInsert = 
            [
                'category' => $input['category'],
                'parent' => $input['parent'],
                'description' => $input['description'],
                'image' => $getImage,
                'status' => '1'
            ];
            $getLastID = EmProductCategory::insertData($dataInsert);
            $result['trigger'] = 'yes';
            $result['notif'] = 'New category has been added.';
            $result['id'] = $getLastID;
        }

        echo json_encode($result);
    }

    public function editCategory($id)
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Product category | Administrator',
            'title_page' => 'Product category',
            'title_form' => 'Form edit product category',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_product_categories').'"><i class="fa fa-file-text-o"></i> Product category</a></li>
                                <li class="active"><i class="fa fa-edit"></i> Edit category</li>
                            ',
            'data_categories' => EmProductCategory::getWhere([['category_id', '!=', $id], ['status', '!=', '2']], '', false),
            'data_result' => EmProductCategory::getWhere([['category_id', '=', $id], ['status', '!=', '2']], '', false),
            'menu_order' => $this->menu_order,
            'masterProductCategory' => 'active',
        );
        return view('admin.product.category_edit', $data);
    }

    public function editCategoryProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'category_id' => 'required',
            'category' => 'required',
            'status' => 'required',
        ],
        [
            'category_id.required' => 'Server can\'t response.',
            'category.required' => 'Please insert category.',
            'status.required' => 'Please insert status category.',
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

            $result['new_image_ori'] = '';
            $result['new_image_thumb'] = '';

            if(isset($input['up_image'])){
                //upload image
                $newSize = array(
                    'crop' => false,
                    'width' => 480,
                    'height' => 0
                );
                $getImageName = Common_helper::upload_image('category/', 'category/thumb/', $newSize, $request->file('up_image'));
                if($getImageName != '')
                {
                    $dataUpdate = 
                    [
                        'category' => $input['category'],
                        'parent' => $input['parent'],
                        'description' => $input['description'],
                        'image' => $getImageName,
                        'status' => $input['status'],
                        'show_in_home' => $input['show_in_home']
                    ];

                    //delete image
                    $getData = EmProductCategory::getWhere([['category_id', '=', $input['category_id']]], false);
                    foreach ($getData as $key) 
                    {
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/category/'.$key->image);
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/category/thumb/'.$key->image);
                    }
                    $result['new_image_ori'] = asset(env('URL_IMAGE')).'/category/'.$getImageName;
                    $result['new_image_thumb'] = asset(env('URL_IMAGE')).'/category/thumb/'.$getImageName;
                }
            } else {
                $dataUpdate = 
                [
                    'category' => $input['category'],
                    'parent' => $input['parent'],
                    'description' => $input['description'],
                    'status' => $input['status'],
                    'show_in_home' => $input['show_in_home']
                ];
            }
            EmProductCategory::updateData($input['category_id'], $dataUpdate);
            $result['trigger'] = 'yes';
            $result['notif'] = 'Category has been changed.';
        }

        echo json_encode($result);
    }

    public function actionData(Request $request, $id = '')
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';

        if($id != '')
        {
            $dataUpdate = ['status' => '2'];
            EmProductCategory::updateData($id, $dataUpdate);
            $result['trigger'] = 'yes';
            $result['notif'] = 'Data has been deleted.';
        }
        else
        {
            $validator = Validator::make(request()->all(), [
                'data' => 'required',
                'status' => 'required',
            ],
            [
                'data.required' => 'Please choose data.',
                'status.required' => 'Server can\'t response.',
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
                $status = $input['status'];
                if($input['status'] == 'delete')
                {
                    $status = '2';
                    $result['notif'] = 'Data has been deleted.';
                }
                else
                {
                    $result['notif'] = 'Status data has been changed.';
                }

                foreach ($input['data'] as $key) 
                {
                    $dataUpdate = ['status' => $status];
                    EmProductCategory::updateData($key[0], $dataUpdate);
                }
                $result['trigger'] = 'yes';
            }
        }

        echo json_encode($result);
    }
}