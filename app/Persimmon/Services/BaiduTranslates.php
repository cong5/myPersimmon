<?php

namespace Persimmon\Services;


use GuzzleHttp\Client;

class BaiduTranslates
{

    private $apiUrl = "https://fanyi-api.baidu.com/api/trans/vip/translate?q=%s&from=%s&to=%s&appid=%s&salt=%s&sign=%s";

    public function __construct()
    {
    }

    public function exec($params)
    {
        $client = new Client();
        $originUrl = $this->getUrl($params);
        $response = $client->request('GET', $originUrl);
        if ($response->getStatusCode() != 200) {
            return ['status' => $response->getStatusCode(), 'info' => '翻译接口调用失败，请重试。'];
        }
        $jsonBody = $response->getBody();
        $body = json_decode($jsonBody, true);
        if (isset($body['error_code'])) {
            $data = ['status' => 500, 'trans_result' => self::errorMeaasge($body['error_code'])];
        } else {
            $data = ['status' => 200, 'trans_result' => $body['trans_result'][0]];
        }
        return $data;
    }

    private static function errorMeaasge($code)
    {
        $error = [
            52000 => '成功',
            52001 => '请求超时,重试',
            52002 => '系统错误,重试',
            52003 => '未授权用户,检查您的appid是否正确',
            54000 => '必填参数为空,检查是否少传参数',
            58000 => '客户端IP非法,检查您填写的IP地址是否正确,可修改您填写的服务器IP地址',
            54001 => '签名错误,请检查您的签名生成方法',
            54003 => '访问频率受限,请降低您的调用频率',
            58001 => '译文语言方向不支持,检查译文语言是否在语言列表里',
            54004 => '账户余额不足,前往管理控制台为账户充值',
            54005 => '长query请求频繁,请降低长query的发送频率，3s后再试',
        ];
        return !empty($error[$code]) ? $error[$code] : '未知错误';
    }

    private function getUrl($params)
    {
        $salt = time();
        $ak = config('services.baidu_translate.ak');
        $sk = config('services.baidu_translate.sk');
        $words = $this->filter_mark(isset($params['q']) ? $params['q'] : '');
        $from = isset($params['from']) && !empty($params['from']) ? $params['from'] : 'zh';
        $to = isset($params['to']) && !empty($params['to']) ? $params['to'] : 'en';
        $sign = md5(mb_convert_encoding($ak . $words . $salt . $sk, "UTF-8"));
        $url = sprintf($this->apiUrl, urlencode($words), $from, $to, $ak, $salt, $sign);
        return $url;
    }

    private function filter_mark($text)
    {
        if (trim($text) == '') return '';
        $keyword = urlencode($text);//将关键字编码
        $keyword = preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99)+/", '', $keyword);
        $text = urldecode($keyword);//将过滤后的关键字解码
        return trim($text);
    }

}