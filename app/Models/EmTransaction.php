<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmTransaction
 * 
 * @property int $transaction_id
 * @property string $transaction_code
 * @property string $customer_id
 * @property float $total_price
 * @property float $shipping_cost
 * @property int $additional_price
 * @property float $total_and_additional_price
 * @property string $type_payment
 * @property string $payment_status
 * @property string $status
 *
 * @package App\Models
 */
class EmTransaction extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'em_transaction';
	protected $primaryKey = 'transaction_id';
	public $timestamps = false;

	protected $casts = [
		'total_price' => 'float',
		'shipping_cost' => 'float',
		'additional_price' => 'int',
		'total_and_additional_price' => 'float',
		'transaction_date' => 'int',
	];

	protected $fillable = [
		'transaction_code',
		'customer_id',
		'total_price',
		'shipping_cost',
		'additional_price',
		'total_and_additional_price',
		'type_payment',
		'payment_status',
		'status',
		'transaction_date',
	];

	public function scopeTransactionData($query, $payment, $status, $date_transaction, $search, $paginate = true, $admin_id = null)
	{
		$getData = \DB::table($this->table)
			->select($this->table.'.*', 'em_customer.first_name', 'em_customer.last_name')
	    	->leftJoin('em_customer', $this->table.'.customer_id', '=', 'em_customer.customer_id')
			->join('em_transaction_detail', $this->table.'.transaction_id', '=', 'em_transaction_detail.transaction_id')
			->join('em_product', 'em_transaction_detail.product_id', '=', 'em_product.product_id')
			// ->join('em_administrator', 'em_product.admin_id', '=', 'em_administrator.admin_id')
	    	->whereNotIn($this->table.'.status', ['0', '6']);

    	if($payment != 'all-payments')
        {
            $getData = $getData->where([[$this->table.'.type_payment', '=', $payment]]);
        }

    	if($status != 'all-status')
        {
            $getData = $getData->where([[$this->table.'.status', '=', $status]]);
        }

        if($search != '')
        {
        	$getData = $getData->whereRaw("(".$this->table.".transaction_code like '%".$search."%' OR em_customer.first_name like '%".$search."%' OR em_customer.last_name like '%".$search."%' OR ".$this->table.".total_payment like '%".$search."%')");
        }

		// if(!is_null($admin_id)){
		// 	$getData = $getData->where('em_administrator.admin_id',$admin_id);
		// }

        if(sizeof($date_transaction) == 2)
        {
        	$getData = $getData->whereBetween($this->table.'.transaction_date', $date_transaction);
        }

        if($paginate)
        {
	    	return $getData->orderBy($this->table.'.transaction_date', 'DESC')->groupBy('em_transaction.transaction_code')->paginate($this->limitPaging);
    	}
    	else
    	{
    		return $getData->orderBy($this->table.'.transaction_date', 'DESC')->groupBy('em_transaction.transaction_code')->get();
    	}
	}

	public function scopeGetWhere($query, $where, $where_raw = '', $paging = true) 
	{
    	$getData = \DB::table($this->table)
    			->where($where);

		if($where_raw != '')
		{
			$getData = $getData->whereRaw($where_raw);
		}

		$getData = $getData->orderBy('transaction_date', 'DESC');

		if($paging)
		{
			return $getData->paginate($this->limitPaging);
		}
		else
		{
			return $getData->get();	
		}
    }

    public function scopeGetWhereLastOne($query, $where) 
	{
    	$getData = \DB::table($this->table)
    			->where($where);

		$getData = $getData->orderBy('transaction_date', 'DESC');
		return $getData->first();	
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

	public function scopeGetWhereCountWithCompany($query, $where, $where_raw = '', $admin_id) 
	{
    	$getData = \DB::table($this->table)
				->join('em_transaction_detail', 'em_transaction_detail.transaction_id', '=', $this->table . '.transaction_id')
				->join('em_product', 'em_product.product_id', '=', 'em_transaction_detail.product_id')
				// ->where('em_product.admin_id',$admin_id)
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
        $getData = \DB::table($this->table)->orderBy($this->primaryKey, 'DESC')->first();
        return $getData->transaction_id;
    }
}
