<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

use App\Models\EmCustomer;
use App\Models\EmProduct;
use App\Models\EmTransaction;
use App\Helper\Common_helper;

class DashboardController extends Controller
{
	public function index($year = '')
	{
		Common_helper::check_session_backend(true);

        $category_admin = Session::get(env('SES_BACKEND_CATEGORY'));
		
		if($year == '')
		{
			$year = date('Y');
		}

		//set chart dashboard
		$jsTotalTransaction = '';
		$jsTotalTransactionCancel = '';
		$jsTotalTransactionPaid = '';
		$jsTotalTransactionProscessed = '';
        for ($i = 1; $i <= 12 ; $i++) 
        {
            $startDate = strtotime($year."-".$i."-01 00:00:00");
            $endDate = strtotime(date("Y-m-t", $startDate).' 23:59:59');

            //new order
            $getTransaction = 0;
            if(is_null($category_admin)) {
                $getTransaction = EmTransaction::getWhereCount([['status', '=', '1'], ['transaction_date', '>=', $startDate], ['transaction_date', '<=', $endDate]], '');
            } else {
                $getTransaction = EmTransaction::getWhereCountWithCompany([['em_transaction.status', '=', '1'], ['em_transaction.transaction_date', '>=', $startDate], ['em_transaction.transaction_date', '<=', $endDate]], '', Session::get(env('SES_BACKEND_ID')));
            }
            $jsTotalTransaction .= $getTransaction;

            //cancel
            $getTransaction = 0;
            if(is_null($category_admin)) {
                $getTransaction = EmTransaction::getWhereCount([['status', '=', '5'], ['transaction_date', '>=', $startDate], ['transaction_date', '<=', $endDate]], '');
            } else {
                $getTransaction = EmTransaction::getWhereCountWithCompany([['em_transaction.status', '=', '5'], ['em_transaction.transaction_date', '>=', $startDate], ['em_transaction.transaction_date', '<=', $endDate]], '', Session::get(env('SES_BACKEND_ID')));
            }
            $jsTotalTransactionCancel .= $getTransaction;

            //paid
            $getTransaction = 0;
            if(is_null($category_admin)) {
                $getTransaction = EmTransaction::getWhereCount([['status', '=', '2'], ['transaction_date', '>=', $startDate], ['transaction_date', '<=', $endDate]], '');
            } else {
                $getTransaction = EmTransaction::getWhereCountWithCompany([['em_transaction.status', '=', '2'], ['em_transaction.transaction_date', '>=', $startDate], ['em_transaction.transaction_date', '<=', $endDate]], '', Session::get(env('SES_BACKEND_ID')));
            }
            $jsTotalTransactionPaid .= $getTransaction;

            //on process
            $getTransaction = 0;
            if(is_null($category_admin)) {
                $getTransaction = EmTransaction::getWhereCount([['status', '=', '3'], ['transaction_date', '>=', $startDate], ['transaction_date', '<=', $endDate]], '');
            } else {
                $getTransaction = EmTransaction::getWhereCountWithCompany([['em_transaction.status', '=', '3'], ['em_transaction.transaction_date', '>=', $startDate], ['em_transaction.transaction_date', '<=', $endDate]], '', Session::get(env('SES_BACKEND_ID')));
            }
            $jsTotalTransactionProscessed .= $getTransaction;
            
            if($i != 12)
            {
                $jsTotalTransaction .= ',';  
                $jsTotalTransactionCancel .= ',' ;
                $jsTotalTransactionPaid .= ',' ;
                $jsTotalTransactionProscessed .= ',' ;
            }
        }

        $count_products = 0;
        if(is_null($category_admin)){
            $count_products = Common_helper::convert_to_format_currency(EmProduct::getWhereCount([['status', '=', '1']], ''));
        } else {
            $count_products = Common_helper::convert_to_format_currency(EmProduct::getWhereCount([['status', '=', '1'], ['admin_id', '=', Session::get(env('SES_BACKEND_ID'))]], ''));
        }

        $count_transactions = 0;
        if(is_null($category_admin)){
            $count_transactions = Common_helper::convert_to_format_currency(EmTransaction::getWhereCount([], "status NOT IN ('0', '6')"));
        } else {
            $count_transactions = Common_helper::convert_to_format_currency(EmTransaction::getWhereCountWithCompany([], "em_transaction.status NOT IN ('0', '6')", Session::get(env('SES_BACKEND_ID'))));
        }

		$data = array(
            'title' => 'Dashboard | Administrator',
            'title_page' => 'Dashboard',
            'breadcrumbs' => '<li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>',
            'count_customers' => Common_helper::convert_to_format_currency(EmCustomer::getWhereCount([['status', '=', '1']], '')),
            'count_products' => $count_products,
            'count_transactions' => $count_transactions,
            'year' => $year,
			'menu_order' => 1,
			'js_total_transaction' => $jsTotalTransaction,
			'js_total_transaction_cancel' => $jsTotalTransactionCancel,
			'js_total_transaction_paid' => $jsTotalTransactionPaid,
			'js_total_transaction_processed' => $jsTotalTransactionProscessed,
        );
		return view('admin.dashboard.dashboard', $data);
	}
}