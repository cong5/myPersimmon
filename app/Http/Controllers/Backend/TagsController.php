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
use Models\Tags;
use Persimmon\Interfaces\CreatorInterface;

class TagsController extends Controller implements CreatorInterface
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
        $listData = Tags::paginate($rows);
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
            'tags_name' => 'required',
            'tags_flag' => 'required|flag',
        ]);
        app(\Persimmon\Creator\TagsCreator::class)->create($this, $request);
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
            'tags_name' => 'required',
            'tags_flag' => 'required|flag',
        ]);
        app(\Persimmon\Creator\TagsCreator::class)->update($this, $request);
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
        $data = Tags::find($id);
        return response()->json($data);
    }


    /**
     * Remove the specified from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (empty($request->ids)) {
            return response()->json(['status' => 'error', 'info' => 'ID不能为空']);
        }
        $result = Tags::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => !$result ? 'error' : 'success']);
    }

    /**
     * Get like tags with ajax
     * @param $keyword
     * @return \Illuminate\Http\JsonResponse
     */
    public function query($keyword)
    {
        $data = Tags::where('tags_name', 'like', '%' . $keyword . '%')->get();
        return response()->json($data);
    }

    /**
     * data transform format
     *
     * @param $data
     * @return mixed
     */
    private function transform($data)
    {
        return $data;
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