<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\MSlide;
use App\Models\EmConfig;

class SlideController extends Controller
{
    private $menu_order = 5;

    public function index($search = '')
    {
        Common_helper::check_session_backend(true);

        $data_result = MSlide::getWhere([], true);
        $view_content = View::make('admin.master.slide.slide', compact('data_result'));

        $data = array(
            'title' => 'Slide | Administrator',
            'title_page' => 'Slide',
            'title_form' => 'Data slide',
            'information' => 'The following data image has been stored in the system',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-picture-o"></i> Slide</li>
                            ',
            'menu_order' => $this->menu_order,
            'masterSlide' => 'active',
            'view_content' => $view_content,
        );
        return view('admin.table_view_template', $data);
    }
    public function add()
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Slide | Administrator',
            'title_page' => 'Slide',
            'title_form' => 'Form add new image for silde',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_slide').'"><i class="fa fa-picture-o"></i> Slide</a></li>
                                <li class="active"><i class="fa fa-plus"></i> Add slide</li>
                            ',
            'menu_order' => $this->menu_order,
            'new_order' => MSlide::newOrder(),
            'masterSlide' => 'active',
        );
        return view('admin.master.slide.slide_add', $data);
    }

    public function addProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'order' => 'required',
            'up_image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ],
        [
            'order.required' => 'Please insert order data.',
            'up_image.required' => 'Please upload image.',
            'up_image.mimes' => 'Format image is wrong.',
            'up_image.max' => 'Upload image max 2MB.',
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
            
            //upload image
            $newSize = array(
                'crop' => false,
                'width' => 1800,
                'height' => 0
            );
            $getImageName = Common_helper::upload_image('slide/', 'slide/thumb/', $newSize, $request->file('up_image'));

            $dataInsert = 
            [
                'order' => $input['order'],
                'image' => $getImageName,
                'url' => (isset($input['url'])?$input['url']:''),
            ];
            MSlide::insertData($dataInsert);
            $result['trigger'] = 'yes';
            $result['notif'] = 'New slide has been added.';
            $result['order'] = MSlide::newOrder();
        }

        echo json_encode($result);
    }

    public function edit($id)
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Slide | Administrator',
            'title_page' => 'Slide',
            'title_form' => 'Form edit image for slide',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_slide').'"><i class="fa fa-picture-o"></i> Slide</a></li>
                                <li class="active"><i class="fa fa-edit"></i> Edit slide</li>
                            ',
            'data_result' => MSlide::getWhere([['slide_id', '=', $id]], false),
            'menu_order' => $this->menu_order,
            'masterSlide' => 'active',
        );
        return view('admin.master.slide.slide_edit', $data);
    }

    public function editProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';


        $validator = Validator::make(request()->all(), [
            'order' => 'required',
            'up_image' => 'image|mimes:jpeg,jpg,png|max:2048',
        ],
        [
            'order.required' => 'Please insert order data.',
            'up_image.mimes' => 'Format image is wrong.',
            'up_image.max' => 'Upload image max 2MB.',
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
            
            //upload image
            $newSize = array(
                'crop' => false,
                'width' => 1800,
                'height' => 0
            );
            $getImageName = Common_helper::upload_image('slide/', 'slide/thumb/', $newSize, $request->file('up_image'));

            $dataUpdate = 
            [
                'order' => $input['order'],
                'url' => (isset($input['url'])?$input['url']:''),
            ];
            $result['new_image_ori'] = '';
            $result['new_image_thumb'] = '';
            if($getImageName != '')
            {
                $dataUpdate = 
                [
                    'order' => $input['order'],
                    'image' => $getImageName,
                    'url' => (isset($input['url'])?$input['url']:''),
                ];

                //delete image
                $getData = MSlide::getWhere([['slide_id', '=', $input['slide_id']]], false);
                foreach ($getData as $key) 
                {
                    @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/slide/'.$key->image);
                    @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/slide/thumb/'.$key->image);
                }
                $result['new_image_ori'] = asset(env('URL_IMAGE')).'/slide/'.$getImageName;
                $result['new_image_thumb'] = asset(env('URL_IMAGE')).'/slide/thumb/'.$getImageName;
            }
            MSlide::updateData($input['slide_id'], $dataUpdate);
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
            $this->deleteDataAction($id);
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

                foreach ($input['data'] as $key) 
                {
                    $this->deleteDataAction($key[0]);
                }
                $result['trigger'] = 'yes';
                $result['notif'] = 'Data has been deleted.';
            }
        }

        echo json_encode($result);
    }

    private function deleteDataAction($id)
    {
        $getData = MSlide::getWhere([['slide_id', '=', $id]], false);
        foreach ($getData as $key) 
        {
            @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/slide/'.$key->image);
            @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').'/slide/thumb/'.$key->image);

            MSlide::deleteData($key->slide_id);
        }
    }

    public function kindSlide()
    {
        Common_helper::check_session_backend(true);

        $masterKindOfSlide = array(
            'slide' => 'Horizontal Slider', 
            'vertical' => 'Display images in vertical', 
            'vertical mix' => 'Display images and video in vertical', 
            'video' => 'Only display video'
        );

        $masterDataProductCategory = array(
            '-' => 'No categories', 
            'first_section' => 'Category on first section', 
            'middle_section' => 'Category on middle section', 
            'last_section' => 'Category on last section', 
        );

        $data = array(
            'title' => 'Kind of slide | Administrator',
            'title_page' => 'Kind of slide',
            'title_form' => 'Form update kind of slide',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_slide').'"><i class="fa fa-picture-o"></i> Kind of slide</a></li>
                                <li class="active"><i class="fa fa-dit"></i> Edit</li>
                            ',
            'menu_order' => $this->menu_order,
            'kind_data' => EmConfig::getData(array('meta_key' => 'kind_home_page'))->meta_value,
            'masterKindOfSlide' => $masterKindOfSlide,
            'masterDataProductCategory' => $masterDataProductCategory,
            'masterSlide' => 'active',
        );
        return view('admin.master.slide.kind_slide', $data);
    }

    public function videoSlide()
    {
        Common_helper::check_session_backend(true);

        $data = array(
            'title' => 'Video slide | Administrator',
            'title_page' => 'Video slide',
            'title_form' => 'Form update video slide',
            'breadcrumbs' => '
                                <li class=""><a href="'.route('control_slide').'"><i class="fa fa-picture-o"></i> Video slide</a></li>
                                <li class="active"><i class="fa fa-dit"></i> Edit</li>
                            ',
            'menu_order' => $this->menu_order,
            'video_data' => MSlide::getWhere(['kind' => '1'], false),
            'masterSlide' => 'active',
        );
        return view('admin.master.slide.slide_video', $data);
    }

    public function editKindVideo(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, server can\'t process your data.';

        $input = $request->all();

        if($input['action'] != '')
        {
            if($input['action'] == 'kindofvideo')
            {
                $validator = Validator::make(request()->all(), [
                    'kind' => 'required',
                ],
                [
                    'kind.required' => 'Please choose kind of video.',
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
                    $dataUpdate = array(
                        'meta_key' => 'kind_home_page',
                        'meta_value' => $input['kind'],
                    );
                    EmConfig::updateData($dataUpdate);

                    $dataUpdate = array(
                        'meta_key' => 'show_category_home_page',
                        'meta_value' => $input['show_category'],
                    );
                    EmConfig::updateData($dataUpdate);

                    $result['trigger'] = 'yes';
                    $result['notif'] = 'Data has been changed.';
                }
            }
            
            if($input['action'] == 'video')
            {
                $validator = Validator::make(request()->all(), [
                    'video' => 'required',
                ],
                [
                    'kind.required' => 'Please enter url video from youtube.',
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
                        'image' => $input['video'],
                    ];
                    MSlide::updateData('5', $dataUpdate);

                    $result['trigger'] = 'yes';
                    $result['notif'] = 'Data has been changed.';
                }
            }
        }

        echo json_encode($result);
    }
}