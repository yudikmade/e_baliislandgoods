<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 08 Jan 2019 22:25:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MSubdistrict
 * 
 * @property int $subdistrict_id
 * @property int $city_id
 * @property string $subdistrict_name
 * @property string $status
 *
 * @package App\Models
 */
class MSubdistrict extends Eloquent
{
	protected $table = 'm_subdistrict';
	protected $primaryKey = 'subdistrict_id';
	public $timestamps = false;

	protected $casts = [
		'city_id' => 'int'
	];

	protected $fillable = [
		'city_id',
		'subdistrict_name',
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

		$getData = $getData->orderBy('subdistrict_name', 'ASC');

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
