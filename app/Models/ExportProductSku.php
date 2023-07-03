<?php

namespace App\Models;

use App\Models\EmProductSku;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ExportProductSku implements FromView
{
	private $search;

    public function __construct(string $search)
    {
        $this->search = $search;
    }

    public function view(): View
    {
	    return view('export.product_sku', [
            'data_result' => EmProductSku::getWhereJoin($this->search, false)
        ]);   
    }
}