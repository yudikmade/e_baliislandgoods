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
class MBank extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'm_bank';
	protected $primaryKey = 'bank_id';
	public $timestamps = false;

	protected $casts = [
		'order' => 'int'
	];

	protected $fillable = [
		'bank_name',
		'account_name',
		'account_number',
		'order',
		'image',
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

    public function scopeNewOrder($query)
    {
        $getData = \DB::table($this->table)->where([['status', '!=', '2']])->orderBy('order', 'DESC')->first();
        if(count($getData) > 0)
        {
			return ($getData->order + 1);
		}
		else
		{
			return '1';
		}
    }
}
