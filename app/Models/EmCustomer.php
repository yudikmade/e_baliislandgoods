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
class EmCustomer extends Authenticatable
{
	protected $limitPaging = 20;
	protected $table = 'em_customer';
	protected $primaryKey = 'customer_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'country_phone_id' => 'int',
		'exp_reset_key' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'customer_name',
		'country_phone_id',
		'phone_number',
		'email',
		'password',
		'status',
		'reset_key',
		'exp_reset_key'
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

    public function scopeInsertData($query, $dataInsert)
    {
        \DB::table($this->table)->insert($dataInsert);
        // $getData = \DB::table($this->table)->orderBy('register_date', 'DESC')->first();
        // return $getData->customer_id;
    }

    public function scopeUpdateData($query, $id, $dataUpdate)
    {
        \DB::table($this->table)->where($this->primaryKey, $id)->update($dataUpdate);
    }
}
