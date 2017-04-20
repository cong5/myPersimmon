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
use Illuminate\Support\Facades\DB;
use Models\Posts;
use Persimmon\Interfaces\CreatorInterface;

class PostsController extends Controller implements CreatorInterface
{

    private $_response = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rows = intval($request->rows) > 0 ? $request->rows : 20;
        $listData = Posts::OfCategory($request->category_id)->OfTitle($request->q)->paginate($rows);
        $listData = $this->transform($listData);
        return response()->json($listData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'flag' => 'required|flag',
            'category_id' => 'required',
            'markdown' => 'required'
        ]);
        app(\Persimmon\Creator\PostsCreator::class)->create($this, $request);
        return response()->json($this->_response);
    }

    /**
     * Update a category
     *
     * @param Request $request
     * @param $id
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'flag' => 'required|flag',
            'category_id' => 'required',
            'markdown' => 'required'
        ]);
        app(\Persimmon\Creator\PostsCreator::class)->update($this, $request);
        return response()->json($this->_response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Posts::find($id);
        $data['tags'] = $data->tags;
        return response()->json($data);
    }


    /**
     * Remove the specified  from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required'
        ]);
        $result = Posts::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => !$result ? 'error' : 'success']);
    }

    /**
     * data transform format
     *
     * @param $data
     * @return mixed
     */
    private function transform($data)
    {
        foreach ($data as $key => $item) {
            $item->tags;
            $item->categories;
        }
        return $data;
    }

    /*******************************************
     * Delegate Action
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