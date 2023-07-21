<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 07 Jan 2019 20:52:07 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MShippingCostDefault
 * 
 * @property int $shipping_cost_id
 * @property float $cost
 * @property string $status
 *
 * @package App\Models
 */
class MShippingCostDefault extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'm_shipping_cost_default';
	protected $primaryKey = 'shipping_cost_id';
	public $timestamps = false;

	protected $casts = [
		'cost' => 'float'
	];

	protected $fillable = [
		'cost',
		'status'
	];

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
