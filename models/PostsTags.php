<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/14
 * Time: 14:07
 */

namespace Models;


use Illuminate\Database\Eloquent\Model;

class PostsTags extends Model
{
    protected $fillable = ['post_id', 'tag_id'];
}