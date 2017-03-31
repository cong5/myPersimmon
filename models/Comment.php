<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 10:53
 */

namespace Models;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = ['id','posts_id'];

    public function posts()
    {
        return $this->belongsTo(Posts::class)->select('title');
    }

}