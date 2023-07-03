<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 02:58:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmProductCategory
 * 
 * @property int $category_id
 * @property string $category
 * @property int $parent
 * @property string $status
 *
 * @package App\Models
 */
class EmProductCategory extends Eloquent
{
	protected $limitPaging = 20;
	protected $table = 'em_product_category';
	protected $primaryKey = 'category_id';
	public $timestamps = false;

	protected $with = ["sub_categories"];

	protected $casts = [
		'parent' => 'int'
	];

	protected $fillable = [
		'category',
		'parent',
		'status',
		'description',
		'image'
	];

	public function sub_categories(){
        return $this->hasMany("App\Models\EmProductCategory", "parent", "category_id");
    }
    // public function parent(){
    //     return $this->belongsTo("App\Models\EmProductCategory", "parent", "category_id");
    // }

	public function scopeGetWhere($query, $where, $where_raw = '', $paging = true) 
	{
    	$getData = \DB::table($this->table)
    			->where($where);

		if($where_raw != '')
		{
			$getData = $getData->whereRaw($where_raw);
		}

		$getData = $getData->orderBy('category', 'ASC');
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
        return $getData->category_id;
    }

    public function scopeGetOneHierarchy($query, $id)
    {
    	$category = \DB::table($this->table)->where([['status', '!=', '2'], ['category_id', '=', $id]])->get();
        $parents = [];
        $current = $category[0]->parent;
        while($current != null)
        {
            $parent = \DB::table($this->table)->where([['status', '!=', '2'], ['category_id', '=', $current]])->get();
            array_unshift($parents, $parent[0]);
            $current = $parent[0]->parent;
        }
        
        $category["parent"] = $parents;
        return $category;
    }
}
