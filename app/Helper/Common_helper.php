<?php namespace App\Helper;

use PHPMailer\PHPMailer\PHPMailer;
use App\Models\EmProductCategory;
use App\Models\EmProduct;
use App\Models\EmProductSku;
use App\Models\EmProductImg;
use App\Models\MCurrency;
use App\Models\EmTransaction;
use App\Models\EmTransactionMeta;
use App\Models\EmSocialMedia;
use App\Models\EmCustomer;
use View;
use Session;
use Cookie;
use Redirect;
use Image;

class Common_helper 
{

	public static function clean($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}

	public static function cookie_time()
	{
		return 1440;
	}

	public static function create_invoice_number()
	{
		$countInvoice = EmTransaction::getWhereCount([], '');
		$countInvoice += 1;

		$initial = 'I'.date('ym');

		$invoiceLength = '0000';

		return $initial.substr($invoiceLength, 0, (strlen($invoiceLength) - strlen($countInvoice))).$countInvoice;
	}

	public static function create_id_customer()
	{
		$countInvoice = EmCustomer::getWhereCount([], '');
		$countInvoice += 1;

		$initial = 'C'.date('ym');

		$invoiceLength = '0000';

		return $initial.substr($invoiceLength, 0, (strlen($invoiceLength) - strlen($countInvoice))).$countInvoice;
	}

	public static function create_product_code($product_code = ""){
		if($product_code != ''){
			return $product_code;
		}else
		{
			return 'P'.strtotime(date('Y-m-d H:i:s')).rand(0000,9999);
		}
	}

	public static function create_product_sku_code($sku_code, $product_id)
	{
		if($sku_code != '')
		{
			return $sku_code;
		}
		else
		{
			$getData = EmProduct::getWhere([['product_id', '=', $product_id]], '', false);
			return $getData[0]->product_id.'-'.strtotime(date('Y-m-d H:i:s'));
		}
	}

	public static function set_product_category($dataArray, $action)
	{
		if($action == 'insert' || $action == 'update')
		{
			// if($dataArray['sub_category3'] != '')
			// {
			// 	return $dataArray['sub_category3'];
			// }
			// else if($dataArray['sub_category2'] != '')
			// {
			// 	return $dataArray['sub_category2'];
			// }
			// else if($dataArray['sub_category1'] != '')
			// {
			// 	return $dataArray['sub_category1'];
			// }	
			// else
			// {
			// 	return $dataArray['main_category'];
			// }
			return $dataArray['main_category'];
		}
		else if($action == 'edit')
		{
			return EmProductCategory::getOneHierarchy($dataArray);
		}
		else
		{
			return EmProductCategory::getWhere([['category_id', '=', $dataArray]], '', false);
		}
	}

	public static function check_session_backend($after_login = false)
	{
		if($after_login)
		{
			if(Session::get(env('SES_BACKEND_ID')) == null)
	        {
	    		echo Redirect::route('control.login');
	        }
        }
        else
        {
        	if(Session::get(env('SES_BACKEND_ID')) != null)
	        {
	        	echo Redirect::route('control_dashboard');
	        }
        }
	}

	public static function check_session_frontend($after_login = false)
	{
		if($after_login)
		{
			if(Session::get(env('SES_FRONTEND_ID')) == null)
	        {
	    		echo Redirect::route('user_login');
	        }
        }
        else
        {
        	if(Session::get(env('SES_FRONTEND_ID')) != null)
        	{
        		// echo Redirect::route('home_page');
        		echo Redirect::route('shop_page');
    		}
        }
	}

	public static function password_encryption($password)
	{
		return sha1(md5($password).sha1($password)).md5($password);
	}

	public static function create_reset_key_password($id, $email)
	{
		return rand(000000000, 999999999).sha1(sha1($id).sha1(substr($email, 0, 5))).rand(000000000, 999999999);
	}

	public static function exp_reset_key_password($setChangeDate)
	{
		return strtotime(date('Y-m-d H:i:s',strtotime($setChangeDate)));
	}

	public static function date_time_now()
	{
		return date('Y-m-d H:i:s');
	}

	public static function date_default($date)
	{
		return date('Y-m-d H:i:s', $date);
	}

	public static function send_email($email, $message, $subject, $view, $addCC = false)
	{
		//set message
		$viewMessage = View::make('email.header');
		$viewMessage .= View::make('email.'.$view, $message);
		$viewMessage .= View::make('email.footer');


		$mail = new PHPMailer(true);
        try
        {
        	// echo env('MAIL_DATA_EMAIL').env('MAIL_PASSWORD');
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->CharSet = 'utf-8';
            $mail->SMTPAuth =true;
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Host = env('MAIL_HOST'); //gmail has host > smtp.gmail.com
            $mail->Port = env('MAIL_PORT'); //gmail has port > 587 . without double quotes
            $mail->Username = env('MAIL_USERNAME'); //your username. actually your email
            $mail->Password = env('MAIL_PASSWORD'); // your password. your mail password
            $mail->setFrom(env('MAIL_USERNAME'), env('AUTHOR_SITE')); 
            $mail->Subject = $subject;
            $mail->MsgHTML($viewMessage);
			$mail->addAddress($email); 
			if($addCC){
				// $mail->AddCC('test@gmail.com'); 
			}

            if($mail->send())
            {
            	return true;
            }
            else
            {
            	return false;
            }
        }
        catch(phpmailerException $e)
        {
            // dd($e);
        }
        catch(Exception $e)
        {
            // dd($e);
        }

        return false;
    }

