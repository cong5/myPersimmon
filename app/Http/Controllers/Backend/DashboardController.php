<?php
/**
 * Created by PhpStorm.
 * User: MrCong <i@cong5.net>
 * Date: 2017/1/13
 * Time: 16:38
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Models\Comment;
use Models\Posts;
use Persimmon\ThridService\Shanbay;

class DashboardController extends Controller
{

    public function dashboard()
    {
        return view('backend.dashboard');
    }

    public function meta()
    {
        $post = Posts::count();
        $post_trash = Posts::onlyTrashed()->count();
        $comment = Comment::count();
        $recent_posts = Posts::orderBy('created_at')->limit(5)->select('id', 'title', 'created_at')->get();
        $response = [
            'posts' => intval($post),
            'comments' => intval($comment),
            'post_trash' => $post_trash,
            'recent_posts' => $recent_posts
        ];
        return response()->json($response);
    }

}