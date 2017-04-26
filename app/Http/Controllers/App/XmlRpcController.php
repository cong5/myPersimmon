<?php
/**
 * Created by PhpStorm.
 * User: MrCong <i@cong5.net>
 * Date: 2017/2/24
 * Time: 18:19
 */

namespace App\Http\Controllers\App;


use Persimmon\Interfaces\CreatorInterface;
use Persimmon\Interfaces\XmlRpcInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Persimmon\Storage\QiniuUploads;
use Persimmon\Services\XmlRpc;
use Illuminate\Http\Request;
use Models\Categorys;
use Models\Options;
use Carbon\Carbon;
use Models\Posts;

class XmlRpcController extends Controller implements CreatorInterface,XmlRpcInterface
{

    private $client_id;

    public function index(Request $request)
    {
        $methods = array(
            'blogger.getUsersBlogs' => 'getUsersBlogs',
            'blogger.deletePost' => 'deletePost',
            'metaWeblog.newPost' => 'newPost',
            'metaWeblog.editPost' => 'editPost',
            'metaWeblog.getPost' => 'getPost',
            'metaWeblog.getCategories' => 'getCategories',
            'metaWeblog.newMediaObject' => 'newMediaObject',
            'metaWeblog.getRecentPosts' => 'getRecentPosts',
            'wp.newCategory' => 'newCategory',
        );

        /**
         * 获取客户端IP
         */
        $this->client_id = $request->getClientIp();

        /**
         * 消息处理，具体函数细节，可参看
         * @link http://php.net/manual/zh/book.xmlrpc.php
         * 接受客户端POST过来的XML数据
         */
        $request = $request->getContent();
        $method = null;
        $response = xmlrpc_decode_request($request, $method);

        /**
         * 如果用户名密码不正确
         */
        list($appkey, $username, $password) = $response;
        if (!$this->authenticate($username, $password)) {
            $response = [
                'faultCode' => '2',
                'faultString' => "Name or password is wrong, if you forget, please contect Administrator"
            ];
            XmlRpc::response($response, 'error');
            exit();
        }

        /**
         * 正常执行
         */
        if (isset($methods[$method])) {
            call_user_func_array([$this, $methods[$method]], [$method, $response]);
        } else {
            $this->methodNotFound($method);
        }
    }

    /**
     * Get Request Show Error Message
     */
    public function errorMessage()
    {
        return response('XML-RPC server accepts POST requests only.');
    }

    /**
     * Observer creator Fail
     * @param $error
     */
    public function creatorFail($error)
    {
        $response = [
            'faultCode' => '2',
            'faultString' => $error
        ];
        XmlRpc::response($response, 'error');
    }

    /**
     * Observer creator Success
     * @param $model
     */
    public function creatorSuccess($model)
    {
        XmlRpc::response($model->id);
    }

    /**
     * authenticate
     * @param $email
     * @param $password
     * @return bool
     */
    public function authenticate($email, $password)
    {
        if (Auth::check() || Auth::attempt(['email' => $email, 'password' => $password])) {
            return true;
        }

        $errorNumbers = 5;
        /**
         * 如果登陆次数大于10次
         * 而且最后错误登陆时间超过10分钟，已经过期，则清零
         */
        $options = Options::firstOrCreate(['option_name' => 'xmlrpc_login_failed']);
        $expiresAt = Carbon::createFromTimestamp(strtotime($options->updated_at))->addMinutes(10);
        if ($options->option_value >= $errorNumbers && $expiresAt->getTimestamp() < Carbon::now()->getTimestamp()) {
            $options->option_value = 0;
            $options->save();
        }

        /**
         * 如果登陆次数大于10次
         * 而且最后错误登陆时间超过10分钟，已经过期，则清零
         * 则返回错误提示
         */
        if ($options->option_value >= $errorNumbers) {
            $response = [
                'faultCode' => '2',
                'faultString' => "Login of failure, please try again after 10 minutes."
            ];
            XmlRpc::response($response, 'error');
        } else {
            $options->option_value++;
            $options->save();
        }
        unset($options);
        return false;
    }

    /**
     * Get Blogs Info
     * @param $method
     * @param $params
     */
    public function getUsersBlogs($method, $params)
    {
        $response[0] = [
            'url' => url('/'),
            'blogid' => !empty($appkey) ? $appkey : '1',
            'blogName' => "Mr.Cong"
        ];
        XmlRpc::response($response);
    }

