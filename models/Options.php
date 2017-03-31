<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 10:53
 */

namespace Models;


use Illuminate\Database\Eloquent\Model;

class Options extends Model
{

    protected $fillable = ['option_name', 'option_value'];

    public function scopeNotbase($query)
    {
        return $query->where('option_status', '!=', 'base');
    }

    public function scopeNothidden($query)
    {
        return $query->where('option_status', '!=', 'hidden');
    }

}