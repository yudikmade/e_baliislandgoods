<?php

namespace App\Models;

use App\Models\EmCustomer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ExportCustomer implements FromView
{
    private $search;

    public function __construct(string $search)
    {
        $this->search = $search;
    }

    public function view(): View
    {
        return view('export.customer', [
            'data_result' => EmCustomer::getWhere([['status', '!=', '2']], "(customer_name like '%" . $this->search . "%' OR email like '%" . $this->search . "%' OR phone_number like '%" . $this->search . "%')", false)
        ]);   
    }
}