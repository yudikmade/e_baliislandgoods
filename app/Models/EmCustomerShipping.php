<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmCustomerShipping
 * 
 * @property int $shipping_id
 * @property int $country_id
 * @property string $country_name
 * @property int $province_id
 * @property string $province_name
 * @property int $city_id
 * @property string $city_name
 * @property int $subdistrict_id
 * @property string $subdistrict_name
 * @property int $order
 * @property string $status
 *
 * @package App\Models
 */
class EmCustomerShipping extends Eloquent
{
	private $limitPaging = 20;
	protected $table = 'em_customer_shipping';
	protected $primaryKey = 'shipping_id';
	public $timestamps = false;

	protected $casts = [
		'country_id' => 'int',
		'province_id' => 'int',
		'city_id' => 'int',
		'subdistrict_id' => 'int',
		'order' => 'int'
	];

	protected $fillable = [
		'country_id',
		'country_name',
		'province_id',
		'province_name',
		'city_id',
		'city_name',
		'subdistrict_id',
		'subdistrict_name',
		'detail_address',
		'order',
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
		$getData = $getData->orderBy('order', 'DESC');
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

    public function scopeInsertData($query, $dataInsert)
    {
        \DB::table($this->table)->insert($dataInsert);
    }

    public function scopeUpdateData($query, $id, $dataUpdate)
    {
        \DB::table($this->table)->where($this->primaryKey, $id)->update($dataUpdate);
    }

    public function scopeUpdateDataByCustomer($query, $id, $dataUpdate)
    {
        \DB::table($this->table)->where('customer_id', $id)->update($dataUpdate);
    }
}
