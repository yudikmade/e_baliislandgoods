<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmTransaction;
use App\Models\EmTransactionMeta;
use App\Models\EmTransactionDetail;
use App\Models\EmTransactionShipping;
use App\Models\EmCustomer;
use App\Models\EmProofOfPayment;
use App\Models\MBank;
use App\Models\EmProduct;
use App\Models\EmProductSku;

use App\Models\ExportTransaction;
use App\Models\ExportDetailTransaction;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    protected $limiPage = 10;
    private $menu_order = 3;

    public function index($payment = 'all-payments', $status = 'all-status', $search = '', $date_transaction = '')
    {
        Common_helper::check_session_backend(true);

        $search = rtrim($search, '-');

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $date_search = array();
        if($date_transaction != '')
        {
            $tmpData = explode('.', $date_transaction);
            if(sizeof($tmpData) == 2)
            {
                $date_search = array(strtotime($tmpData[0]), strtotime($tmpData[1]));
            }
        }

        $data_result = EmTransaction::transactionData($payment, $status, $date_search, $search, Session::get(env('SES_BACKEND_ID')));

        $data = array(
            'title' => 'Transaction | Administrator',
            'title_page' => 'Transaction',
            'title_form' => 'Data transaction',
            'information' => 'The following data transaction has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> Transaction</li>
                            ',
            'menu_order' => $this->menu_order,
            'payment' => $payment,
            'search' => $search,
            'status' => $status,
            'date_transaction' => $date_transaction,
            'url_search' => route('control_transactions')
        );
        return view('admin.transaction.transaction', $data, compact('data_result'));
    }

    public function transactionExport($payment = 'all-payments', $status = 'all-status', $search = '', $date_transaction = '')
    {
        Common_helper::check_session_backend(true);

        $search = rtrim($search, '-');

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $date_search = array();
        if($date_transaction != '')
        {
            $tmpData = explode('.', $date_transaction);
            if(sizeof($tmpData) == 2)
            {
                $date_search = array(strtotime($tmpData[0]), strtotime($tmpData[1]));
            }
        }

        // $data_result = EmTransaction::transactionData($payment, $status, $date_search, $search);

        return Excel::download(new ExportTransaction($payment, $status, $date_search, $search, Session::get(env('SES_BACKEND_ID'))), 'transaction'.date('YmdHis').'.xlsx');
    }

    public function detailTransaction($status = 'all-status', $search = '', $date_transaction = '')
    {
        Common_helper::check_session_backend(true);

        $search = rtrim($search, '-');

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $date_search = array();
        if($date_transaction != '')
        {
            $tmpData = explode('.', $date_transaction);
            if(sizeof($tmpData) == 2)
            {
                $date_search = array(strtotime($tmpData[0]), strtotime($tmpData[1]));
            }
        }

        $data_result = EmTransactionDetail::transactionDetailData($status, $date_search, $search, true, Session::get(env('SES_BACKEND_ID')));

        $data = array(
            'title' => 'Detail transaction | Administrator',
            'title_page' => 'Detail transaction',
            'title_form' => 'Data detail transaction',
            'information' => 'The following data detail transactions has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> Detail transaction</li>
                            ',
            'menu_order' => $this->menu_order,
            'search' => $search,
            'status' => $status,
            'date_transaction' => $date_transaction,
            'url_search' => route('control_detail_transactions')
        );
        return view('admin.transaction.transaction_detail_data', $data, compact('data_result'));
    }

    public function detailTransactionExport($status = 'all-status', $search = '', $date_transaction = '')
    {
        Common_helper::check_session_backend(true);

        $search = rtrim($search, '-');

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $date_search = array();
        if($date_transaction != '')
        {
            $tmpData = explode('.', $date_transaction);
            if(sizeof($tmpData) == 2)
            {
                $date_search = array(strtotime($tmpData[0]), strtotime($tmpData[1]));
            }
        }

        // $data_result = EmTransaction::transactionData($payment, $status, $date_search, $search);

        return Excel::download(new ExportDetailTransaction($status, $date_search, $search, Session::get(env('SES_BACKEND_ID'))), 'detail-transaction'.date('YmdHis').'.xlsx');
    }

    public function detail($id)
    {
        Common_helper::check_session_backend(true);

        $getTrans = EmTransaction::getWhere([['transaction_id', '=', $id], ['status', '!=', '0'], ['status', '!=', '6']], '', false);

        $getCustomer = array();
        if(sizeof($getTrans) >0)
        {
            $getCustomer = EmCustomer::getWhere([['customer_id', '=', $getTrans[0]->customer_id]], '', false);
        }

        $getShipping = array();
        if(sizeof($getTrans) >0)
        {
            $getShipping = EmTransactionShipping::getWhere([['transaction_id', '=', $getTrans[0]->transaction_id]], '', false);
        }

        $getDetailTrans = array();
        if(sizeof($getTrans) >0)
        {
            $getDetailTrans = EmTransactionDetail::transactionDetail([['em_transaction_detail.transaction_id', '=', $getTrans[0]->transaction_id],['em_product.admin_id', '=', Session::get(env('SES_BACKEND_ID'))]]);
        }

        $getCustomerMeta = array();
        $paypal = array();
        if(sizeof($getCustomer) == 0)
        {
            $getCustomerMeta = EmTransactionMeta::getWhere([['transaction_id', '=', $getTrans[0]->transaction_id]]);

            $paypal = array(
                'transaction_id' => EmTransactionMeta::getMeta(array('transaction_id' => $getTrans[0]->transaction_id, 'meta_key' => 'transaction_id')),
                'payer_id' => EmTransactionMeta::getMeta(array('transaction_id' => $getTrans[0]->transaction_id, 'meta_key' => 'payerid')),
                'payment_status' => EmTransactionMeta::getMeta(array('transaction_id' => $getTrans[0]->transaction_id, 'meta_key' => 'payment_paypal')),
            );
        }

        $data = array(
            'title' => 'Transaction | Administrator',
            'title_page' => 'Transaction',
            'title_form' => 'Detail transaction',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_transactions').'"><i class="fa fa-file-text-o"></i> Transaction</a></li>
                                <li class="active"><i class="fa fa-eye"></i> Detail</li>
                            ',
            'data_transaction' => $getTrans,
            'data_transaction_detail' => $getDetailTrans,
            'data_customer' => $getCustomer,
            'data_customer_meta' => $getCustomerMeta,
            'data_shipping' => $getShipping,
            'menu_order' => $this->menu_order,
            'paypal' => $paypal,
        );
        return view('admin.transaction.transaction_detail', $data);
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
                EmProductImg::updateData($input['id'], $dataUpdate);
                
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
                $newSize = array(
                    'crop' => false,
                    'width' => 400,
                    'height' => 0
                );
                $getImageName = Common_helper::upload_image('product/', 'product/thumb/', $newSize, $request->file('up_image'), true);
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
                'code_product' => 'required',
                'product_name' => 'required',
                'basic_price' => 'required',
                'price' => 'required',
                'main_category' => 'required',
                'unit' => 'required',
                'description' => 'required',
                'book' => 'required',
            ],
            [
                'product_id.required' => 'Sorry, server can\'t response.',
                'code_product.required' => 'Please insert product code.',
                'product_name.required' => 'Please insert product name.',
                'basic_price.required' => 'Please insert basic price.',
                'price.required' => 'Please insert price.',
                'main_category.required' => 'Please insert main category.',
                'unit.required' => 'Please insert unit name.',
                'description.required' => 'Please insert description of product.',
                'book.required' => 'Please choose look book.',
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
                
                $productCategory = Common_helper::set_product_category($input, 'insert');
                $dataUpdate = 
                [
                    'category_id' => $productCategory,
                    'product_name' => $input['product_name'],
                    'product_code' => $input['code_product'],
                    'description' => $input['description_text'],
                    'description_html' => $input['description'],
                    'price' => $input['price'],
                    'price_basic' => $input['basic_price'],
                    'discount' => $input['discount'],
                    'last_update' => strtotime(Common_helper::date_time_now()),
                    'unit' => $input['unit'],
                    'status' => $input['status'],
                    'book_id' => $input['book']
                ];
                EmProduct::updateData($input['product_id'], $dataUpdate);
                $result['trigger'] = 'yes';
                $result['notif'] = 'Product has been changed.';
            }
        }

        echo json_encode($result);
    }

    public function actionData(Request $request, $id = '', $action = '')
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';

        if($id != '')
        {
            if($action == 'invoice')
            {   
                $result['trigger'] = 'no';
                $result['notif'] = 'Invoice failed to send.';

                $emailCustomer = '';
                $message = array();
                $getTransaction = EmTransaction::getWhereLastOne([['transaction_id', '=', $id]]);
                if(isset($getTransaction->transaction_id))
                {
                    $message['unique_code'] = $getTransaction->unique_code;
                    $message['invoice_number'] = $getTransaction->transaction_code;
                    $message['first_name'] = '';
                    $message['last_name'] = '';
                    if($getTransaction->customer_id != '')
                    {
                        $getCustomer = EmCustomer::getWhere([['customer_id', '=', $getTransaction->customer_id]]);
                        foreach ($getCustomer as $key) 
                        {
                            $emailCustomer = $key->email;
                            $message['first_name'] = $key->first_name;
                            $message['last_name'] = $key->last_name;
                        }
                    }
                    else
                    {
                        $getMeta = EmTransactionMeta::getMeta(array('transaction_id' => $getTransaction->transaction_id, 'meta_key' => 'email'));
                        if(isset($getMeta->meta_description))
                        {
                            if($getMeta->meta_description != '')
                            {
                                $emailCustomer = $getMeta->meta_description;

                                $getMeta = EmTransactionMeta::getMeta(array('transaction_id' => $getTransaction->transaction_id, 'meta_key' => 'first_name'));
                                if(isset($getMeta->meta_description))
                                {
                                    if($getMeta->meta_description != '')
                                    {
                                        $message['first_name'] = $getMeta->meta_description;
                                        $getMeta = EmTransactionMeta::getMeta(array('transaction_id' => $getTransaction->transaction_id, 'meta_key' => 'last_name'));
                                        $message['last_name'] = $getMeta->meta_description;
                                    }
                                }
                            }
                        }
                    }

                    $getDetails = EmTransactionDetail::transactionDetail([['em_transaction_detail.transaction_id', '=', $getTransaction->transaction_id],['em_product.admin_id', '=', Session::get(env('SES_BACKEND_ID'))]]);
                    $tmpData = array();
                    foreach ($getDetails as $key) 
                    {
                        $tmp = array(
                            'product_id' => $key->product_id,
                            'product_name' => $key->product_name,
                            'size' => $key->size,
                            'color_name' => $key->color_name,
                        );

                        array_push($tmpData, $tmp);
                    }

                     $message['details'] = $tmpData;

                    if($getTransaction->status == '1')
                    {
                        if($emailCustomer != '' && sizeof($message) > 0)
                        {
                            Common_helper::send_email($emailCustomer, $message, 'Complete your purchase at '.env('AUTHOR_SITE'), 'invoice');
                        }

                        $result['trigger'] = 'yes';
                        $result['notif'] = 'Invoice has been sent.';
                    }
                }
            }
            else
            {
                $dataUpdate = ['status' => '6'];
                EmTransaction::updateData($id, $dataUpdate);

                $couponData = Common_helper::getCouponTransaction($id);
                Common_helper::manageAmoutofUsage($couponData, 'plus');

                $result['trigger'] = 'yes';
                $result['notif'] = 'Data has been deleted.';
            }
        }
        else
        {
            $validator = Validator::make(request()->all(), [
                'data' => '',
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

                //get customer
                $status = $input['status'];
                if($input['status'] == '6')
                {
                    $result['notif'] = 'Data has been deleted.';
                }
                else
                {
                    $result['notif'] = 'Status data has been changed.';
                }

                if(isset($input['form_action']))
                {
                    $sendEmail = false;
                    if(isset($input['send-email']))
                    {
                        $sendEmail = true;
                    }

                    //get customer
                    $customer_id = '';
                    $getTransaction = EmTransaction::getWhere([['transaction_id', '=', $input['transaction_id']]], '', false);
                    foreach ($getTransaction as $key) 
                    {
                        $this->actionUpdateStatusTransaction($key->transaction_id, $key->customer_id, $status, $sendEmail);
                    }
                }
                else
                {
                    foreach ($input['data'] as $key) 
                    {
                        $explData = explode('-', $key[0]);
                        if(count($explData) == 2)
                        {
                            $this->actionUpdateStatusTransaction($explData[0], $explData[1], $status);

                            if($status == '6')
                            {
                                $couponData = Common_helper::getCouponTransaction($explData[0]);
                                Common_helper::manageAmoutofUsage($couponData, 'plus');
                            }
                        }
                    }
                }
                $result['trigger'] = 'yes';
            }
        }

        echo json_encode($result);
    }

    private function actionUpdateStatusTransaction($transaction_id, $customer_id, $status, $sendEmail = true)
    {
        $getCustomer = EmCustomer::getWhere([['customer_id', '=', $customer_id]], '', false);
        $emailCustomer = '';
        $nameCustomer = '';
        foreach ($getCustomer as $value) 
        {
            $emailCustomer = $value->email;
            $nameCustomer = $value->first_name;
        }

        if(sizeof($getCustomer) == 0)
        {
            $getCustomerMeta = EmTransactionMeta::getWhere([['transaction_id', '=', $transaction_id]]);
            foreach ($getCustomerMeta as $key) 
            {
                if($key->meta_key == 'email')
                {
                    $emailCustomer = $key->meta_description;
                }

                if($key->meta_key == 'name');
                {
                    $tmpData = explode(',', $key->meta_description);
                    $nameCustomer = $tmpData[0];
                }
            }
        }

        //update meta last update
        EmTransactionMeta::updateMeta(
            array(
                'transaction_id' => $transaction_id,
                'meta_key' => 'last_update',
                'meta_description' => strtotime(Common_helper::date_time_now())
            )
        );

        $getTransaction = EmTransaction::getWhere([['transaction_id', '=', $transaction_id]], '', false);

        $message = '';

        if($status == '2')
        {
            $message = 'We have received payment for your transaction. We will process your transaction.';
        }
        else if($status == '3')
        {
            $message = 'We are currently processing your transaction.';   
        }
        else if($status == '4')
        {
            $getTransactionShipping = EmTransactionShipping::getWhere([['transaction_id', '=', $transaction_id]], '', false);

            if($getTransactionShipping[0]->country_id == '236')
            {
                $message = 'We have sent your transaction, with an estimated delivery time of '.$getTransactionShipping[0]->shipping_estimate.' days.';   
            }
            else
            {
                $message = 'We have sent your transaction, with an estimated delivery time of '.$getTransactionShipping[0]->shipping_estimate.' days.';   
            }
        }
        else if($status == '5')
        {
            $message = 'At your request or policy of our company, we have canceled your transaction.';   
        }

        if($status != '6')
        {
            if($sendEmail)
            {
                if($emailCustomer != '')
                {
                    $message = array(
                        'unique_code' => $getTransaction[0]->unique_code,
                        'transaction_code' => $getTransaction[0]->transaction_code,
                        'name' => $nameCustomer,
                        'message' => $message,
                    );
                    Common_helper::send_email($emailCustomer, $message, 'Status transaction', 'status_transaction');
                }
            }
        }

        $dataUpdate = ['status' => $status];
        if($status == '2')
        {
            $dataUpdate = ['status' => $status, 'payment_status' => '1'];
        }
        EmTransaction::updateData($transaction_id, $dataUpdate);

        if($status == '5' || $status == '6')
        {
            $getDetail = EmTransactionDetail::getWhere([['transaction_id', '=', $transaction_id], ['status', '=', '1']], '', false);

            $dataUpdate = ['status' => '0'];
            foreach ($getDetail as $key) 
            {
                $this->update_stock_product($key->detail_id, $key->product_id, $key->sku_id, $key->qty, $dataUpdate);
            }
        }
    }

    public function actionDataProof(Request $request, $id = '')
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';

        if($id != '')
        {
            $dataUpdate = ['status' => '3'];
            EmProofOfPayment::updateData($id, $dataUpdate);
            self::deleteProofOfPayment($id);
            $result['trigger'] = 'yes';
            $result['notif'] = 'Data has been deleted.';
        }
        else
        {
            $validator = Validator::make(request()->all(), [
                'data' => '',
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

                if($input['status'] == '3')
                {
                    $result['notif'] = 'Data has been deleted.';
                }
                else
                {
                    $result['notif'] = 'Status data has been changed.';
                }

                if(isset($input['form_action']))
                {
                    $sendEmail = false;
                    if(isset($input['send-email']))
                    {
                        $sendEmail = true;
                    }
                    $this->actionUpdateStatusProof($input['proof_id'], $status, $sendEmail);
                }
                else
                {
                    foreach ($input['data'] as $key) 
                    {
                        $this->actionUpdateStatusProof($key[0], $status);
                    }
                }
                $result['trigger'] = 'yes';
            }
        }

        echo json_encode($result);
    }

    private function actionUpdateStatusProof($proof_id, $status, $sendEmail = true)
    {
        //get customer
        $getData = EmProofOfPayment::getWhere([['proof_id', '=', $proof_id]], '', array(), false);
        foreach ($getData as $value) 
        {
            $dataUpdate = [
                'status' => $status,
                'approve_date' => strtotime(Common_helper::date_time_now())
            ];
            
            $message = '';
            if($status == '1')
            {
                $message = 'Proof of payment with transaction number '.$getData[0]->transaction_code.' has been received. Thank you for making a payment, we will immediately process your order.';
            }
            else if($status == '2')
            {
                $message = 'Sorry, we reject the proof of payment fro transaction number '.$getData[0]->transaction_code.', please confirm payment according to the invoice you received. Thank you.';   
            }
            else
            {
                $dataUpdate = ['status' => $status];
                self::deleteProofOfPayment($proof_id);
            }

            if($status != '3')
            {
                if($sendEmail)
                {
                    $message = array(
                        'transaction_code' => $getData[0]->transaction_code,
                        'name' => $getData[0]->full_name,
                        'message' => $message,
                    );
                    Common_helper::send_email($getData[0]->email, $message, 'Re - confirmation payment', 're_confirmation_payment');
                }
            }

            EmProofOfPayment::updateData($proof_id, $dataUpdate);
        }
    }

    private function deleteProofOfPayment($proof_id){
        $getData = EmProofOfPayment::where('proof_id', $proof_id)->first();
        if($getData){
            @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/proofofpayment/'.$getData->proof_of_payment);
            @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/proofofpayment/thumb/'.$getData->proof_of_payment);
        }
    }

    public function actionDataDetail(Request $request, $id = '')
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, this item of transaction can\'t canceled';

        if($id != '')
        {
            $getData = EmTransactionDetail::getWhere([['detail_id', '=', $id], ['status', '=', '1']], '', false);
            foreach ($getData as $key) 
            {
                $dataUpdate = ['status' => '0'];
                $this->update_stock_product($key->detail_id, $key->product_id, $key->sku_id, $key->qty, $dataUpdate);

                $result['trigger'] = 'yes';
                $result['notif'] = 'Item has been canceled.';
            }
        }
        echo json_encode($result);
    }

    private function update_stock_product($detail_trans_id, $product_id, $sku_id, $qty, $dataUpdate)
    {
        EmTransactionDetail::updateData($detail_trans_id, $dataUpdate);

        //add stock
        $model = EmProduct::find($product_id);
        $model->stock += $qty;
        $model->save();

        $model = EmProductSku::find($sku_id);
        $model->stock += $qty;
        $model->save();
    }

    public function checkOrderCancel()
    {
        $getTransaction = EmTransaction::getWhere([['status', '=', '1']]);

        foreach ($getTransaction as $key) 
        {
            $getTimezone = EmTransactionMeta::getMeta(array('transaction_id' => $key->transaction_id, 'meta_key' => 'timezone'));
            if(isset($getTimezone->meta_description))
            {
                $getTimerTrans = \App\Helper\Common_helper::timerCheckout($key->transaction_date, $getTimezone->meta_description);
                if(!$getTimerTrans['limit'])
                {
                    echo $key->transaction_id.' '.Common_helper::data_date($key->transaction_date).' limit<br>';

                    //update status and stock
                    $dataUpdate = ['status' => '5'];
                    EmTransaction::updateData($key->transaction_id, $dataUpdate);

                    $getDetail = EmTransactionDetail::getWhere([['transaction_id', '=', $key->transaction_id], ['status', '=', '1']], '', false);
                    $dataUpdate = ['status' => '0'];
                    foreach ($getDetail as $key) 
                    {
                        $this->update_stock_product($key->detail_id, $key->product_id, $key->sku_id, $key->qty, $dataUpdate);
                    }
                }
            }
        }

    }
}