    /**
     * edit Post
     * @param $method
     * @param $params
     */
    public function editPost($method, $params)
    {
        list($post_id, $username, $password, $struct, $publish) = $params;
        $request = $this->transform($struct);
        app(\Persimmon\Creator\PostsCreator::class)->update($this, $request);
    }

    /**
     * Get Categories
     * @param $method
     * @param $params
     */
    public function getCategories($method, $params)
    {
        $category = Categorys::all(array('id as categoryid', 'category_name as title', 'category_name as description', 'category_flag as slug'))->toArray();
        XmlRpc::response($category);
    }

    /**
     * Get Post
     * @param $method
     * @param $params
     */
    public function getPost($method, $params)
    {
        list($post_id, $username, $password) = $params;
        $data = [];
        $post = Posts::where('id', $post_id)->select('id', 'id as postid', 'title', 'category_id', 'markdown as description', 'user_id as userid', 'flag as wp_slug', 'created_at as dateCreated')->first();
        $data = $post->toArray();
        $tags = $post->tags->toArray();
        $data['categories'] = $post->categories->category_name;
        $data['link'] = route('posts', [$post->wp_slug]);
        $tags = array_map(function ($item) {
            return $item['tags_name'];
        }, $tags);
        $data['mt_keywords'] = implode(',', $tags);
        unset($post, $tags);
        XmlRpc::response($data);
    }

    /**
     * Get Recent Posts
     * @param $method
     * @param $params
     */
    public function getRecentPosts($method, $params)
    {
        list($blogid, $username, $password, $numberOfPosts) = $params;
        $posts = Posts::orderBy('id', 'desc')->select('id', 'id as postid', 'title', 'category_id', 'markdown as description', 'user_id as userid', 'flag as wp_slug', 'created_at as dateCreated')->paginate($numberOfPosts);
        $data = [];
        foreach ($posts as $key => $post) {
            $data[$key] = $post->toArray();
            $tags = $post->tags->toArray();
            $data[$key]['categories'] = $post->categories->category_name;
            $data[$key]['link'] = route('posts', [$post->wp_slug]);

            $tags = array_map(function ($item) {
                return $item['tags_name'];
            }, $tags);
            $data[$key]['mt_keywords'] = implode(',', $tags);
            unset($post, $tags);
        }
        XmlRpc::response($data);
    }

    /**
     * Upload new Media Object
     * @param $method
     * @param $params
     */
    public function newMediaObject($method, $params)
    {
        list($blogid, $username, $password, $struct) = $params;
        //开始上传
        $qiniu = new QiniuUploads();
        $url = $qiniu->putForContent($struct['bits']);
        XmlRpc::response(['url' => $url]);
    }

    /**
     * Create new Post
     * @param $method
     * @param $params
     */
    public function newPost($method, $params)
    {
        list($blogid, $username, $password, $struct, $publish) = $params;
        $request = $this->transform($struct);
        app(\Persimmon\Creator\PostsCreator::class)->create($this, $request);
    }

    /**
     * Create new Category
     * @param $method
     * @param $params
     */
    public function newCategory($method, $params)
    {
        list($blog_id, $username, $password, $category) = $params;
        $categorys = Categorys::firstOrCreate(['category_name' => $category]);
        XmlRpc::response(intval($categorys->id));
    }

    /**
     * delete Post
     * @param $method
     * @param $params
     */
    public function deletePost($method, $params)
    {
        list($appKey, $postid, $username, $password, $publish) = $params;
        $result = Posts::where('id', $postid)->delete();
        XmlRpc::response(intval($result) > 0 ? true : false);
    }

    /**
     * method Not Found
     * @param $methodName
     */
    protected function methodNotFound($methodName)
    {
        $response = [
            'faultCode' => '2',
            'faultString' => "The method you requested, '$methodName', was not found."
        ];
        XmlRpc::response($response, 'error');
    }

    /**
     * transform data
     * @param $struct
     * @return Request
     */
    private function transform($struct)
    {
        $tags = strpos($struct['mt_keywords'], ',') !== false ? explode(',', $struct['mt_keywords']) : $struct['mt_keywords'];
        $category = Categorys::where('category_name', $struct['categories'][0])->select('id')->first();
        $request = new Request();
        $request->title = $struct['title'];
        $request->flag = $struct['wp_slug'];
        $request->thumb = '';
        $request->tags = is_array($struct['mt_keywords']) ? $struct['mt_keywords'] : $tags;
        $request->category_id = $category->id;
        $request->user_id = Auth::id();
        $request->markdown = $struct['description'];
        $request->ipaddress = !empty($this->client_id) ? $this->client_id : '127.0.0.1';
        return $request;
    }
}