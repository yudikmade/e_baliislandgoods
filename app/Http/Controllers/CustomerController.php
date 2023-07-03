<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmCustomer;
use App\Models\EmCustomerShipping;
use App\Models\ExportCustomer;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    private $limitPage = 20;
    private $menu_order = 4;

    public function index($search = '')
    {
        Common_helper::check_session_backend(true);

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $data_result = EmCustomer::getWhere([['status', '!=', '2']], "(customer_name like '%" . $search . "%' OR email like '%" . $search . "%' OR phone_number like '%" . $search . "%')", true);
        $view_content = View::make('admin.customer.customer', compact('data_result'));
        $data = array(
            'title' => 'Customer | Administrator',
            'title_page' => 'Customer',
            'title_form' => 'Data customer',
            'information' => 'The following data customer has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-users"></i> Customer</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterCustomer' => 'active',
            'search' => $search,
            'view_content' => $view_content,
            'url_search' => route('control_customers'),
            'url_export' => route('control_customers_export')
        );
        return view('admin.table_view_template', $data);
    }

    public function exportCustomer($search = '')
    {
        Common_helper::check_session_backend(true);
        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        return Excel::download(new ExportCustomer($search), 'customer'.date('YmdHis').'.xlsx');
    }

    public function detail(Request $request, $id = '')
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';
        if($id != '')
        {
            $result['trigger'] = 'yes';
            $result['customer'] = EmCustomer::select('customer_id', 'email', 'first_name', 'last_name', 'phone_number', 'status')->where([['status', '!=', '2'], ['customer_id', '=', $id]])->get();
            $result['shipping'] = EmCustomerShipping::getWhere([['status', '=', '1'], ['customer_id', '=', $id]], '', false);
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
            EmCustomer::updateData($id, $dataUpdate);
            $result['trigger'] = 'yes';
            $result['notif'] = 'Data has been deleted.';
        }
        else
        {
            $input = $request->all();

            if(isset($input['form_action']))
            {
                if($input['form_action'] == 'reset-password')
                {
                    $newPassword = rand(111111, 999999);
                    $newPassEncrypt = Common_helper::password_encryption($newPassword);

                    $getData = EmCustomer::getWhere([['customer_id', '=', $input['customer_id']], ['status', '!=', '2']], '', false);
                    foreach ($getData as $key) 
                    {                        
                        $dataUpdate = ['password' => $newPassEncrypt];
                        EmCustomer::updateData($key->customer_id, $dataUpdate);   

                        $message = array(
                            'name' => $key->customer_name,
                            'new_password' => $newPassword
                        );
                        Common_helper::send_email($key->email, $message, 'New password', 'new_password');

                        $result['trigger'] = 'yes';
                        $result['notif'] = 'Password has been changed and sent to customer\'s email.';
                    }
                }
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
                        EmCustomer::updateData($key[0], $dataUpdate);
                    }
                    $result['trigger'] = 'yes';
                }
            }
        }

        echo json_encode($result);
    }
}