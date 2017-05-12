<?php

namespace App\Http\Controllers\App;


use Illuminate\Support\Facades\Session;
use Models\Links;
use Persimmon\Interfaces\CreatorInterface;
use App\Http\Controllers\Controller;
use Persimmon\Services\SiteMap;
use Persimmon\Services\RssFeed;
use Models\Categorys;
use Models\Posts;
use Models\Tags;

class HomeController extends Controller implements CreatorInterface
{

    protected $response;

    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Posts::orderBy('id', 'desc')->paginate(15);

        return view('app.home')->with(compact('posts'));
    }

    public function posts($flag)
    {
        $post = app(\Persimmon\Services\RedisCache::class)->cachePost($flag);

        !empty($post) ?: abort(404, '很抱歉，页面找不到了。');

        Posts::increment('views', 1);

        return view('app.post')->with(compact('post'));
    }

    /**
     * Tag page
     * @param $tag
     */
    public function tags($tag)
    {

        $tags = Tags::where('tags_name', $tag)->first();

        !empty($tags) ?: abort(404, '很抱歉，页面找不到了。');

        $posts = $tags->posts()->paginate(15);

        return view('app.home')->with(compact('posts', 'tags'));

    }

    /**
     * 分类
     * @param $flag
     * @return $this
     */
    public function category($flag)
    {
        $category = Categorys::where('category_flag', $flag)->first();

        !empty($category) ?: abort(404, '很抱歉，页面找不到了。');

        $posts = $category->posts()->paginate(15);

        return view('app.home')->with(compact('posts', 'category'));
    }

    /**
     * friends links
     * @return $this
     */
    public function friends()
    {
        $links = Links::all();

        return view('app.friends')->with(compact('links'));
    }

    /**
     * Feed 流
     * @return mixed
     */
    public function feed(RssFeed $feed)
    {
        $rss = $feed->getRSS();

        return response($rss)->header('Content-type', 'text/xml; charset=UTF-8');
    }

    /**
     * 站点地图
     * @param SiteMap $siteMap
     * @return mixed
     */
    public function siteMap(SiteMap $siteMap)
    {
        $map = $siteMap->getSiteMap();

        return response($map)->header('Content-type', 'text/xml');
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