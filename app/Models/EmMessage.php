<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmCustomer
 * 
 * @property string $customer_id
 * @property string $customer_name
 * @property int $country_phone_id
 * @property string $phone_number
 * @property string $email
 * @property string $password
 * @property string $status
 * @property string $reset_key
 * @property int $exp_reset_key
 *
 * @package App\Models
 */
class EmMessage extends Authenticatable
{
	protected $limitPaging = 10;
	protected $table = 'em_message';
	protected $primaryKey = 'message_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'message_id' => 'int',
		'customer_id' => 'int'
	];

	protected $fillable = [
		'subject',
		'message',
		'parent',
		'date_in',
		'last_update',
		'status',
		'read_admin',
		'read_customer'
	];

	public function scopeGetWhere($query, $where, $where_raw = '', $paging = true) 
	{
		$getData = \DB::table($this->table)
			->select($this->table.'.*', 'em_customer.first_name', 'em_customer.last_name')
	    	->leftJoin('em_customer', $this->table.'.customer_id', '=', 'em_customer.customer_id');
    	if(sizeof($where) > 0)
    	{
	    	$getData = $getData->where($where);
    	}

		if($where_raw != '')
		{
			$getData = $getData->whereRaw($where_raw);
		}

		$getData = $getData->orderBy($this->table.'.last_update', 'DESC');

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
        $getData = \DB::table($this->table)->orderBy('date_in', 'DESC')->first();
        return $getData->message_id;
    }

    public function scopeUpdateData($query, $id, $dataUpdate)
    {
        \DB::table($this->table)->where($this->primaryKey, $id)->update($dataUpdate);
    }
}
