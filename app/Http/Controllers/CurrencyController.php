<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\MCurrency;

class CurrencyController extends Controller
{
    private $menu_order = 5;

    public function index($search = '')
    {
        Common_helper::check_session_backend(true);

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $data_currency = MCurrency::getWhere([['status', '!=', '2']], "(code like '%" . $search . "%')", true);
        $view_content = View::make('admin.master.currency.currency', compact('data_currency'));

        $data = array(
            'title' => 'Currency | Administrator',
            'title_page' => 'Currency',
            'title_form' => 'Data currency',
            'information' => 'The following data currency has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-dollar"></i> Currency</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterCurrency' => 'active',
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
            'title' => 'Currency | Administrator',
            'title_page' => 'Currency',
            'title_form' => 'Form add currency',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_currency').'"><i class="fa fa-dollar"></i> Currency</a></li>
                                <li class="active"><i class="fa fa-plus"></i> Add currency</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterCurrency' => 'active',
        );
        return view('admin.master.currency.currency_add', $data);
    }

    public function addProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'code' => 'required',
            'symbol' => 'required',
            'rate' => 'required',
        ],
        [
            'code.required' => 'Please insert code.',
            'symbol.required' => 'Please insert symbol.',
            'rate.required' => 'Please insert rate currency to rupiah.',
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
                'code' => $input['code'],
                'symbol' => $input['symbol'],
                'rate' => $input['rate'],
                'status' => '1'
            ];
            MCurrency::insertData($dataInsert);
            $result['trigger'] = 'yes';
            $result['notif'] = 'New currency has been added.';
        }

        echo json_encode($result);
    }

    public function edit($id)
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Currency | Administrator',
            'title_page' => 'Currency',
            'title_form' => 'Form edit currency',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_currency').'"><i class="fa fa-dollar"></i> Currency</a></li>
                                <li class="active"><i class="fa fa-edit"></i> Edit currency</li>
                            ',
            'data_result' => MCurrency::getWhere([['currency_id', '=', $id], ['status', '!=', '2']], '', false),
            'menu_order' => $this->menu_order,
            'masterCurrency' => 'active',
        );
        return view('admin.master.currency.currency_edit', $data);
    }

    public function editProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'currency_id' => 'required',
            'code' => 'required',
            'symbol' => 'required',
            'rate' => 'required',
            'status' => 'required',
        ],
        [
            'currency_id.required' => 'Server can\'t response.',
            'code.required' => 'Please insert code.',
            'symbol.required' => 'Please insert symbol.',
            'rate.required' => 'Please insert rate currency to rupiah.',
            'status.required' => 'Please insert status currency.',
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
                'code' => $input['code'],
                'symbol' => $input['symbol'],
                'rate' => $input['rate'],
                'status' => $input['status']
            ];
            MCurrency::updateData($input['currency_id'], $dataUpdate);
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
            $getData = MCurrency::getWhere([['currency_id', '=', $id]], '', true);
            if($getData[0]->status_permanent != '1')
            {
                $dataUpdate = ['status' => '2'];
                MCurrency::updateData($id, $dataUpdate);
                $result['trigger'] = 'yes';
                $result['notif'] = 'Data has been deleted.';
            }
            else
            {
                $result['notif'] = 'Sorry, you can\'t delete this data.';   
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
                    $getData = MCurrency::getWhere([['currency_id', '=', $key[0]]], '', true);
                    if($getData[0]->status_permanent != '1')
                    {
                        $dataUpdate = ['status' => $status];
                        MCurrency::updateData($key[0], $dataUpdate);
                    }
                }
                $result['trigger'] = 'yes';
            }
        }

        echo json_encode($result);
    }
}