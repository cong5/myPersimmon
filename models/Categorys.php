<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/10
 * Time: 9:57
 */

namespace Models;


use Illuminate\Database\Eloquent\Model;

class Categorys extends Model
{
    protected $fillable = ['category_name', 'category_flag'];

    public function posts()
    {
        return $this->hasMany(Posts::class,'category_id');
    }
}