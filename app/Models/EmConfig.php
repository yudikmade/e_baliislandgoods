<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 13 Jan 2019 10:53:15 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmConfig
 * 
 * @property int $config_id
 * @property string $meta_key
 * @property string $meta_value
 *
 * @package App\Models
 */
class EmConfig extends Eloquent
{
	protected $table = 'em_config';
	protected $primaryKey = 'config_id';
	public $timestamps = false;

	protected $fillable = [
		'meta_key',
		'meta_value'
	];

	public function scopeGetData($query, $dataWhere) 
	{
    	$value = \DB::table($this->table)
    			->where([ 
    				['meta_key', '=', $dataWhere['meta_key']]
    			])->first();
		
		if($value){
			return $value->meta_value;
		} else {
			return '';
		}
    }

    public function scopeUpdateData($query, $dataUpdate)
    {
    	$checkData = \DB::table($this->table)->where([
    				['meta_key', '=', $dataUpdate['meta_key']]
    			])->count();

    	if($checkData > 0)
    	{
    		\DB::table($this->table)
    			->where('meta_key', $dataUpdate['meta_key'])
    			->update(['meta_value' => $dataUpdate['meta_value']]);
    	}
    	else
    	{
    		\DB::table($this->table)->insert([
    			'meta_key' => $dataUpdate['meta_key'],
    			'meta_value' => $dataUpdate['meta_value']
    		]);
    	}
    }
}
