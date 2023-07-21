<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmAdministrator;

class ProfileController extends Controller
{

    public function index()
    {
        Common_helper::check_session_backend(true);
        $data = array(
            'title' => 'Profile | Administrator',
            'title_page' => 'Profile',
            'title_form' => 'Data profile',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-user"></i> Profile</li>
                            ',
            'menu_order' => 0,
            'data_profile' => EmAdministrator::getWhere([['admin_id', '=', Session::get(env('SES_BACKEND_ID'))]], '', false)
        );
        return view('admin.profile.profile', $data);
    }

    public function process(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = 'Data is not exist.';


        $validator = Validator::make(request()->all(), [
            'full_name' => 'required',
            'email' => 'required',
            'rnew_password' => 'same:new_password',
            'current_password' => 'required',
        ],
        [
            'full_name.required' => 'Please insert category.',
            'email.required' => 'Please insert email.',
            'rnew_password.same' => 'New password is not match.',
            'current_password.required' => 'Please insert current password.',
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
            
            //check current password
            $dataWhere = [
                ['admin_id', '=', Session::get(env('SES_BACKEND_ID'))],
            ];
            $getProfile = EmAdministrator::getWhere($dataWhere, '', false);
            foreach ($getProfile as $key) 
            {
                $validCurrentPassword = true;

                $encryptCurrentPass = Common_helper::password_encryption($input['current_password']);
                if($key->password != $encryptCurrentPass)
                {
                    $validCurrentPassword = false;
                }

                if($validCurrentPassword)
                {
                    //check email
                    $getEmail = EmAdministrator::getWhere([['email', '=', $input['email']], ['admin_id', '!=', Session::get(env('SES_BACKEND_ID'))]], '', false);
                    if(count($getEmail) == 0)
                    {
                        $result['trigger'] = 'yes';

                        $dataUpdate = 
                        [
                            'full_name' => $input['full_name'],
                            'email' => $input['email'],
                            'last_update' => strtotime(Common_helper::date_time_now())
                        ];
                        if($input['new_password'] != '')
                        {
                            $dataUpdate = 
                            [
                                'full_name' => $input['full_name'],
                                'email' => $input['email'],
                                'password' => Common_helper::password_encryption($input['new_password']),
                                'last_update' => strtotime(Common_helper::date_time_now())
                            ];
                        }
                        EmAdministrator::updateData(Session::get(env('SES_BACKEND_ID')), $dataUpdate);

                        $result['notif'] = 'Data profile has been changed.';
                        $result['last_update'] = Common_helper::registerd_date(strtotime(Common_helper::date_time_now()));
                    }
                    else
                    {
                        $result['notif'] = 'Sorry, email already exist.';
                    }
                }
                else
                {
                    $result['notif'] = 'Current password is wrong.';
                }
            }
        }

        echo json_encode($result);
    }
}