    public static function send_broadcast($email, $message, $subject, $view)
	{
		//set message
		$viewMessage = View::make('email.'.$view, $message);
		$mail = new PHPMailer(true);
        try
        {
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->CharSet = 'utf-8';
            $mail->SMTPAuth =true;
            $mail->SMTPSecure = env('MAIL_DATA_ENCRYPTION');
            $mail->Host = env('MAIL_DATA_HOST'); //gmail has host > smtp.gmail.com
            $mail->Port = env('MAIL_DATA_PORT'); //gmail has port > 587 . without double quotes
            $mail->Username = env('MAIL_DATA_USERNAME'); //your username. actually your email
            $mail->Password = env('MAIL_DATA_PASSWORD'); // your password. your mail password
            $mail->setFrom(env('MAIL_DATA_USERNAME'), env('AUTHOR_SITE')); 
            $mail->Subject = $subject;
            $mail->MsgHTML($viewMessage);
			$mail->addAddress($email); 

            if($mail->send())
            {
            	return true;
            }
            else
            {
            	return false;
            }
        }
        catch(phpmailerException $e)
        {
            // dd($e);
        }
        catch(Exception $e)
        {
            // dd($e);
        }

        return false;
    }

    public static function convert_to_format_currency($data){
		// return $data;
		$tmpData = explode('.', $data);

		$belakangKoma="";
		if(count($tmpData)>1){
			$belakangKoma=".".$tmpData[1];
			if(strlen($tmpData[1]) == 1){
				$belakangKoma=".".$tmpData[1].'0';
			}
		}
		$data=$tmpData[0];
		$panjangData=strlen(ceil($data));
		$hasilBagi=substr($panjangData/3,0,1);
		$sisaBagi=$panjangData%3;
		$h="";
		$aw2=0;
		$ambil=3;
		
		if($sisaBagi!=0)
		{
			$h.=substr($data,0,$sisaBagi);
			$pjgDataBaru=strlen($data)-$sisaBagi;
			
			for($i=1;$i<=$pjgDataBaru/3;$i++)
			{
				$h.=",";
				$h.=substr($data,$sisaBagi,$ambil);
				$sisaBagi+=3;
			}
			
		}else
		{
			for($a=1;$a<=$hasilBagi;$a++)
			{
				$h=$h.substr($data,$aw2,$ambil);
				if($a<$hasilBagi)
				{
					$h=$h.",";
				}
				$aw2+=3;
			}
		}
		return $h.$belakangKoma;
	}

	public function generateProduct($key){
		$description = $key->description;
        if(strlen($description) > 80){
          $description = substr($description,0,80).'...';
        }

		$image = array();
		$get_product = EmProductSku::where('status','1')->where('product_id', $key->product_id)->groupBy('color_hexa')->orderBy('order','ASC')->get();
		// product image
		foreach($get_product as $value){
			array_push($image, array(
				'sku' => $value->sku_id,
				'color' => $value->color_hexa,
				'image' => EmProductImg::where('sku_id',$value->sku_id)->limit(2)->get(),
			));
		}

		$discount_text = 0;
		if(!is_null($key->discount)){
			$discount_text = $key->discount;
		}

		//price
		$setDiscount = self::set_discount($key->price, $key->discount);
		$priceAfterDisc = $setDiscount[0];
		$discount = $setDiscount[1];

		$current_currency = self::get_current_currency();

		$priceInCurrencyFormat = self::convert_to_current_currency($priceAfterDisc, "", false);
		// $showPriceAfterDisc = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];
		$showPriceAfterDisc = $current_currency[1].$priceInCurrencyFormat[1];

		$priceInCurrencyFormat = self::convert_to_current_currency($key->price, "", false);
		// $showPriceNormal = $current_currency[1].$priceInCurrencyFormat[1].' '.$current_currency[2];
		$showPriceNormal = $current_currency[1].$priceInCurrencyFormat[1];

		$showPriceHTML = '';
		if($discount == '0'){
			$showPriceHTML = '<span>'.$showPriceAfterDisc.'</span>';
		}else{
			$showPriceHTML = 'Save - <strike>'.$showPriceNormal.'</strike> <span>'.$showPriceAfterDisc.'</span>';
		}
		if($key->stock == 0){
			$showPriceHTML = 'Sold Out';
		}

		return array(
			'id' => $key->product_id,
			'product' => $key->product_name,
			'description' => $description,
			'discount' => $discount_text,
			'link' => route('shop_detail_page').'/'.str_replace(' ', '-', strtolower($key->product_name)).'-'.$key->product_id,
			'showPriceHTML' => $showPriceHTML,
			'image' => $image,
		);
	}

	public static function registerd_date($dateInTimestamp)
	{	
		if($dateInTimestamp != '')
		{
			$dateNormal = date('Y-m-d H:i:s', $dateInTimestamp);

	        $day = date('D', $dateInTimestamp);
	        $month = date('M', $dateInTimestamp);

	        return substr($dateNormal, 8,2).' '.$month.'. '.substr($dateNormal, 0,4);
        }
        else
        {
        	return '';
        }
	}

