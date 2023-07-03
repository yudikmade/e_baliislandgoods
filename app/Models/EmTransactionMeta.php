<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmTransactionMetum
 * 
 * @property int $meta_id
 * @property int $transaction_id
 * @property string $meta_key
 * @property string $meta_description
 *
 * @package App\Models
 */
class EmTransactionMeta extends Eloquent
{
	protected $primaryKey = 'meta_id';
    protected $table = 'em_transaction_meta';
	public $timestamps = false;

	protected $casts = [
		'transaction_id' => 'int'
	];

	protected $fillable = [
		'transaction_id',
		'meta_key',
		'meta_description'
	];


    public function scopeGetWhere($query, $where) 
    {
        $getData = \DB::table($this->table)
                ->where($where);
                return $getData->get(); 
    }

	public function scopeGetMeta($query, $dataWhere) 
	{
    	return \DB::table($this->table)
    			->where([
    				['transaction_id', '=', $dataWhere['transaction_id']], 
    				['meta_key', '=', $dataWhere['meta_key']]
    			])->first();	
    }

    public function scopeUpdateMeta($query, $dataUpdate)
    {
    	$checkData = \DB::table($this->table)->where([
    				['transaction_id', '=', $dataUpdate['transaction_id']], 
    				['meta_key', '=', $dataUpdate['meta_key']]
    			])->count();

    	if($checkData > 0)
    	{
    		\DB::table($this->table)
    			->where('transaction_id', $dataUpdate['transaction_id'])
    			->where('meta_key', $dataUpdate['meta_key'])
    			->update(['meta_description' => $dataUpdate['meta_description']]);
    	}
    	else
    	{
    		\DB::table($this->table)->insert([
    			'transaction_id' => $dataUpdate['transaction_id'],
    			'meta_key' => $dataUpdate['meta_key'],
    			'meta_description' => $dataUpdate['meta_description']
    		]);
    	}
    }
}
