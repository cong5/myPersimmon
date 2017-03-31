<?php
/**
 * Created by PhpStorm.
 * User: MrCong <i@cong5.net>
 * Date: 2017/2/13
 * Time: 10:14
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Persimmon\Services\BaiduTranslates;

class UtilController extends Controller
{

    public function index(Request $request)
    {
        $data = [];
        if (in_array($request->action, get_class_methods($this))) {
            $data = call_user_func_array([$this, $request->action], [$request->toArray()]);
        }
        return response()->json($data);
    }

    public function translates($params)
    {
        $translates = new BaiduTranslates();
        $data = $translates->exec($params);
        return $data;
    }

}