	public static function data_date($dateInTimestamp)
	{	
		$dateNormal = date('Y-m-d H:i:s', $dateInTimestamp);

        $day = date('D', $dateInTimestamp);
        $month = date('M', $dateInTimestamp);

        return substr($dateNormal, 8,2).' '.$month.'. '.substr($dateNormal, 0,4).' - '.substr($dateNormal, 11,5);
	}

	public static function status_default($status, $exportString = false)
	{
		// 0 : not active
		// 1 : active
		// 2 : delete

		if($status == '1')
		{
			if($exportString)
			{
				return 'Active';
			}
			else
			{
				return '<label class="label label-success">Active</label>';
			}
		}
		else
		{
			if($exportString)
			{
				return 'Not Active';
			}
			else
			{
				return '<label class="label label-danger">Not Active</label>';
			}
		}
	}

	public static function list_status_transaction()
	{
		$dataStatus = array('all-status' => 'All Status', '1' => 'New', '2' => 'Paid', '3' => 'Processed', '4' => 'Sent', '5' => 'Cancel');
		return $dataStatus;
	}

	public static function list_status_proof()
	{
		$dataStatus = array('all-status' => 'All status', '0' => 'New', '1' => 'Received', '2' => 'Rejected');;
		return $dataStatus;
	}

	public static function transaction_status($status, $statusPayment, $exportString = false)
	{
		// 0 : in shopping cart, 
		// 1 : order, 
		// 2 : paid, 
		// 3 : processed, 
		// 4 : send, 
		// 5 : cancel, 
		// 6 : delete

		$textStatusPayment = '';
		$textStatusPaymentExport = '';
		if($statusPayment != '1')
		{
			$textStatusPayment = '<br><label class="label label-warning">Not Paid</label>';
			$textStatusPaymentExport = ' - Not Paid';
		}

		if($status == '1')
		{
			if($exportString)
			{
				return 'New'.$textStatusPaymentExport;
			}
			else
			{
				return '<label class="label label-primary">New</label>'.$textStatusPayment;
			}
		}
		else if($status == '2')
		{
			if($exportString)
			{
				return 'Paid';
			}
			else
			{
				return '<label class="label label-success">Paid</label>';
			}
		}
		else if($status == '3')
		{
			if($exportString)
			{
				return 'Processed';
			}
			else
			{
				return '<label class="label label-warning">Processed</label>';
			}
		}
		else if($status == '4')
		{
			if($exportString)
			{
				return 'Sent';
			}
			else
			{
				return '<label class="label label-warning">Sent</label>';
			}
		}
		else
		{
			if($exportString)
			{
				return 'Cancel'.$textStatusPaymentExport;
			}
			else
			{
				return '<label class="label label-danger">Cancel</label>'.$textStatusPayment;
			}
		}

	}

	public static function type_of_payment($type, $exportString = false)
	{
		// paypal // stripe or bank transfer

		if($type != '')
		{
			if($type == 'paypal')
			{
				if($exportString)
				{
					return 'Paypal';
				}
				else
				{
					return '<label class="label label-success">Paypal</label>';
				}
			}

			if($type == 'stripe')
			{
				if($exportString)
				{
					return 'Stripe';
				}
				else
				{
					return '<label class="label label-info">Stripe</label>';
				}
			}

			if($type == 'bank-transfer')
			{
				if($exportString)
				{
					return 'Bank transfer';
				}
				else
				{
					return '<label class="label label-primary">Bank transfer</label>';
				}
			}
		}
		return '';
	}

	public static function proofofpayment_status($status)
	{
		// 0 : new, 
		// 1 : approved, 
		// 2 : rejected, 
		
		if($status == '0')
		{
			return '<label class="label label-primary">New</label>';
		}
		else if($status == '1')
		{
			return '<label class="label label-success">Approved</label>';
		}
		else
		{
			return '<label class="label label-danger">Rejected</label>';
		}
	}

	public static function trans_detail_status($status)
	{
		// 0 : cancel, 
		// 1 : active, 
		
		if($status == '0')
		{
			return '<label class="label label-danger">Cancel</label>';
		}
		else 
		{
			return '<label class="label label-success">Active</label>';
			return '';
		}
	}


	public static function status_form_edit($status)
	{
		// 0 : not active
		// 1 : active

        $statusActive = "";
        $statusNotActive = "";
        if($status == "1")
        {
            $statusActive = "checked";
            $statusNotActive = "";
        }
        else
        {
            $statusActive = "";
            $statusNotActive = "checked";
        }
        
        return '
    		<input type="radio" class="minimal" name="status" id="statusActive" value="1" '.$statusActive.'/> <label for="statusActive">Active</label>
    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    		<input type="radio" class="minimal" name="status" id="statusNotActive" value="0" '.$statusNotActive.'/> <label for="statusNotActive">Not Active</label><br>';

	}

	public static function status_show_home($status)
	{
		// 0 : not active
		// 1 : active

        $statusActive = "";
        $statusNotActive = "";
        if($status == "1")
        {
            $statusActive = "checked";
            $statusNotActive = "";
        }
        else
        {
            $statusActive = "";
            $statusNotActive = "checked";
        }
        
        return '
    		<input type="radio" class="minimal" name="show_in_home" id="showHomeActive" value="1" '.$statusActive.'/> <label for="statusActive">Yes</label>
    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    		<input type="radio" class="minimal" name="show_in_home" id="showHomeNotActive" value="0" '.$statusNotActive.'/> <label for="statusNotActive">No</label><br>';

	}

