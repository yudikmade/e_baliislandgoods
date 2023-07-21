<?php

namespace App\Models;

use App\Models\EmTransaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ExportTransaction implements FromView
{
	private $payment;
	private $status;
	private $date_transaction;
	private $search;

    public function __construct(string $payment, string $status, array $date_transaction, string $search, string $admin_id)
    {
        $this->payment = $payment;
        $this->status = $status;
        $this->date_transaction = $date_transaction;
        $this->search = $search;
        $this->admin_id = $admin_id;
    }

    public function view(): View
    {
	    return view('export.transaction', [
            'data_result' => EmTransaction::transactionData($this->payment, $this->status, $this->date_transaction, $this->search, false, $admin_id)
        ]);   
    }
}