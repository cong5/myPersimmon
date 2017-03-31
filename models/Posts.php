<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/10
 * Time: 11:11
 */

namespace Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model
{
    use SoftDeletes;

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'flag'];


    public function scopeOfType($query, $type)
    {
        return $query;
    }

    /**
     * 注入分类查询的条件
     * @param $query
     * @param $category_id
     * @return mixed
     * @author Mr.Cong<i@cong5.net>
     */
    public function scopeOfCategory($query, $category_id)
    {
        if (intval($category_id) > 0) {
            return $query->where('category_id', $category_id);
        }
        return $query;
    }

    /**
     * 注入文章标题模糊查询的条件
     * @param $query
     * @param $title
     * @return mixed
     * @author Mr.Cong<i@cong5.net>
     */
    public function scopeOfTitle($query, $title)
    {
        if (!empty($title)) {
            return $query->where('title', 'like', '%' . $title . '%');
        }
        return $query;
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class,'posts_tags','');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsTo(Categorys::class, 'category_id');
    }
}