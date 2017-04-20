<?php
/**
 * Created by PhpStorm.
 * User: MrCong
 * Date: 2017/2/19
 * Time: 21:43
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Models\Options;

class SettingsController extends Controller
{

    /**
     * get setting
     * @param Request $request
     * @author Mr.Cong<i@cong5.net>
     */
    public function index()
    {
        $listData = Options::nothidden()->get();
        return response()->json($listData);
    }


    /**
     * update setting
     * @param Request $request
     * @author Mr.Cong<i@cong5.net>
     */
    public function update(Request $request)
    {
        $post = $request->all();
        foreach ($post as $key => $option) {
            $model = Options::firstOrNew(['option_name' => $key]);
            $model->option_value = $option;
            $model->save();
            unset($model);
        }
        app(\Persimmon\Services\RedisCache::class)->updateSetting();
        return response()->json(['status' => 'success']);
    }

}