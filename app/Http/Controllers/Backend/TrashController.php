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
use Models\Posts;
use Models\PostsTags;

class TrashController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rows = intval($request->rows) > 0 ? $request->rows : 20;
        $listData = Posts::onlyTrashed()->OfCategory($request->category_id)->OfTitle($request->q)->paginate($rows);
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
        $this->validate($request, [
            'ids' => 'required'
        ]);
        $result = Posts::withTrashed()->whereIn('id', $request->ids)->restore();
        return response()->json(['status' => !$result ? 'error' : 'success']);
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
        $result = Posts::whereIn('id', $request->ids)->forceDelete();
        PostsTags::whereIn('posts_id', $request->ids)->delete();
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

}