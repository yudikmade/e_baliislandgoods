<?php

namespace App\Models;

use App\Models\EmProduct;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ExportProduct implements FromView
{
	private $search;

    public function __construct(string $search, string $admin_id)
    {
        $this->search = $search;
        $this->admin_id = $admin_id;
    }

    public function view(): View
    {
	    return view('export.product', [
            'data_result' => EmProduct::where('status', '!=', '2')->where('admin_id',$this->admin_id)->whereRaw("(product_name like '%".$this->search."%' OR product_code like '%".$this->search."%')")->get()
        ]);   
    }
}