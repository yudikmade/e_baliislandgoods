<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use View;
use Validator;

use App\Models\EmAdministrator;
use App\Helper\Common_helper;

class AuthController extends Controller
{
    public function index()
    {
        Common_helper::check_session_backend();

        $copyRight = View::make('admin.auth.copy_right');
        $loginForm = View::make('admin.auth.login_form', array('copy_right' => $copyRight, 'emailInput' => ''));

        $dataHeader = '
            <h3 class="text-center">Forgot password?</h3>
            <p>Please insert email to reset your password.</p>
        ';
        $forgotPassword = View::make('admin.auth.forgot_password', array('copy_right' => $copyRight, 'data_header' => $dataHeader, 'show' => ' none '));

        $data = array(
            'view_form' => $loginForm.$forgotPassword,
            'title' => 'Login | Administrator');
        return view('admin.auth.auth', $data);
    }

    public function authentication(Request $request)
    {
        Common_helper::check_session_backend();

        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];
        $validation = $this->validate($request, $rules);

        $statusResponse = 400;
        $notif = 'Sorry, please insert your account.';

        if($validation)
        {
            $input = $request->all();
            $dataWhere = 
            [
                ['email', '=', $input['email']],
                ['status', '=', '1'],
            ];
            $getData = EmAdministrator::getWhere($dataWhere, '', false);

            $notif = 'Sorry, account is not available.';
            if(count($getData) == 1)
            {
                foreach ($getData as $key) 
                {
                    if($key->status == '1')
                    {
                        $passwordEncrypt = Common_helper::password_encryption($input['password']);
                        if($key->password == $passwordEncrypt)
                        {
                            Session::put(env('SES_BACKEND_ID'), $key->admin_id);
                            Session::put(env('SES_BACKEND_NAME'), $key->full_name);
                            Session::put(env('SES_BACKEND_EMAIL'), $key->email);
                            Session::put(env('SES_BACKEND_CATEGORY'), $key->category_id);
                            Session::put(env('SES_BACKEND_REGISTERED'), Common_helper::registerd_date($key->register_date));

                            Auth::guard('backend')->loginUsingId($key->admin_id, true);

                            $statusResponse = 200;
                            $notif = 'Welcome, '.$key->full_name;

                            return redirect()->route('control_dashboard');
                        }
                        else
                        {
                            $notif = 'Sorry, password is not match.';
                        }
                    }
                }
            }
        }

        Session::flash('status', $statusResponse);
        Session::flash('notif', $notif);

