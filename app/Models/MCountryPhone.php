<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MCountryPhone
 * 
 * @property int $country_phone_id
 * @property string $name
 * @property string $code
 * @property string $phone_prefix
 * @property int $order
 * @property int $status
 *
 * @package App\Models
 */
class MCountryPhone extends Eloquent
{
	protected $table = 'm_country_phone';
	protected $primaryKey = 'country_phone_id';
	public $timestamps = false;

	protected $casts = [
		'order' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'code',
		'phone_prefix',
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

		$getData = $getData->orderBy('name', 'ASC');

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
