<?php
/**
 * Created by PhpStorm.
 * User: MrCong <i@cong5.net>
 * Date: 2017/2/11
 * Time: 15:45
 */

namespace Models;


use Illuminate\Database\Eloquent\Model;

class Links extends Model
{

    protected $fillable = ['name', 'url', 'logo'];

}