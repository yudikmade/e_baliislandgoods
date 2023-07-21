<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmProduct
 * 
 * @property string $product_id
 * @property int $category_id
 * @property int $admin_id
 * @property string $product_code
 * @property string $product_name
 * @property string $description
 * @property string $description_html
 * @property float $price
 * @property float $discount
 * @property int $date_in
 * @property int $last_update
 * @property string $status
 *
 * @package App\Models
 */
class EmProduct extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'em_product';
	protected $primaryKey = 'product_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $with = ["category"];

	protected $casts = [
		'category_id' => 'int',
		'admin_id' => 'int',
		'price' => 'float',
		'discount' => 'float',
		'date_in' => 'int',
		'last_update' => 'int',
		'stock' => 'float',
	];

	protected $fillable = [
		'category_id',
		'admin_id',
		'product_code',
		'product_name',
		'description',
		'description_html',
		'price',
		'discount',
		'date_in',
		'last_update',
		'status',
		'stock',
		'order',
	];

	public function category()
	{
	    return $this->hasOne('App\Models\EmProductCategory', 'category_id', 'category_id');
	}

	public function scopeGetWithImage($query, $whereRaw, $offset = 0, $limit = 20, $paging = true, $random = false)
	{
	    $exe_query = \DB::table($this->table)
            ->join('em_product_img', $this->table.'.product_id', '=', 'em_product_img.product_id')
            ->join('em_product_category', $this->table.'.category_id', '=', 'em_product_category.category_id')
            ->select(
                'em_product_img.image',
				'em_product_category.category',
                $this->table.'.*');

            if($whereRaw != '')
            {
                $exe_query = $exe_query->whereRaw($whereRaw);
            }
            
            $exe_query = $exe_query->where($this->table.'.status', '=', '1')
            ->where('em_product_img.order', '=', '1');

        if($paging)
        {
			if($random){
				return $exe_query->orderByRaw('RAND()')->offset($offset)->limit($limit)->get();
			} else {
        		return $exe_query->orderBy($this->table.'.order', 'DESC')->offset($offset)->limit($limit)->get();
			}
    	}
    	else
    	{
    		return $exe_query->orderBy($this->table.'.order', 'DESC')->get();
    	}
	}

	public function scopeGetWhere($query, $where, $where_raw = '', $paging = true) 
	{
    	$getData = \DB::table($this->table)
    			->where($where);

		if($where_raw != '')
		{
			$getData = $getData->whereRaw($where_raw);
		}

		$getData = $getData->orderBy('date_in', 'DESC');
		
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
        $getData = \DB::table($this->table)->orderBy($this->primaryKey, 'DESC')->first();
        return $getData->product_id;
    }

    public function scopeNewOrder($query)
    {
        $getData = \DB::table($this->table)->where([['status', '!=', '2']])->orderBy('order', 'DESC')->first();
        if($getData)
        {
			return ($getData->order + 1);
		}
		else
		{
			return '1';
		}
    }
}
