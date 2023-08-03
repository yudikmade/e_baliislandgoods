<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Validator;

use App\Helper\Common_helper;
use App\Models\EmConfig;
use App\Models\EmFaq;

class InformationController extends Controller
{
    private $menu_order = 5;

    public function contact()
    {
        Common_helper::check_session_backend(true);

        $meta_key = 'contact_us';
        $meta_key_address = "contact_us_address";
        $meta_key_telp = "contact_us_telp";
        $meta_key_email = "contact_us_email";

        $data = array(
            'title' => 'Contact Us | Administrator',
            'title_page' => 'Contact Us',
            'title_form' => 'Form edit Contact Us of site',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-edit"></i> Edit Contact Us</li>
                            ',
            'data_result_address' => EmConfig::getData(array('meta_key' => $meta_key_address)),
            'data_result_telp' => EmConfig::getData(array('meta_key' => $meta_key_telp)),
            'data_result_email' => EmConfig::getData(array('meta_key' => $meta_key_email)),
            'menu_order' => $this->menu_order,
            'meta_key' => $meta_key,
            'masterInformation' => 'active',
        );
        return view('admin.master.config.information_edit', $data);
    }

    public function control_info_terms_of_payment()
    {
        Common_helper::check_session_backend(true);

        $meta_key = 'terms_of_payment_page';

        $data = array(
            'title' => 'Terms of Payment | Administrator',
            'title_page' => 'Terms of Payment',
            'title_form' => 'Form edit Terms of Payment of site',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-edit"></i> Edit Terms of Payment</li>
                            ',
            'data_result' => EmConfig::getData(array('meta_key' => $meta_key)),
            'menu_order' => $this->menu_order,
            'meta_key' => $meta_key,
            'masterInformation' => 'active',
        );
        return view('admin.master.config.information_edit', $data);
    }

    public function control_info_shipping_and_return()
    {
        Common_helper::check_session_backend(true);

        $meta_key = 'shipping_and_return_page';

        $data = array(
            'title' => 'Shipping & Return | Administrator',
            'title_page' => 'Shipping & Return',
            'title_form' => 'Form edit Shipping & Return of site',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-edit"></i> Edit Shipping & Return</li>
                            ',
            'data_result' => EmConfig::getData(array('meta_key' => $meta_key)),
            'menu_order' => $this->menu_order,
            'meta_key' => $meta_key,
            'masterInformation' => 'active',
        );
        return view('admin.master.config.information_edit', $data);
    }

    public function control_info_privacy_policy()
    {
        Common_helper::check_session_backend(true);

        $meta_key = 'privacy_policy_page';

        $data = array(
            'title' => 'Privacy Policy | Administrator',
            'title_page' => 'Privacy Policy',
            'title_form' => 'Form edit Privacy Policy of site',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-edit"></i> Edit Privacy Policy</li>
                            ',
            'data_result' => EmConfig::getData(array('meta_key' => $meta_key)),
            'menu_order' => $this->menu_order,
            'meta_key' => $meta_key,
            'masterInformation' => 'active',
        );
        return view('admin.master.config.information_edit', $data);
    }

    public function tax()
    {
        Common_helper::check_session_backend(true);

        $meta_key = 'tax';

        $data = array(
            'title' => 'Tax | Administrator',
            'title_page' => 'Tax',
            'title_form' => 'Form edit Tax of site',
            'breadcrumbs' => '
                                <li class="active"><i class="fa fa-edit"></i> Edit Tax</li>
                            ',
            'data_result' => EmConfig::getData(array('meta_key' => $meta_key)),
            'menu_order' => $this->menu_order,
            'meta_key' => $meta_key,
            'masterTax' => 'active',
        );
        return view('admin.master.tax_edit', $data);
    }

    public function process(Request $request)
    {
        Common_helper::check_session_backend(true);

        $result['trigger'] = 'no';
        $result['notif'] = 'Please choose image to upload.';


        $validator = Validator::make(request()->all(), [
            'meta_key' => 'required',
            // 'information_text' => 'min:0',
        ],
        [
            'meta_key.required' => 'Sorry, server can\'t process your data.',
            // 'information_text.min' => 'Please insert information.',
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
                'meta_key' => $input['meta_key'],
                'meta_value' => isset($input['information']) ? $input['information'] : '',
            );
            EmConfig::updateData($dataUpdate);

            if($input['meta_key'] == 'contact_us'){
                $dataUpdate = array(
                    'meta_key' => 'contact_us_address',
                    'meta_value' => $input['address'],
                );
                EmConfig::updateData($dataUpdate);

                $dataUpdate = array(
                    'meta_key' => 'contact_us_telp',
                    'meta_value' => $input['telp'],
                );
                EmConfig::updateData($dataUpdate);

                $dataUpdate = array(
                    'meta_key' => 'contact_us_email',
                    'meta_value' => $input['email'],
                );
                EmConfig::updateData($dataUpdate);
            }
            $result['trigger'] = 'yes';
            $result['notif'] = 'Data has been changed.';
        }

        echo json_encode($result);
    }
}