	public static function check_image($image = '', $directory, $width = '100px')
	{
		if($image == '')
		{
			return '<label class="label label-danger">NO IMAGE</label>';
		}
		else
		{
			$urlImageOri = asset(env('URL_IMAGE')).$directory['ori'].$image;
			$urlImageThumb = asset(env('URL_IMAGE')).$directory['thumb'].$image;
			return '<a target="_blank" href="'.$urlImageOri.'"><img width="'.$width.'" src="'.$urlImageThumb.'"></a>';
		}
	}

	public static function upload_image($directory, $directory_thumb, $newSize, $image, $multiple = false, $directory_thumb_small = '', $size_small = array())
	{
		if($image != null)
		{
			if($multiple)
			{
				$tmpName = array();
				foreach($image as $file)
				{
					$originalImage= $file;
			        $thumbnailImage = Image::make($originalImage);
			        $thumbnailPath = $_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$directory_thumb;
			        $originalPath = $_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$directory;

			        $newName = time().str_replace(' ', '_', $originalImage->getClientOriginalName());
					if (!is_dir($originalPath)) {
						mkdir($originalPath, 0777, true);
					}
			        $thumbnailImage->save($originalPath.$newName);
			        if($newSize['crop'])
			        {
			        	$thumbnailImage->resize($newSize['width'], $newSize['height']);
			        }
			        else
			        {
			        	$thumbnailImage->resize($newSize['width'], null, function ($constraint) {
			            	$constraint->aspectRatio();
			        	});
			    	}
					if (!is_dir($thumbnailPath)) {
						mkdir($thumbnailPath, 0777, true);
					}
			        $thumbnailImage->save($thumbnailPath.$newName); 

			        //small
			        if($directory_thumb_small != '')
			        {
			        	$thumbnailImage = Image::make($originalImage);
			        	$thumbnailPath = $_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$directory_thumb_small;
						if (!is_dir($originalPath)) {
							mkdir($originalPath, 0777, true);
						}
			        	$thumbnailImage->save($originalPath.$newName);
			        	if($size_small['crop'])
				        {
				        	$thumbnailImage->resize($size_small['width'], $newSize['height']);
				        }
				        else
				        {
				        	$thumbnailImage->resize($size_small['width'], null, function ($constraint) {
				            	$constraint->aspectRatio();
				        	});
				    	}
						if (!is_dir($thumbnailPath)) {
							mkdir($thumbnailPath, 0777, true);
						}
				        $thumbnailImage->save($thumbnailPath.$newName); 
			        }

		        	array_push($tmpName, $newName);
				}

				return $tmpName;
			}
			else
			{
		        $originalImage= $image;
		        $thumbnailImage = Image::make($originalImage);
		        $thumbnailPath = $_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$directory_thumb;
		        $originalPath = $_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$directory;

		        $newName = time().str_replace(' ', '_', $originalImage->getClientOriginalName());
				if (!is_dir($originalPath)) {
					mkdir($originalPath, 0777, true);
				}
		        $thumbnailImage->save($originalPath.$newName);
		        if($newSize['crop'])
		        {
		        	$thumbnailImage->resize($newSize['width'], $newSize['height']);
		        }
		        else
		        {
		        	$thumbnailImage->resize($newSize['width'], null, function ($constraint) {
		            	$constraint->aspectRatio();
		        	});
		    	}
				if (!is_dir($thumbnailPath)) {
					mkdir($thumbnailPath, 0777, true);
				}
		        $thumbnailImage->save($thumbnailPath.$newName); 

		        //small
		        if($directory_thumb_small != '')
		        {
		        	$thumbnailImage = Image::make($originalImage);
		        	$thumbnailPath = $_SERVER['DOCUMENT_ROOT'].'/'.env('URL_IMAGE').$directory_thumb_small;
					if (!is_dir($originalPath)) {
						mkdir($originalPath, 0777, true);
					}
		        	$thumbnailImage->save($originalPath.$newName);
		        	if($size_small['crop'])
			        {
			        	$thumbnailImage->resize($size_small['width'], $newSize['height']);
			        }
			        else
			        {
			        	$thumbnailImage->resize($size_small['width'], null, function ($constraint) {
			            	$constraint->aspectRatio();
			        	});
			    	}
					if (!is_dir($thumbnailPath)) {
						mkdir($thumbnailPath, 0777, true);
					}
			        $thumbnailImage->save($thumbnailPath.$newName); 
		        }

		        return $newName;
	        }
        }
        return '';
	}

	public static function data_social_name()
	{
		return array(
			'facebook',
			'twitter',
			'instagram',
		);
	}

	

