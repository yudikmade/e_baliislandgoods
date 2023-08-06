<?php namespace App\Helper;

use Xendit\Xendit;

class Xendit_helper {
    public static function pay($input, $amount){
        Xendit::setApiKey(env('XENDIT_SECRET_KEY'));

        $response = array (
            'status' => '0',
            'create_card' => '',
            'capture_card' => '',
        );

        $params = [
            'token_id' => $input['token_id'],
            'external_id' => 'card_' . time(),
            'authentication_id' => $input['authentication_id'],
            'amount' => $amount,
            'card_cvn' => $input['cvn_code'],
            'capture' => false
        ];
        
        $createCharge = \Xendit\Cards::create($params);
        if(isset($createCharge['id'])){
            $id = $createCharge['id'];
            $params = ['amount' => $amount];

            $captureCharge = \Xendit\Cards::capture($id, $params);
            if(isset($captureCharge['status'])){
                if($captureCharge['status'] == 'CAPTURED'){
                    $response = array (
                        'status' => '1',
                        'create_card' => $createCharge,
                        'capture_card' => $captureCharge,
                    );
                }
            }
        }

        return $response;
    }
}