<?php
/**
 * Created by PhpStorm.
 * User: MrCong
 * Date: 2017/2/23
 * Time: 00:09
 */

namespace App\Providers;


use App\Http\ViewComposers\Navigation;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    public function boot(){
        view()->composer('app.widgets.navigation',Navigation::class);
    }

    public function register(){

    }

}