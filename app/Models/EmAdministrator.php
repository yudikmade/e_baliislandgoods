<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmAdministrator
 * 
 * @property int $admin_id
 * @property int $category_id
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property string $status
 * @property string $reset_key
 * @property int $exp_reset_key
 *
 * @package App\Models
 */
class EmAdministrator extends Authenticatable
{
	protected $limitPaging = 20;
	protected $table = 'em_administrator';
	protected $primaryKey = 'admin_id';
	public $timestamps = false;

	protected $casts = [
		'category_id' => 'int',
		'exp_reset_key' => 'int',
		'register_date' => 'int',
		'last_update' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'category_id',
		'full_name',
		'email',
		'password',
		'status',
		'reset_key',
		'exp_reset_key',
		'register_date',
		'last_update'
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
}
