<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmFaq;

class FaqController extends Controller
{
    private $menu_order = 5;

    public function index($search = '')
    {
        Common_helper::check_session_backend(true);

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $data_result = EmFaq::getWhere([], "(question like '%" . $search . "%')", true);
        $view_content = View::make('admin.master.faq.faq', compact('data_result'));

        $data = array(
            'title' => 'FAQ | Administrator',
            'title_page' => 'FAQ',
            'title_form' => 'Data FAQ',
            'information' => 'The following data FAQ has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> FAQ</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterFaq' => 'active',
            'search' => $search,
            'view_content' => $view_content,
            'url_search' => route('control_faq')
        );
        return view('admin.table_view_template', $data);
    }
    public function add()
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'FAQ | Administrator',
            'title_page' => 'FAQ',
            'title_form' => 'Form add new FAQ',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_faq').'"><i class="fa fa-file-text-o"></i> FAQ</a></li>
                                <li class="active"><i class="fa fa-plus"></i> Add new data</li>
                            ',
            'menu_order' => $this->menu_order,
            'new_order' => EmFaq::newOrder(),
            'masterFaq' => 'active',
        );
        return view('admin.master.faq.faq_add', $data);
    }

    public function addProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'question' => 'required',
            'answer_text' => 'required',
        ],
        [
            'bank_name.required' => 'Please insert question.',
            'answer_text.required' => 'Please insert answer.',
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
                'question' => $input['question'],
                'answer' => $input['answer'],
                'order' => $input['order'],
            ];
            EmFaq::insertData($dataInsert);
            $result['trigger'] = 'yes';
            $result['notif'] = 'New FAQ has been added.';
            $result['order'] = EmFaq::newOrder();
        }

        echo json_encode($result);
    }

    public function edit($id)
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'FAQ | Administrator',
            'title_page' => 'FAQ',
            'title_form' => 'Form edit FAQ',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_bank').'"><i class="fa fa-file-text-o"></i> FAQ</a></li>
                                <li class="active"><i class="fa fa-edit"></i> Edit FAQ</li>
                            ',
            'data_result' => EmFaq::getWhere([['faq_id', '=', $id]], '', false),
            'menu_order' => $this->menu_order,
            'masterFaq' => 'active',
        );
        return view('admin.master.faq.faq_edit', $data);
    }

    public function editProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'faq_id' => 'required',
            'question' => 'required',
            'answer_text' => 'required',
        ],
        [
            'faq_id.required' => 'Sorry, server can\'t process your data.',
            'bank_name.required' => 'Please insert question.',
            'answer_text.required' => 'Please insert answer.',
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
                'question' => $input['question'],
                'answer' => $input['answer'],
                'order' => $input['order'],
            ];
            
            EmFaq::updateData($input['faq_id'], $dataUpdate);
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
            EmFaq::deleteData($id);
            $result['trigger'] = 'yes';
            $result['notif'] = 'Data has been deleted.';
        }
        else
        {
            $validator = Validator::make(request()->all(), [
                'data' => 'required',
            ],
            [
                'data.required' => 'Please choose data.',
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
                $result['notif'] = 'Data has been deleted.';

                foreach ($input['data'] as $key) 
                {
                    EmFaq::deleteData($key[0]);
                }
                $result['trigger'] = 'yes';
            }
        }

        echo json_encode($result);
    }
}