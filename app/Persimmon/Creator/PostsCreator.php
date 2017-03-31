<?php

/**
 * Created by PhpStorm.
 * User: MrCong <i@cong5.net>
 * Date: 2017/2/11
 * Time: 14:45
 */

namespace Persimmon\Creator;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Models\Posts;
use Models\Tags;
use Naux\AutoCorrect;
use Persimmon\Interfaces\CreatorInterface;
use Persimmon\Services\BaiduPush;

class PostsCreator
{

    protected $_error = 'error';

    public function create(CreatorInterface $observer, Request $request)
    {
        $posts = new Posts;
        $posts = $this->transform($posts, $request);
        if (!$posts) {
            $observer->creatorFail($this->_error);
        }
        Tags::createPostsTags($posts->id, $request->tags);

        //发送百度ping
        (new BaiduPush())->run(route('post',[$posts->flag]));

        $observer->creatorSuccess($posts);
    }

    public function update(CreatorInterface $observer, Request $request)
    {
        if (empty($request->id)) {
            $observer->creatorFail('ID 不能为空');
        }
        $posts = Posts::firstOrCreate(['flag' => $request->flag]);
        Tags::createPostsTags($posts->id, $request->tags);
        $posts = $this->transform($posts, $request);

        if (!$posts) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($posts);
    }

    public function transform(Posts $posts, Request $request)
    {
        $posts->title = (new AutoCorrect())->convert($request->title);
        $posts->flag = strtolower($request->flag);
        $posts->thumb = $request->thumb;
        $posts->category_id = $request->category_id;
        $posts->user_id = Auth::id();
        $posts->content = (new \Parsedown())->text($request->markdown);
        $posts->markdown = $request->markdown;
        $posts->ipaddress = !empty($request->ipaddress) ? $request->ipaddress : $request->ip();
        try {
            $posts->save();
            return $posts;
        } catch (QueryException $exception) {
            if ($exception->errorInfo[1] == 1062) {
                $this->_error = '文章插入失败，flag重复了。';
            }
            return null;
        }
    }

}