<?php

namespace App\Models;

use App\Models\EmTransactionDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ExportDetailTransaction implements FromView
{
    private $status;
    private $date_transaction;
    private $search;

    public function __construct(string $status, array $date_transaction, string $search, string $admin_id)
    {
        $this->status = $status;
        $this->date_transaction = $date_transaction;
        $this->search = $search;
        $this->admin_id = $admin_id;
    }

    public function view(): View
    {
        return view('export.transaction_detail', [
            'data_result' => EmTransactionDetail::transactionDetailData($this->status, $this->date_transaction, $this->search, false, $this->admin_id)
        ]);   
    }
}