	// public static function convert_shipping_cost($nominal)
	// {
	// 	$helper = new \App\Helper\Common_helper;
	// 	if(Session::get(env('SES_GLOBAL_CURRENCY')) == "1")
	// 	{
	// 		return array($nominal, $helper->convert_to_format_currency($dataSnominalet), 'Rp', 'IDR');
	// 	}
	// 	else
	// 	{
	// 		$getCurrency = MCurrency::getWhere([['currency_id', '=', Session::get(env('SES_GLOBAL_CURRENCY'))]], '', false);
	// 		if(Session::get(env('SES_GLOBAL_CURRENCY')) == null)
	// 		{
	// 			$getCurrency = MCurrency::getWhere([['status_permanent', '=', '1'], ['currency_id', '=', '2']], '', false);
	// 		}
	// 		$setNominal = ($nominal * $getCurrency[0]->rate);
	// 		$expNominal = explode('.', $setNominal);
	// 		if(count($expNominal) == 2)
	// 		{
	// 			if(strlen($expNominal[1]) > 2)
	// 			{
	// 				$setNominal = $expNominal[0].'.'.(substr($expNominal[1], 0, 2) + 1);
	// 			}
	// 		}

	// 		return array($setNominal, $helper->convert_to_format_currency($setNominal), $getCurrency[0]->symbol, $getCurrency[0]->code);
	// 	}
	// }

	// public static function currency_details_format($dataSet, $bank_transfer = false)
	// {
	// 	$helper = new \App\Helper\Common_helper;

	// 	if($dataSet['transaction'])
	// 	{
	// 		$newFormatNominal = $helper->convert_to_format_currency($dataSet['nominal']);
	// 		if($bank_transfer)
	// 		{
	// 			return array($dataSet['nominal'], $newFormatNominal, 'Rp'.$newFormatNominal.' IDR');
	// 		}
	// 		else
	// 		{
	// 			$getMeta = EmTransactionMeta::getMeta(array('transaction_id' => $dataSet['transaction_id'], 'meta_key' => 'currency_id'));
	// 			$getCurrency = MCurrency::getWhere([['currency_id', '=', $getMeta->meta_description]], '', false);
	// 			return array($dataSet['nominal'], $newFormatNominal, $getCurrency[0]->symbol.$newFormatNominal.' '.$getCurrency[0]->code);
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$getCurrency = array();
	// 		if(Session::get(env('SES_GLOBAL_CURRENCY')) == NULL)
	// 		{
	// 			$getCurrency = MCurrency::getWhere([['status_permanent', '=', '1'], ['currency_id', '=', '2']], '', false);
	// 		}
	// 		else
	// 		{
	// 			$getCurrency = MCurrency::getWhere([['currency_id', '=', Session::get(env('SES_GLOBAL_CURRENCY'))]], '', false);
	// 		}

	// 		//set data
	// 		$setNominal = ($dataSet['nominal'] * $getCurrency[0]->rate);
	// 		$expNominal = explode('.', $setNominal);
	// 		if(count($expNominal) == 2)
	// 		{
	// 			if(strlen($expNominal[1]) > 2)
	// 			{
	// 				$setNominal = $expNominal[0].'.'.(substr($expNominal[1], 0, 2) + 1);
	// 			}
	// 		}

	// 		$newFormatNominal = $helper->convert_to_format_currency($setNominal);
	// 		return array($setNominal, $newFormatNominal, $getCurrency[0]->symbol.$newFormatNominal.' '.$getCurrency[0]->code, $getCurrency[0]->symbol, $getCurrency[0]->code);
	// 	}
	// }

	public static function currency_details_format_split($dataSet, $bank_transfer = false)
	{
		if($dataSet['transaction'])
		{
			if($bank_transfer)
			{
				return array($dataSet['nominal'], 'Rp', 'IDR');
			}
			else
			{
				$getMeta = EmTransactionMeta::getMeta(array('transaction_id' => $dataSet['transaction_id'], 'meta_key' => 'currency_id'));
				$getCurrency = MCurrency::getWhere([['currency_id', '=', $getMeta->meta_description]], '', false);
				return array($dataSet['nominal'], $getCurrency[0]->symbol, $getCurrency[0]->code);
			}
		}
		else
		{
			$currencyID = Session::get(env('SES_GLOBAL_CURRENCY'));

			if(Session::get(env('SES_GLOBAL_CURRENCY')) == NULL)
			{
				$getData = MCurrency::getWhere([['status_permanent', '=', '1']], '', false);
				$currencyID = $getData[0]->currency_id;
			}

			$getCurrency = MCurrency::getWhere([['currency_id', '=', $currencyID]], '', false);

			//set data
			$setNominal = ($dataSet['nominal'] * $getCurrency[0]->rate);
			$expNominal = explode('.', $setNominal);
			if(count($expNominal) == 2)
			{
				if(strlen($expNominal[1]) > 2)
				{
					$setNominal = $expNominal[0].'.'.($expNominal[1] + 1);
				}
			}

			return array($setNominal, $getCurrency[0]->symbol, $getCurrency[0]->code);
		}
	}

