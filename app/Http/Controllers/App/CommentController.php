<?php
/**
 * Created by PhpStorm.
 * User: MrCong
 * Date: 2017/3/6
 * Time: 21:53
 */

namespace App\Http\Controllers\App;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Models\Comment;
use Persimmon\Interfaces\CreatorInterface;

class CommentController extends Controller implements CreatorInterface
{

    protected $response;

    /**
     * 获取评论内容
     * @param $post_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($post_id)
    {
        $lists = Comment::where('posts_id', $post_id)->select('id', 'name', 'email', 'url', 'content')->get();
        foreach ($lists as $key => &$comment) {
            $comment['md5'] = md5($comment['email']);
            $comment['content'] = preg_replace('/@[^\s]+\s?/', "<a href='javascript:void(0);'>\${0}</a>", $comment['content']);
            unset($comment['email']);
        }
        return response()->json($lists);
    }

    /**
     * 发布评论
     * @param $post_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        app(\Persimmon\Creator\CommentCreator::class)->create($this, $request);
        return response()->json($this->response);
    }


    /**
     * 观察者方法，操作失败时候回调
     * @param $error
     */
    public function creatorFail($error)
    {
        $this->response = ['status' => 'error', 'id' => '', 'info' => $error];
    }

    /**
     * 观察者方法，操作成功时候回调
     * @param $model
     */
    public function creatorSuccess($model)
    {
        $this->response = ['status' => 'success', 'id' => $model->id, 'info' => '评论发布成功'];
    }

}