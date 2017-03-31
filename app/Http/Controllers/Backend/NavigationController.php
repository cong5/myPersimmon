<?php
/**
 * Created by PhpStorm.
 * User: MrCong <i@cong5.net>
 * Date: 2017/2/10
 * Time: 9:49
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Models\Options;
use Models\Tags;
use Persimmon\Interfaces\CreatorInterface;

class NavigationController extends Controller implements CreatorInterface
{

    private $_response = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listData = Options::firstOrCreate(['option_name' => 'navigations']);
        $listData = $this->transform($listData);
        return response()->json($listData);
    }

    /**
     * Update a category
     *
     * @param Request $request
     * @param $id
     */
    public function update(Request $request)
    {
        $post = $request->all();
        $options = Options::firstOrCreate(['option_name' => 'navigations']);
        $options->option_value = json_encode($post);
        $response = $options->save();
        return response()->json(['status' => !$response ? 'error' : 'success']);
    }

    /**
     * data transform format
     *
     * @param $data
     * @return mixed
     */
    private function transform($data)
    {
        return !empty($data->option_value) ? json_decode($data->option_value,true) : [];
    }


    /*******************************************
     * delegate function
     ******************************************/

    /**
     * Create Fail Use
     *
     * @param $error
     * @return \Illuminate\Http\JsonResponse
     */
    public function creatorFail($error)
    {
        $this->_response = ['status' => 'error', 'info' => $error];
    }

    /**
     * Create Success Use
     *
     * @param $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function creatorSuccess($model)
    {
        $this->_response = ['status' => 'success', 'info' => '操作成功'];
    }

}