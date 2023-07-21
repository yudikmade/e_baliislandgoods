<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmSocialMedia;

class SocialMediaController extends Controller
{
    private $menu_order = 5;

    public function index($search = '')
    {
        Common_helper::check_session_backend(true);

        if($search != '')
        {
            $search = str_replace('+', ' ', $search);
        }

        $data_result = EmSocialMedia::getWhere([], "(social_name like '%" . $search . "%')", true);
        $view_content = View::make('admin.master.social_media.smedia', compact('data_result'));

        $data = array(
            'title' => 'Social media | Administrator',
            'title_page' => 'Social media',
            'title_form' => 'Data social media',
            'information' => 'The following data social media has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-file-text-o"></i> Social media</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterSocial' => 'active',
            'search' => $search,
            'view_content' => $view_content,
            'url_search' => route('control_social_media')
        );
        return view('admin.table_view_template', $data);
    }
    public function add()
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Social media | Administrator',
            'title_page' => 'Social media',
            'title_form' => 'Form add social media',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_social_media').'"><i class="fa fa-file-text-o"></i> Social media</a></li>
                                <li class="active"><i class="fa fa-plus"></i> Add social media</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterSocial' => 'active',
        );
        return view('admin.master.social_media.smedia_add', $data);
    }

    public function addProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'kind' => 'required',
            'url' => 'required',
        ],
        [
            'kind.required' => 'Please choose social media.',
            'url.required' => 'Please insert url of social media.',
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

            if(count(EmSocialMedia::getWhere([['social_name', '=', $input['kind']]], '', false)) == 0)
            {
                $dataInsert = 
                [
                    'social_name' => $input['kind'],
                    'social_url' => $input['url'],
                ];
                EmSocialMedia::insertData($dataInsert);
                $result['trigger'] = 'yes';
                $result['notif'] = 'New social media has been added.';
            }
            else
            {
                $result['notif'] = 'Social media ('.$input['kind'].') already exist.';
            }
        }

        echo json_encode($result);
    }

    public function edit($id)
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Social media | Administrator',
            'title_page' => 'Social media',
            'title_form' => 'Form edit social media',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_social_media').'"><i class="fa fa-file-text-o"></i> Social media</a></li>
                                <li class="active"><i class="fa fa-edit"></i> Edit social media</li>
                            ',
            'data_result' => EmSocialMedia::getWhere([['social_id', '=', $id]], '', false),
            'menu_order' => $this->menu_order,
            'masterSocial' => 'active',
        );
        return view('admin.master.social_media.smedia_edit', $data);
    }

    public function editProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'social_id' => 'required',
            'kind' => 'required',
            'url' => 'required',
        ],
        [
            'social_id.required' => 'Sorry, server can\'t response.',
            'kind.required' => 'Please choose social media.',
            'url.required' => 'Please insert url of social media.',
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
                'social_name' => $input['kind'],
                'social_url' => $input['url'],
            ];
            EmSocialMedia::updateData($input['social_id'], $dataUpdate);
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
            EmSocialMedia::where('social_id', $id)->delete();
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
                    EmSocialMedia::where('social_id', $key[0])->delete();
                }
                $result['trigger'] = 'yes';
            }
        }

        echo json_encode($result);
    }
}