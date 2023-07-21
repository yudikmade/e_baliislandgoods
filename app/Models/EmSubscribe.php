<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MBank
 * 
 * @property int $bank_id
 * @property string $bank_name
 * @property string $account_name
 * @property string $account_number
 * @property int $order
 * @property string $status
 *
 * @package App\Models
 */
class EMSubscribe extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'em_subscribe';
	protected $primaryKey = 'subscribe_id';
	public $timestamps = false;

	protected $casts = [
		'subscribe_id' => 'int'
	];

	protected $fillable = [
		'email',
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

		$getData = $getData->orderBy('email', 'ASC');

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

    public function scopeDeleteData($query, $id)
    {
    	\DB::table($this->table)->where($this->primaryKey, $id)->delete();
    }
}
