<?php
/**
 * Created by PhpStorm.
 * User: MrCong <i@cong5.net>
 * Date: 2017/3/8
 * Time: 17:27
 */

namespace Persimmon\Services;


use GuzzleHttp\Client;

class BaiduPush
{

    protected $error;

    public function run($urls)
    {
        $pushUrls = is_array($urls) ? $urls : (array)$urls;
        $site = config('services.baidu_ping.site');
        $token = config('services.baidu_ping.token');
        if(empty($token)){
            return '';
        }
        $api = sprintf("http://data.zz.baidu.com/urls?site=%s&token=%s", $site, $token);
        $data = implode("\n", $pushUrls);
        $client = new Client();
        $response = $client->request('POST', $api, [
            'body' => $data
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        if (isset($result['success'])) {
            return true;
        } else {
            $this->error = isset($result['message']) ? $result['message'] : '未知错误';
            return false;
        }
    }

    public function getError()
    {
        return $this->error;
    }

}