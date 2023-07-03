<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 30 Mar 2019 10:25:56 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmCoupon
 * 
 * @property int $coupon_id
 * @property string $coupon_code
 * @property int $use_count
 * @property float $discount
 * @property string $status
 *
 * @package App\Models
 */
class EmCoupon extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'em_coupon';
	protected $primaryKey = 'coupon_id';
	public $timestamps = false;

	protected $casts = [
		'use_count' => 'int',
		'discount' => 'float'
	];

	protected $fillable = [
		'coupon_code',
		'use_count',
		'discount',
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
