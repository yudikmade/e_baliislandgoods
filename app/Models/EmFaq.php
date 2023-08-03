<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 03 Feb 2019 20:05:53 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmFaq
 * 
 * @property int $faq_id
 * @property string $question
 * @property string $answer
 * @property int $order
 *
 * @package App\Models
 */
class EmFaq extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'em_faq';
	protected $primaryKey = 'faq_id';
	public $timestamps = false;

	protected $casts = [
		'order' => 'int'
	];

	protected $fillable = [
		'question',
		'answer',
		'order'
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
        $getData = \DB::table($this->table)->orderBy('order', 'DESC')->first();
        if(isset($getData))
        {
			return ($getData->order + 1);
		}
		else
		{
			return '1';
		}
    }

    public function scopeDeleteData($query, $id)
    {
    	\DB::table($this->table)->where($this->primaryKey, $id)->delete();
    }
}
