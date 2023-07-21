<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmSubscribe;

class SubscribeController extends Controller
{
    private $menu_order = 6;

    public function index($search = '')
    {
        Common_helper::check_session_backend(true);

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $data_result = EmSubscribe::getWhere([['status', '!=', '2']], '', true);
        $view_content = View::make('admin.subscribe.subscribe', compact('data_result'));

        $data = array(
            'title' => 'Subscriber | Administrator',
            'title_page' => 'Subscriber',
            'title_form' => 'Data subscriber',
            'information' => 'The following data subscribe has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> Subscriber</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterCurrency' => 'active',
            'search' => $search,
            'view_content' => $view_content,
            'url_search' => route('control_subscribe')
        );
        return view('admin.table_view_template', $data);
    }

    public function actionData(Request $request, $id = '')
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';

        if($id != '')
        {
            $dataUpdate = ['status' => '2'];
            EmSubscribe::updateData($id, $dataUpdate);
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
                    EmSubscribe::updateData($key[0], $dataUpdate);
                }
                $result['trigger'] = 'yes';
            }
        }

        echo json_encode($result);
    }
}