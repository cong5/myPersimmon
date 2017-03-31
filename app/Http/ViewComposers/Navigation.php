<?php
/**
 * Created by PhpStorm.
 * User: MrCong
 * Date: 2017/2/23
 * Time: 00:04
 */

namespace App\Http\ViewComposers;


use Illuminate\Contracts\View\View;
use Models\Options;

class Navigation
{

    private $navigation;

    public function __construct()
    {
    }

    public function compose(View $view){
        $navigation = Options::where('option_name','navigations')->select('option_value')->first();
        $navigation = !empty($navigation->option_value) ? json_decode($navigation->option_value,true) : [];
        $view->with(compact('navigation'));
    }

}