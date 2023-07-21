<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmTransactionDetail
 * 
 * @property int $detail_id
 * @property int $transaction_id
 * @property string $product_id
 * @property int $sku_id
 * @property int $qty
 * @property float $price
 * @property float $discount
 * @property string $status
 *
 * @package App\Models
 */
class EmTransactionDetail extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'em_transaction_detail';
	protected $primaryKey = 'detail_id';
	public $timestamps = false;

	protected $casts = [
		'transaction_id' => 'int',
		'sku_id' => 'int',
		'qty' => 'int',
		'price' => 'float',
		'discount' => 'float'
	];

	protected $fillable = [
		'transaction_id',
		'product_id',
		'sku_id',
		'qty',
		'price',
		'discount',
		'status'
	];

	public function scopeGetAdmin($query, $transaction_id)
	{
		$getData = \DB::table($this->table)
			->select('em_administrator.email', 'em_administrator.admin_id')
			->join('em_product', $this->table.'.product_id', '=', 'em_product.product_id')
			->join('em_administrator', 'em_product.admin_id', '=', 'em_administrator.admin_id')
			->where($this->table.'.transaction_id',$transaction_id);

		return $getData->groupBy('em_administrator.email')->get();
	}

	public function scopeTransactionDetailData($query, $status, $date_transaction, $search, $paginate = true, $admin_id = null)
	{
		$getData = \DB::table($this->table)
			->select($this->table.'.*', 
				'em_transaction.transaction_code', 'em_transaction.transaction_date', 'em_transaction.status as status_transaction', 'em_transaction.type_payment', 'em_transaction.payment_status', 
				'em_product.product_code', 'em_product.product_name',
				'em_product_img.image',
				'em_product_sku.sku_code', 'em_product_sku.size', 'em_product_sku.color_name', 'em_product_sku.color_hexa'
			)
			->join('em_transaction', $this->table.'.transaction_id', '=', 'em_transaction.transaction_id')
	    	->join('em_product', $this->table.'.product_id', '=', 'em_product.product_id')
	    	->join('em_product_img', 'em_product.product_id', '=', 'em_product_img.product_id')
	    	->join('em_product_sku', $this->table.'.sku_id', '=', 'em_product_sku.sku_id')
			->where('em_product.admin_id',$admin_id)
	    	->whereNotIn('em_transaction.status', ['0', '6']);

    	if($status != 'all-status')
        {
            $getData = $getData->where([['em_transaction.status', '=', $status]]);
        }

        if($search != '')
        {
        	$getData = $getData->whereRaw("(em_transaction.transaction_code like '%".$search."%' OR em_product.product_code like '%".$search."%' OR em_product.product_name like '%".$search."%' OR em_product_sku.sku_code like '%".$search."%')");
        }

        if(sizeof($date_transaction) == 2)
        {
        	$getData = $getData->whereBetween('em_transaction.transaction_date', $date_transaction);
        }
        $getData = $getData->groupBy($this->table.'.detail_id');
        if($paginate)
        {
	    	return $getData->orderBy('em_transaction.transaction_date', 'DESC')->paginate($this->limitPaging);
    	}
    	else
    	{
    		return $getData->orderBy('em_transaction.transaction_date', 'DESC')->get();
    	}
	}

	public function scopeTransactionDetail($query, $dataWhere)
	{
		return \DB::table($this->table)
			->select($this->table.'.*', 
				'em_product.product_code', 'em_product.product_name',
				'em_product_img.image',
				'em_product_sku.sku_code', 'em_product_sku.size', 'em_product_sku.color_name', 'em_product_sku.color_hexa', 'em_product_sku.stock'
			)
	    	->join('em_product', $this->table.'.product_id', '=', 'em_product.product_id')
	    	->join('em_product_img', 'em_product.product_id', '=', 'em_product_img.product_id')
	    	->join('em_product_sku', $this->table.'.sku_id', '=', 'em_product_sku.sku_id')
	    	->where($dataWhere)
	    	->groupBy('em_product_sku.sku_id')->get();
	}

	public function scopeTransactionDetailPerAdmin($query, $dataWhere)
	{
		return \DB::table($this->table)
			->select($this->table.'.*', 
				'em_product.product_code', 'em_product.product_name',
				'em_product_img.image',
				'em_product_sku.sku_code', 'em_product_sku.size', 'em_product_sku.color_name', 'em_product_sku.color_hexa', 'em_product_sku.stock', 
				'em_transaction.transaction_date'
			)
			->join('em_transaction', $this->table.'.transaction_id', '=', 'em_transaction.transaction_id')
	    	->join('em_product', $this->table.'.product_id', '=', 'em_product.product_id')
	    	->join('em_product_img', 'em_product.product_id', '=', 'em_product_img.product_id')
	    	->join('em_product_sku', $this->table.'.sku_id', '=', 'em_product_sku.sku_id')
	    	->where($dataWhere)
	    	->groupBy('em_product_sku.sku_id')->get();
	}

	public function scopeGetWhere($query, $where, $where_raw = '', $paging = true) 
	{
    	$getData = \DB::table($this->table)
    			->where($where);

		if($where_raw != '')
		{
			$getData = $getData->whereRaw($where_raw);
		}

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
}
