<?php
/**
 * Created by PhpStorm.
 * User: MrCong
 * Date: 2017/4/15
 * Time: 16:27
 */

namespace Persimmon\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ServerChanSend
{

    protected $baseUri = 'http://sc.ftqq.com/%s.send';
    protected $serverChanKey;

    public function __construct()
    {
        $this->serverChanKey = config('services.serverchan');
    }

    /**
     * 拼接错误信息
     * @param \Exception $exception
     * @return mixed
     */
    public function send(\Exception $exception)
    {
        if(empty($this->serverChanKey)){
            return '';
        }
        $description = sprintf("Code File : %s    \nError Line : %s    \nError Message : ***%s***", $exception->getFile(), $exception->getLine(), $exception->getMessage());
        return $this->post($description);
    }

    /**
     * 执行POST CURL 发送
     * @param string $description
     * @return mixed
     */
    public function post($description = '')
    {
        $apiUri = sprintf($this->baseUri, $this->serverChanKey);
        $client = new Client();
        $response = $client->request('POST', $apiUri, [
            'headers' => [
                'Content-type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'text' => 'Laravel错误',
                'desp' => $description
            ]
        ]);
        if ($response->getStatusCode() != 200) {
            Log::error('MyPersimmon系统错误信息推送失败.');
        }
        return json_decode($response->getBody()->getContents(), true);
    }

}