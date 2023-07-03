<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmProductSku
 * 
 * @property int $sku_id
 * @property string $sku_code
 * @property string $product_id
 * @property string $color_name
 * @property string $color_hexa
 * @property int $stock
 * @property int $order
 * @property string $status
 *
 * @package App\Models
 */
class EmProductSku extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'em_product_sku';
	protected $primaryKey = 'sku_id';
	public $timestamps = false;

	protected $casts = [
		'stock' => 'int',
		'product_id' => 'int',
		'order' => 'int',
		'date_in' => 'int',
		'last_update' => 'int'
	];

	protected $fillable = [
		'sku_code',
		'product_id',
		'size',
		'color_name',
		'color_hexa',
		'stock',
		'order',
		'status',
		'date_in',
		'last_update',
	];

	public function scopeGetWhereJoin($query, $search)
	{
	    $exe_query = \DB::table($this->table)
            ->join('em_product', $this->table.'.product_id', '=', 'em_product.product_id')
            ->select(
                'em_product.product_name',
                'em_product.product_code',
                $this->table.'.*');

            if($search != '')
            {
                $exe_query = $exe_query->whereRaw(
                	"(em_product.product_name LIKE '%". $search ."%' OR em_product.product_code LIKE '%". $search ."%' OR ".$this->table.".sku_code LIKE '%". $search ."%')"
            	);
            }
            
            $exe_query = $exe_query->where($this->table.'.status', '!=', '2')
            ->where('em_product.status', '!=', '2')
            ->orderBy($this->table.'.order', 'ASC');
        return $exe_query->paginate($this->limitPaging);
	}

	public function scopeGetWhere($query, $where, $where_raw = '', $paging = true) 
	{
    	$getData = \DB::table($this->table)
    			->where($where);

		if($where_raw != '')
		{
			$getData = $getData->whereRaw($where_raw);
		}

		$getData = $getData->orderBy('order', 'ASC');

		if($paging)
		{
			return $getData->paginate($this->limitPaging);
		}
		else
		{
			return $getData->get();	
		}
    }

    public function scopeGetWhereCount($query, $where, $where_raw = '') 
	{
    	$getData = \DB::table($this->table)
    			->where($where);
		if($where_raw != '')
		{
			$getData = $getData->whereRaw($where_raw);
		}
		return $getData->count();	
    }

    public function scopeUpdateData($query, $id, $dataUpdate)
    {
        \DB::table($this->table)->where($this->primaryKey, $id)->update($dataUpdate);
    }

    public function scopeInsertData($query, $dataInsert)
    {
        \DB::table($this->table)->insert($dataInsert);
    }

    public function scopeNewOrder($query, $id)
    {
        $getData = \DB::table($this->table)->where([['status', '!=', '2'], ['product_id', '=', $id]])->orderBy('order', 'DESC')->first();
        if(isset($getData->order))
        {
			return ($getData->order + 1);
		}
		else
		{
			return '1';
		}
    }
}