        return redirect()->route('control.login');
    }

    public function forgotPasswordProcess(Request $request)
    {
        Common_helper::check_session_backend();

        $result['notif'] = '
            <div class="alert alert-danger text-left">
                <strong>Error,</strong> please try again.
            </div>
        ';


        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
        ],
        [
            'email.required' => 'Please insert your email address.',
        ]);
        
        if($validator->fails()) 
        {
            $notif = '';
            foreach ($validator->errors()->all() as $messages) 
            {
                $notif.=$messages.'<br>';
            }

            $result['notif'] = '
                <div class="alert alert-danger text-left">
                    '.$notif.'
                </div>
            ';
        }
        else
        {
            $input = $request->all();
            $dataWhere = 
            [
                ['email', '=', $input['email']],
                ['status', '=', '1'],
            ];
            $getData = EmAdministrator::getWHere($dataWhere, '', false);

            if(count($getData) == 1)
            {
                foreach ($getData as $key) 
                {
                    $resetKey = Common_helper::create_reset_key_password($key->admin_id, $input['email']);
                    $expKey = Common_helper::exp_reset_key_password('+ 1 days');

                    $dataUpdate = [
                        'reset_key' => $resetKey,
                        'exp_reset_key' => $expKey
                    ];

                    EmAdministrator::updateData($key->admin_id, $dataUpdate);

                    $message = array(
                        'name' => $key->full_name,
                        'reset_key' => $resetKey,
                        'url_reset_key' => url('/'.env('URL_LOGIN_BACKEND').'/reset-password/'.$resetKey)
                    );
                    
                    if(Common_helper::send_email($key->email, $message, 'Reset Password', 'forgot_password'))
                    {
                        $result['notif'] = '
                            <div class="alert alert-success text-left">
                                Reset key has been sent to your email. Please check it.
                            </div>
                        ';
                    }
                    else
                    {
                        $result['notif'] = '
                            <div class="alert alert-danger text-left">
                                There is something wrong. Server can\'t send reset key to your email.<br>
                                Please refresh page and try again.
                            </div>
                        ';
                    }
                }
            }
            else
            {
                $result['notif'] = '
                    <div class="alert alert-danger text-left">
                        Email is not exist.
                    </div>
                ';
            }
        }

        echo json_encode($result);
    }

    public function resetPassword($reset_key)
    {
        Common_helper::check_session_backend();

        $dataWhere = 
        [
            ['reset_key', '=', $reset_key],
        ];
        $getData = EmAdministrator::getWHere($dataWhere, '', false);

        $dataCheckExp = 'expired';

        if(count($getData) == 1)
        {
            foreach ($getData as $key) 
            {
                $timeNow = Common_helper::date_time_now();
                $timeExp = $key->exp_reset_key;

                if(strtotime($timeNow)<=$timeExp)
                {
                    $dataCheckExp = 'next';
                }
            }
        }



        $copyRight = View::make('admin.auth.copy_right');

        $view_form = '';
        if($dataCheckExp == "expired")
        {
            $dataHeader = '
                <h3 class="text-center">Reset key is expired</h3>
                <p>Please input email to get new reset key</p>
            ';
            $view_form = View::make('admin.auth.forgot_password', array('copy_right' => $copyRight, 'data_header' => $dataHeader, 'show' => ''));
        }
        else
        {
            $view_form = View::make('admin.auth.reset_password', array('copy_right' => $copyRight, 'reset_key' => $reset_key));
        }

        $data = array(
            'view_form' => $view_form,
            'title' => 'Reset Password | Administrator');
        return view('admin.auth.auth', $data);
    }

    public function resetPasswordProcess(Request $request)
    {
        Common_helper::check_session_backend();

        $result['trigger'] = 'no';
        $result['notif'] = '
            <div class="alert alert-danger text-left">
                <strong>Error,</strong> please try again.
            </div>
        ';


        $validator = Validator::make(request()->all(), [
            'reset_key' => 'required',
            'new_password' => 'required',
            'retype_new_password' => 'required_with:new_password|same:new_password',
        ],
        [
            'reset_key.required' => 'Sorry, reset password needed to reset key.',
            'new_password.required' => 'Please insert new password.',
            'retype_new_password.required_with' => 'Please re-type new password.',
            'retype_new_password.same' => 'Password is not match.',
        ]);
        
        if($validator->fails()) 
        {
            $notif = '';
            foreach ($validator->errors()->all() as $messages) 
            {
                $notif.=$messages.'<br>';
            }

            $result['notif'] = '
                <div class="alert alert-danger text-left">
                    '.$notif.'
                </div>
            ';
        }
        else
        {
            $input = $request->all();
            
            $dataWhere = 
            [
                ['reset_key', '=', $input['reset_key']],
                ['status', '=', '1'],
            ];
            $getData = EmAdministrator::getWHere($dataWhere, '', false);

            if(count($getData) == 1)
            {
                foreach ($getData as $key) 
                {
                    $newPass = Common_helper::password_encryption($input['new_password']);
                    $dateExp = Common_helper::exp_reset_key_password('- 1 days');

                    $dataUpdate = [
                        'exp_reset_key' => $dateExp,
                        'password' => $newPass
                    ];
                    $query = EmAdministrator::updateData($key->admin_id, $dataUpdate);

                    $result['trigger'] = "yes";
                    $result['notif'] = '
                        <div class="alert alert-success text-left">
                            Password has been changed.<br>
                            <a href="'.route('control').'"><b><i>Click for login</i></b></a>
                        </div>
                    ';
                }
            }
            else
            {
                $result['notif'] = '
                    <div class="alert alert-danger text-left">
                        Sorry, reset key needed to reset password.
                    </div>
                ';
            }
        }

        echo json_encode($result);
    }

    public function logout() {
        Common_helper::check_session_backend(true);

        Auth::guard('backend')->logout();
        Session::flush();
        return redirect()->route('control.login');
    }
}