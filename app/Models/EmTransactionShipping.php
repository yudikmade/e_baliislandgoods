<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 11 Jan 2019 23:40:54 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmTransactionShipping
 * 
 * @property int $shipping_id
 * @property int $transaction_id
 * @property int $country_id
 * @property string $country_name
 * @property int $province_id
 * @property string $province_name
 * @property int $city_id
 * @property string $city_name
 * @property int $subdistrict_id
 * @property string $subdistrict_name
 * @property string $detail_address
 *
 * @package App\Models
 */
class EmTransactionShipping extends Eloquent
{
	protected $table = 'em_transaction_shipping';
	protected $primaryKey = 'shipping_id';
	public $timestamps = false;

	protected $casts = [
		'transaction_id' => 'int',
		'country_id' => 'int',
		'province_id' => 'int',
		'city_id' => 'int',
		'subdistrict_id' => 'int'
	];

	protected $fillable = [
		'transaction_id',
		'country_id',
		'country_name',
		'province_id',
		'province_name',
		'city_id',
		'city_name',
		'subdistrict_id',
		'subdistrict_name',
		'detail_address'
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

    public function scopeUpdateDataByTransaction($query, $id, $dataUpdate)
    {
        \DB::table($this->table)->where('transaction_id', $id)->update($dataUpdate);
    }

    public function scopeInsertData($query, $dataInsert)
    {
        \DB::table($this->table)->insert($dataInsert);
    }
}
