<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmCoupon;

class CouponController extends Controller
{
    private $menu_order = 5;

    public function index($search = '')
    {
        Common_helper::check_session_backend(true);

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $data_coupon = EmCoupon::getWhere([['status', '!=', '2']], "(coupon_code like '%" . $search . "%')", true);
        $view_content = View::make('admin.master.coupon.coupon', compact('data_coupon'));

        $data = array(
            'title' => 'Coupon | Administrator',
            'title_page' => 'Coupon',
            'title_form' => 'Data coupon',
            'information' => 'The following data coupon has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> Coupon</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterCoupon' => 'active',
            'search' => $search,
            'view_content' => $view_content,
            'url_search' => route('control_currency')
        );
        return view('admin.table_view_template', $data);
    }
    public function add()
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Coupon | Administrator',
            'title_page' => 'Coupon',
            'title_form' => 'Form add coupon',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_coupon').'"><i class="fa fa-file-text-o"></i> Coupon</a></li>
                                <li class="active"><i class="fa fa-plus"></i> Add coupon</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterCoupon' => 'active',
        );
        return view('admin.master.coupon.coupon_add', $data);
    }

    public function addProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'code' => 'required',
            'count' => 'required',
            'discount' => 'required',
        ],
        [
            'code.required' => 'Please insert coupon code.',
            'count.required' => 'Please insert amount of usage.',
            'rate.required' => 'Please insert discount.',
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
            
            $dataInsert = 
            [
                'coupon_code' => $input['code'],
                'use_count' => $input['count'],
                'discount' => $input['discount'],
                'status' => '1'
            ];
            EmCoupon::insertData($dataInsert);
            $result['trigger'] = 'yes';
            $result['notif'] = 'New coupon has been added.';
        }

        echo json_encode($result);
    }

    public function edit($id)
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Coupon | Administrator',
            'title_page' => 'Coupon',
            'title_form' => 'Form edit coupon',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_coupon').'"><i class="fa fa-file-text-o"></i> Coupon</a></li>
                                <li class="active"><i class="fa fa-edit"></i> Edit coupon</li>
                            ',
            'data_result' => EmCoupon::getWhere([['coupon_id', '=', $id], ['status', '!=', '2']], '', false),
            'menu_order' => $this->menu_order,
            'masterCoupon' => 'active',
        );
        return view('admin.master.coupon.coupon_edit', $data);
    }

    public function editProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'coupon_id' => 'required',
            'code' => 'required',
            'count' => 'required',
            'discount' => 'required',
            'status' => 'required',
        ],
        [
            'coupon_id.required' => 'Server can\'t response.',
            'code.required' => 'Please insert coupon code.',
            'count.required' => 'Please insert amount of usage.',
            'discount.required' => 'Please insert discount.',
            'status.required' => 'Please insert status coupon.',
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
            
            $dataUpdate = 
            [
                'coupon_code' => $input['code'],
                'use_count' => $input['count'],
                'discount' => $input['discount'],
                'status' => $input['status']
            ];
            EmCoupon::updateData($input['coupon_id'], $dataUpdate);
            $result['trigger'] = 'yes';
            $result['notif'] = 'Data has been changed.';
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
            EmCoupon::updateData($id, $dataUpdate);
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
                    EmCoupon::updateData($key[0], $dataUpdate);
                }
                $result['trigger'] = 'yes';
            }
        }

        echo json_encode($result);
    }
}