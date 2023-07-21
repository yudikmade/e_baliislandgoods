<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\MShippingCostDefault;

class ShippingCostController extends Controller
{
    private $menu_order = 5;

    public function index($search = '')
    {
        Common_helper::check_session_backend(true);

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $data_result = MShippingCostDefault::getWhere([['status', '!=', '2']], "(category like '%" . $search . "%')", true);
        $view_content = View::make('admin.master.shipping.shipping', compact('data_result'));

        $data = array(
            'title' => 'Shipping cost | Administrator',
            'title_page' => 'Shipping cost',
            'title_form' => 'Data shipping cost',
            'information' => 'The following data shipping cost has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-paper-plane"></i> Shipping cost</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterCost' => 'active',
            'search' => $search,
            'view_content' => $view_content,
            'url_search' => route('control_shipping_cost')
        );
        return view('admin.table_view_template', $data);
    }

    public function edit($id)
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Shipping cost | Administrator',
            'title_page' => 'Shipping cost ',
            'title_form' => 'Form edit shipping cost',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_currency').'"><i class="fa fa-paper-plane"></i> Shipping cost</a></li>
                                <li class="active"><i class="fa fa-edit"></i> Edit Shipping cost </li>
                            ',
            'data_result' => MShippingCostDefault::getWhere([['shipping_cost_id', '=', $id]], '', false),
            'menu_order' => $this->menu_order,
            'masterCost' => 'active',
        );
        return view('admin.master.shipping.shipping_edit', $data);
    }

    public function editProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'shipping_cost_id' => 'required',
            'cost' => 'required',
        ],
        [
            'shipping_cost_id.required' => 'Server can\'t response.',
            'cost.required' => 'Please insert shipping cost.',
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
                'cost' => $input['cost'],
            ];
            MShippingCostDefault::updateData($input['shipping_cost_id'], $dataUpdate);
            $result['trigger'] = 'yes';
            $result['notif'] = 'Data has been changed.';
        }

        echo json_encode($result);
    }
}