	public static function generateRandomString($length = 20) 
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString.time();
	}

	public static function set_discount($price, $discount)
	{
		if($discount==null){
			$discount=0;
		}

		$priceDisc = 0;
		if($discount != '' && $discount != '0')
		{
			$priceDisc = ($price * $discount) / 100;
		}

		return array(($price - $priceDisc), $priceDisc);
	}

	public static function get_current_currency($currency_id = '')
	{
		$getCurrency = array();
		if($currency_id != '')
		{
			$getCurrency = MCurrency::getWhere([['currency_id', '=', $currency_id ]], '', false);
		}
		else
		{
			if(Session::get(env('SES_GLOBAL_CURRENCY')) != NULL)
			{
				$getCurrency = MCurrency::getWhere([['currency_id', '=', Session::get(env('SES_GLOBAL_CURRENCY'))]], '', false);
			}
			else
			{
				// self::check_currency_country();
				if(Session::get(env('SES_GLOBAL_CURRENCY')) != NULL){
					$getCurrency = MCurrency::getWhere([['currency_id', '=', Session::get(env('SES_GLOBAL_CURRENCY'))]], '', false);
				}else{
					//IDR (default)
					$getCurrency = MCurrency::getWhere([['status_permanent', '=', '1'], ['currency_id', '=', '1']], '', false);
				}
			}
		}

		$code = $getCurrency[0]->code;
		return array($getCurrency[0]->rate, $getCurrency[0]->symbol, $code, $getCurrency[0]->currency_id);
	}

	public static function convert_to_current_currency($nominal, $currency_id = '', $set_two_nominal = true)
	{
		$helper = new \App\Helper\Common_helper;

		//convert to current currency
		$getCurrency = $helper->get_current_currency($currency_id);
		$newNominal = $getCurrency[0] * $nominal;

		// $check_decimal = explode('.', $newNominal);
		// if(sizeof($check_decimal) == 2){
		// 	$tmp_data = $check_decimal[1];
		// 	if(strlen($tmp_data) > 2){
		// 		if(substr($tmp_data, -1) >= 5){
		// 			$newNominal = $check_decimal[0].'.'.substr($tmp_data, 0, 1)
		// 		}else{

		// 		}
		// 	}else{
		// 		if(strlen($tmp_data) == 1){
		// 			$newNominal = $check_decimal[0].'.'.$tmp_data.'0';
		// 		}
		// 	}
		// }

		if($set_two_nominal){
			$newNominal = $helper->set_two_nominal_after_point($newNominal);
		}
		$newNominalCurrencyFormat = $helper->convert_to_format_currency($newNominal);

		return array($newNominal, $newNominalCurrencyFormat, $getCurrency);
	}

	public static function getAdditionalShippingCost(){
		// $helper = new \App\Helper\Common_helper;
		// $getCurrency = $helper->get_current_currency('');
		$additionalCost = 0;
		// if($getCurrency[3] == '1'){
		// 	$getData = \App\Models\MShippingCostDefault::where('shipping_cost_id', 4)->first();
		// 	$additionalCost = $getData->cost;
		// }else{
		// 	$getData = \App\Models\MShippingCostDefault::where('shipping_cost_id', 3)->first();
		// 	$additionalCost = $getData->cost;
		// }
		return $additionalCost;
	}

	public static function getCouponTransaction($transaction_id)
	{
		$getCouponID = \App\Models\EmTransactionMeta::getMeta(array('transaction_id' => $transaction_id, 'meta_key' => 'coupon_id'));
		if(isset($getCouponID->meta_description))
        {
            if($getCouponID->meta_description != '')
            {
                return \App\Models\EmCoupon::getWhere([['coupon_id', '=', $getCouponID->meta_description]], '', false);
            }
        }

        return array();
	}

	// public static function manageAmoutofUsage($couponData, $price, $trigger = 'plus')
    // {
	// 	$remaining = 0;
	// 	$use_count = 1;
	// 	foreach ($couponData as $key) 
    //     {
	// 		$remaining = $key->discount;
	// 	}

	// 	if($trigger == 'plus'){
	// 		$remaining = $remaining + $price;
	// 	} else {
	// 		$remaining = $remaining - $price;
	// 		if($remaining < 0){
	// 			$remaining = 0;
	// 			$use_count = 0;
	// 		}
	// 	}

    //     foreach ($couponData as $key) 
    //     {
    //         $model = \App\Models\EmCoupon::find($key->coupon_id);
    //         if($trigger == 'plus')
    //         {
    //             $model->use_count = $use_count;
	// 			$model->discount = $remaining;
    //         }
    //         else
    //         {
    //             $model->use_count = $use_count;
	// 			$model->discount = $remaining;
    //         }
    //         $model->save();
    //     }
    // }

	public static function manageAmoutofUsage($couponData, $remaining_coupon, $trigger = 'plus')
    {
        foreach ($couponData as $key) 
        {
            $model = \App\Models\EmCoupon::find($key->coupon_id);
            if($trigger == 'plus')
            {
                $model->use_count += 1;
            }
            else
            {
                $model->use_count -= 1;   
            }
            $model->save();
        }
    }

	public static function set_two_nominal_after_point($nominal)
	{
		$expNominal = explode('.', $nominal);
		if(count($expNominal) == 2){
			if(strlen($expNominal[1]) > 2)
			{
				$lastNominal = (substr($expNominal[1], 0, 2)) + 1;
				$nominal = $expNominal[0].'.'.$lastNominal;
			}
			else
			{
				$strAdd = '00';
				$nominal = $expNominal[0].'.'.$expNominal[1].substr($strAdd, 0, (2 - strlen($expNominal[1])));
			}
		}else{
			$nominal .= '.00';
		}
		return $nominal;
	}

	public static function set_two_0_after_point($nominal)
	{
		// if(!strstr($nominal, ','))
		if(!strstr($nominal, '.'))
		{
			// $nominal = $nominal.',00';
			$nominal = $nominal.'.00';
		}
		else
		{
			// $tmp = explode(',', $nominal);
			$tmp = explode('.', $nominal);
			if(strlen($tmp[1]) == 1)
			{
				$nominal = $nominal.'0';
			}
		}
		return $nominal;
	}

	public static function currency_transaction($transaction_id, $nominal)
	{
		$helper = new \App\Helper\Common_helper;

		$newFormatNominal = $helper->convert_to_format_currency($nominal);

		$currency_id = '1';
        $getCurrency = \App\Models\EmTransactionMeta::getMeta(array('transaction_id' => $transaction_id, 'meta_key' => 'currency_id'));
        if($getCurrency)
        {
            if(isset($getCurrency->meta_description) && $getCurrency->meta_description != null)
            {
                $currency_id = $getCurrency->meta_description;
            }
        }

		$getMeta = EmTransactionMeta::getMeta(array('transaction_id' => $transaction_id, 'meta_key' => 'currency_id'));
		$getCurrency = $helper->get_current_currency($getMeta->meta_description);

		return array($nominal, $newFormatNominal, $getCurrency[1], $getCurrency[2], $currency_id);
	}


	public static function show_social_media()
	{
		$getData = EmSocialMedia::getWhere([], '', false);
		$htmlBuilder = '';
		foreach ($getData as $key) 
		{
			if($key->social_url != '')
			{
				if($key->social_name == 'facebook')
				{
					$htmlBuilder .= '<a title="FACEBOOK '.env('AUTHOR_SITE').'" class="facebook" target="_blank" href="'.$key->social_url.'"><i class="fa fa-facebook"></i></a>';
				}
				else if($key->social_name == "instagram")
				{
					$htmlBuilder .= '<a title="INSTAGRAM '.env('AUTHOR_SITE').'" class="instagram" target="_blank" href="'.$key->social_url.'"><i class="fa fa-instagram"></i></a>';
				}
				else
				{
					$htmlBuilder .= '<a title="TWITTER '.env('AUTHOR_SITE').'" class="twitter" target="_blank" href="'.$key->social_url.'"><i class="fa fa-twitter"></i></a>';
				}
			}
		}

		return $htmlBuilder;
	}

	public static function checkCart()
	{
		$result = array(false, 0);
		if(Session::get('cart') != null)
		{
			$result = array(true, sizeof(Session::get('cart')));
		}
		// else
		// {
		// 	if(Cookie::get(sha1(env('AUTHOR_SITE'))) != null)
		// 	{
		// 		Session::put('cart', unserialize(Cookie::get(sha1(env('AUTHOR_SITE')))));

		// 		$result = array(true, sizeof(Session::get('cart')));
		// 	}
		// }

		return $result;
	}

	public static function checkCartIncomplete(){
		$count = 0;
		if(Session::get(env('SES_FRONTEND_ID')) != null){
			$getTransactionIncomplete = EmTransaction::where('payment_status', 0)->where('status', '1')->where('customer_id', Session::get(env('SES_FRONTEND_ID')))->get();
			$count = sizeof($getTransactionIncomplete);
		}
		return $count;
	}

	public static function check_shipping($destination = array())
	{
		$helper = new \App\Helper\Common_helper;

		$cityID = 17;
		$subdistrictID = 259;

		if($destination['national'])
		{
			//check shipping national
			//from
			$origin = array(
				'city' => $cityID,
				'subdistrict' => $subdistrictID
			);
			$originType = $destination['origin_type']; //city or subdistrict

			//destination
			$originDestination = array(
				'city' => $destination['city'],
				'subdistrict' => $destination['subdistrict']
			);
			$originTypeDestination = $destination['origin_type']; //city or subdistrict

			$urlAPI = 'https://pro.rajaongkir.com/api/cost';
			$urlGet= 'origin='.$origin[$originType].'&originType='.$originType.'&destination='.$originDestination[$originTypeDestination].'&destinationType='.$originTypeDestination.'&weight='.$destination['weight'].'&courier=jne';
			//---------

			return $helper->process_check_shipping($urlAPI, $urlGet);
		}
		else
		{
			//check shipping international
			//from
			$origin = $cityID;

			//destination
			$originDestination = $destination['country'];

			$urlAPI = 'https://pro.rajaongkir.com/api/v2/internationalCost';
			$urlGet= 'origin='.$origin.'&destination='.$originDestination.'&weight='.$destination['weight'].'&courier=pos';
			//---------

			return $helper->process_check_shipping($urlAPI, $urlGet);
		}
	}

	public static function process_check_shipping($urlAPI, $urlGet)
	{
		$api = '3480d11ab493016567592a7b402db925';

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  	CURLOPT_URL => $urlAPI,
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
		  	CURLOPT_MAXREDIRS => 10,
		  	CURLOPT_TIMEOUT => 30,
		  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  	CURLOPT_CUSTOMREQUEST => "POST",
		  	CURLOPT_POSTFIELDS => $urlGet,
		  	CURLOPT_HTTPHEADER => array(
		    	"content-type: application/x-www-form-urlencoded",
		    	"key: ".$api
		  	),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		if ($err) 
		{
			return array(false, "cURL Error #:" . $err);
		} 
		else 
		{
			return array(true, $response);
		}
	}

	public static function phone_no_prefix($prefix, $phone_number)
	{
		$prefixLength = strlen($prefix);
		return substr($phone_number, $prefixLength, (strlen($phone_number) - $prefixLength));
	}

	public static function check_currency_country()
	{
		// echo '<span class="none">'.Session::get(env('SES_GLOBAL_CURRENCY')).'</span>';
		if(Session::get(env('SES_GLOBAL_CURRENCY')) == null)
		{
			$real_ip_adress = '';
			if (isset($_SERVER['HTTP_CLIENT_IP']))
			{
			    $real_ip_adress = $_SERVER['HTTP_CLIENT_IP'];
			}

			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
			    $real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			// else
			// {
			    $real_ip_adress = $_SERVER['REMOTE_ADDR'];
			    // echo $real_ip_adress2;
			// }

			// echo $real_ip_adress;
			$helper = new \App\Helper\Common_helper;
			$getData = $helper->ip_info($real_ip_adress);
			// print_r($getData);
			$currency_id = '2';
			if(isset($getData['country']))
			{
				// echo '<span class="none">'.$getData['country'].'</span>';
				if($getData['country'] == 'Indonesia')
				{
					$currency_id = '1';
				}
			}

			Session::put(env('SES_GLOBAL_CURRENCY'), $currency_id);
		}
	}

	public static function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) 
	{
		$output = NULL;
		if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
		  $ip = $_SERVER["REMOTE_ADDR"];
		  if ($deep_detect) {
		      if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
		          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		      if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
		          $ip = $_SERVER['HTTP_CLIENT_IP'];
		  }
		}
		$purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
		$support    = array("country", "countrycode", "state", "region", "city", "location", "address");
		$continents = array(
		  "AF" => "Africa",
		  "AN" => "Antarctica",
		  "AS" => "Asia",
		  "EU" => "Europe",
		  "OC" => "Australia (Oceania)",
		  "NA" => "North America",
		  "SA" => "South America"
		);
		if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
		  $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
		  if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
		      switch ($purpose) {
		          case "location":
		              $output = array(
		                  "city"           => @$ipdat->geoplugin_city,
		                  "state"          => @$ipdat->geoplugin_regionName,
		                  "country"        => @$ipdat->geoplugin_countryName,
		                  "country_code"   => @$ipdat->geoplugin_countryCode,
		                  "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
		                  "continent_code" => @$ipdat->geoplugin_continentCode
		              );
		              break;
		          case "address":
		              $address = array($ipdat->geoplugin_countryName);
		              if (@strlen($ipdat->geoplugin_regionName) >= 1)
		                  $address[] = $ipdat->geoplugin_regionName;
		              if (@strlen($ipdat->geoplugin_city) >= 1)
		                  $address[] = $ipdat->geoplugin_city;
		              $output = implode(", ", array_reverse($address));
		              break;
		          case "city":
		              $output = @$ipdat->geoplugin_city;
		              break;
		          case "state":
		              $output = @$ipdat->geoplugin_regionName;
		              break;
		          case "region":
		              $output = @$ipdat->geoplugin_regionName;
		              break;
		          case "country":
		              $output = @$ipdat->geoplugin_countryName;
		              break;
		          case "countrycode":
		              $output = @$ipdat->geoplugin_countryCode;
		              break;
		      }
		  }
		}
		return $output;
  	}

  	public static function check_decimal($total)
  	{
  		$tmp = explode('.', $total);
  		if(sizeof($tmp) > 1)
  		{
  			$first = $tmp[0].'.'.substr($tmp[1], 0, 2);
  			return $first;
  		}

  		return $total;
  	}

  	public static function getTimezone()
  	{
  		$timezone_offset_minutes = 480;  // $_GET['timezone_offset_minutes']
		// Convert minutes to seconds
		$timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);
		// Asia/Kolkata
		return $timezone_name;
  	}

  	public static function timerCheckout($dateTimeTrans, $timezoneTrans)
  	{
  		$timeStart = date('Y/m/d H:i:s', $dateTimeTrans);
  		$timeEnd = date('Y/m/d H:i:s', strtotime($timeStart.' + 1 days'));

  		$result['timeEnd'] = $timeEnd;
		$result['timeStart'] = $timeStart;

		return $result;

  		// $dateTimeTrans = date('Y-m-d H:i:s', $dateTimeTrans);

		// $datetime = new \DateTime($dateTimeTrans);
		// $timeEurope = new \DateTimeZone($timezoneTrans);
		// $datetime->setTimezone($timeEurope);
		// $timeEnd = $datetime->format('Y-m-d H:i:s');
		// $timeEnd = date('Y/m/d H:i:s', strtotime($timeEnd. '+1 days'));

		// $timezone = new \DateTimeZone($timezoneTrans);
		// $date = new \DateTime();
		// $date->setTimeZone($timezone);
		// $timeStart = $date->format('Y/m/d H:i:s');

		// $result['limit'] = false;
		// if(strtotime($timeEnd) >= strtotime($timeStart))
		// {
		// 	$result['limit'] = true;
		// }

		// $result['timeEnd'] = $timeEnd;
		// $result['timeStart'] = $timeStart;

		// return $result;
  	}

  	public static function checkMessage()
  	{
  		return '';
  	}
}