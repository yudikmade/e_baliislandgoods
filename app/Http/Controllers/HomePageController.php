<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Validator;
use View;

use App\Helper\Common_helper;
use App\Models\EmConfig;

class HomePageController extends Controller
{
    private $menu_order = 6;
    private $menu_title = 'Home Page';
    private $dimension_image = 640;
    private $upload_image = 'home/';
    private $upload_image_thumb = 'home/thumb/';

    public function index(){
        Common_helper::check_session_backend(true);
        $data = array(
            'title' => 'Home page | Administrator',
            'title_page' => 'Home page',
            'title_form' => 'Data Home page',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-list"></i> Home page</li>
                            ',
            'menu_order' => $this->menu_order,
            'home_text' => EmConfig::getData(array('meta_key' => 'home_text')),
            'home_image_1' => EmConfig::getData(array('meta_key' => 'home_image_1')),
            'home_image_2' => EmConfig::getData(array('meta_key' => 'home_image_2')),
            'home_image_3' => EmConfig::getData(array('meta_key' => 'home_image_3')),
        );
        return view('admin.pages.home', $data);
    }

    public function saveProcess(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = '';

        $validator = Validator::make(request()->all(), [
           
        ],[
            
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
                'width' => $this->dimension_image,
                'height' => 0
            );

            if(isset($input['home_image_1'])){
                $getImage = Common_helper::upload_image($this->upload_image, $this->upload_image_thumb, $newSize, $request->file('home_image_1'));
                if($getImage != ''){
                    $getOldImage = EmConfig::getData(array('meta_key' => 'home_image_1'));
                    if($getOldImage != ''){
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$this->upload_image.$getOldImage);
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$this->upload_image_thumb.$getOldImage);
                    }
                }
                EmConfig::updateData(array('meta_key' => 'home_image_1', 'meta_value' => $getImage));
            }

            if(isset($input['home_image_2'])){
                $getImage = Common_helper::upload_image($this->upload_image, $this->upload_image_thumb, $newSize, $request->file('home_image_2'));
                if($getImage != ''){
                    $getOldImage = EmConfig::getData(array('meta_key' => 'home_image_2'));
                    if($getOldImage != ''){
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$this->upload_image.$getOldImage);
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$this->upload_image_thumb.$getOldImage);
                    }
                }
                EmConfig::updateData(array('meta_key' => 'home_image_2', 'meta_value' => $getImage));
            }

            if(isset($input['home_image_3'])){
                $getImage = Common_helper::upload_image($this->upload_image, $this->upload_image_thumb, $newSize, $request->file('home_image_3'));
                if($getImage != ''){
                    $getOldImage = EmConfig::getData(array('meta_key' => 'home_image_3'));
                    if($getOldImage != ''){
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$this->upload_image.$getOldImage);
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$this->upload_image_thumb.$getOldImage);
                    }
                }
                EmConfig::updateData(array('meta_key' => 'home_image_3', 'meta_value' => $getImage));
            }

            EmConfig::updateData(array('meta_key' => 'home_text', 'meta_value' => $input['home_text']));

            $result['trigger'] = 'yes';
            $result['notif'] = 'Data has been saved.';
        }
        echo json_encode($result);
    }
}