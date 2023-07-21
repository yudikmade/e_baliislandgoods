<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmProductImg
 * 
 * @property int $img_id
 * @property int $sku_id
 * @property string $image
 * @property int $order
 *
 * @package App\Models
 */
class EmProductImg extends Eloquent
{
	protected $table = 'em_product_img';
	protected $primaryKey = 'img_id';
	public $timestamps = false;

	protected $casts = [
		'product_id' => 'int',
		'order' => 'int'
	];

	protected $fillable = [
		'product_id',
		'image',
		'order'
	];

	public function scopeGetWhere($query, $where, $where_raw = '', $paging = true) 
	{
    	$getData = \DB::table($this->table)
    		->select(
    			$this->table.'.img_id', $this->table.'.product_id', $this->table.'.sku_id', 
    			$this->table.'.image', $this->table.'.order', 
    			'em_product_sku.sku_code', 'em_product_sku.color_name', 'em_product_sku.size'
    		)
    		->join('em_product_sku', 'em_product_sku.sku_id', '=', $this->table.'.sku_id')
			->where($where);

		if($where_raw != ''){
			$getData = $getData->whereRaw($where_raw);
		}

		$getData = $getData->orderBy($this->table.'.sku_id', 'DESC');

		if($paging){
			return $getData->paginate($this->limitPaging);
		}else{
			return $getData->get();	
		}
    }

    public function scopeGetWhereLimitOne($query, $where) 
	{
    	return \DB::table($this->table)->where($where)->orderBy('order', 'DESC')->first();
    }

    public function scopeGetLastOne($query, $where) 
	{
    	return \DB::table($this->table)->where($where)->orderBy('img_id', 'DESC')->first();
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
        $getData = \DB::table($this->table)->orderBy($this->primaryKey, 'DESC')->first();
        return $getData->img_id;
    }

    public function scopeUpdateData($query, $id, $dataUpdate, $product_id)
    {
    	\DB::table($this->table)->where('product_id', $product_id)->update(['order' => null]);
        \DB::table($this->table)->where($this->primaryKey, $id)->update($dataUpdate);
    }

    public function scopeDeleteData($query, $id)
    {
    	\DB::table($this->table)->where($this->primaryKey, $id)->delete();
    }
}
