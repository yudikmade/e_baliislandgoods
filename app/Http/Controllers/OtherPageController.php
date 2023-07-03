<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmOtherPage;

class OtherPageController extends Controller
{
    private $menu_order = 7;

    public function index($search = '')
    {
        Common_helper::check_session_backend(true);

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $data_result = EmOtherPage::getWhere([], "(page like '%" . $search . "%')", true);
        $view_content = View::make('admin.master.other_page.op', compact('data_result'));

        $data = array(
            'title' => 'Other page | Administrator',
            'title_page' => 'Other page',
            'title_form' => 'Data Other page',
            'information' => 'The following data other page has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> Other page</li>
                            ',
            'menu_order' => $this->menu_order,
            'search' => $search,
            'view_content' => $view_content,
            'url_search' => route('control_other_page')
        );
        return view('admin.table_view_template', $data);
    }
    public function add()
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Other page | Administrator',
            'title_page' => 'Other page',
            'title_form' => 'Form add other page',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_other_page').'"><i class="fa fa-file-text-o"></i> Other page</a></li>
                                <li class="active"><i class="fa fa-plus"></i> Add other page</li>
                            ',
            'menu_order' => $this->menu_order,
        );
        return view('admin.master.other_page.op_add', $data);
    }

    public function addProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'page' => 'required',
        ],
        [
            'page.required' => 'Please add page.',
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

            if(count(EmOtherPage::getWhere([['page', '=', $input['page']]], '', false)) == 0)
            {
                $dataInsert = 
                [
                    'page' => $input['page'],
                    'slug' => Common_helper::clean(strtolower($input['page'])),
                    'description' => $input['description'],
                    'status' => '1',
                ];
                EmOtherPage::insertData($dataInsert);
                $result['trigger'] = 'yes';
                $result['notif'] = 'New other page has been added.';
            }
            else
            {
                $result['notif'] = 'Other page ('.$input['page'].') already exist.';
            }
        }

        echo json_encode($result);
    }

    public function edit($id)
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Other page | Administrator',
            'title_page' => 'Other page',
            'title_form' => 'Form edit Other page',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_other_page').'"><i class="fa fa-file-text-o"></i> Other page</a></li>
                                <li class="active"><i class="fa fa-edit"></i> Edit Other page</li>
                            ',
            'data_result' => EmOtherPage::getWhere([['id', '=', $id]], '', false),
            'menu_order' => $this->menu_order,
        );
        return view('admin.master.other_page.op_edit', $data);
    }

    public function editProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'id' => 'required',
            'page' => 'required',
        ],
        [
            'id.required' => 'Sorry, server can\'t response.',
            'page.required' => 'Please choose social media.',
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
                'page' => $input['page'],
                'slug' => Common_helper::clean(strtolower($input['page'])),
                'description' => $input['description'],
                'status' => $input['status'],
            ];
            EmOtherPage::updateData($input['id'], $dataUpdate);
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
            EmOtherPage::where('id', $id)->delete();
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
                    EmOtherPage::where('id', $key[0])->delete();
                }
                $result['trigger'] = 'yes';
            }
        }

        echo json_encode($result);
    }
}