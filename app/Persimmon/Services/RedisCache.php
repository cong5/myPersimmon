<?php

namespace Persimmon\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Models\Options;
use Models\Posts;

class RedisCache {

    protected $expiresAt = 0;

    public function __construct()
    {
        $this->expiresAt = Carbon::now()->addMinutes(1440);
    }

    /**
     * 更新配置信息
     */
    public function updateSetting()
    {
        cache()->forget('options');
        $optionsList = Options::orderBy('id')->select('option_name', 'option_value')->get()->toArray();
        $options = [];
        foreach ($optionsList as $key => $option) {
            $options[strtolower($option['option_name'])] = $option['option_value'];
        }
        cache(['options' => $options], $this->expiresAt);
    }

    /**
     * 文章缓存
     * @param $flag
     * @return mixed
     */
    public function cachePost($flag)
    {
        $key = hash('sha256', $flag);
        $post = cache($key);
        if (empty($post)) {
            $post = Posts::OfType('post')->where('flag', $flag)->first();
            if(!empty($post)){
                $post->categories;
                $post->tags;
                $post->user;
                cache([$key => $post], $this->expiresAt);
            }
        }
        return $post;
    }

    /**
     * 更新文章缓存
     * @param $flag
     */
    public function updatePost($flag)
    {
        $key = hash('sha256', $flag);
        cache()->forget($key);
        $post = Posts::OfType('post')->where('flag', $flag)->first();
        $post->categories;
        $post->tags;
        $post->user;
        cache([$key => $post], $this->expiresAt);
    }

}