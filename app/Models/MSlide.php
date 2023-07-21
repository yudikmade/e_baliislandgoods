<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 13 Jan 2019 10:53:16 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MSlide
 * 
 * @property int $slide_id
 * @property string $image
 * @property int $order
 *
 * @package App\Models
 */
class MSlide extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'm_slide';
	protected $primaryKey = 'slide_id';
	public $timestamps = false;

	protected $casts = [
		'order' => 'int'
	];

	protected $fillable = [
		'image',
		'order'
	];

	public function scopeGetWhere($query, $where, $paging = true) 
	{
    	$getData = \DB::table($this->table)->where($where)->orderBy('order', 'ASC');
		if($paging)
		{
			return $getData->paginate($this->limitPaging);
		}
		else
		{
			return $getData->get();	
		}
    }

    public function scopeGetWhereCount($query, $where) 
	{
    	$getData = \DB::table($this->table)->where($where);
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

    public function scopeNewOrder($query)
    {
        $getData = \DB::table($this->table)->orderBy('order', 'DESC')->first();
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
