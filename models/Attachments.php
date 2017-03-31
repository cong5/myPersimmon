<?php
/**
 * Created by PhpStorm.
 * User: MrCong
 * Date: 2017/2/12
 * Time: 22:36
 */

namespace Models;


use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    protected $fillable = ['path', 'hash1', 'md5'];

}