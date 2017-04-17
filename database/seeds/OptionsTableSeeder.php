<?php

use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('options')->delete();
        
        \DB::table('options')->insert(array (
            0 => 
            array (
                'id' => 3,
                'option_title' => '网站名称',
                'option_name' => 'site_name',
                'option_value' => 'MyPersimmon',
                'option_group' => '',
                'option_remark' => '',
                'option_status' => 'base',
                'data_type' => 'text',
                'created_at' => '2017-02-16 06:09:21',
                'updated_at' => '2017-03-31 03:29:46',
            ),
            1 => 
            array (
                'id' => 4,
                'option_title' => '网站关键词',
                'option_name' => 'keywords',
                'option_value' => 'MyPersimmon',
                'option_group' => NULL,
                'option_remark' => NULL,
                'option_status' => 'base',
                'data_type' => 'textarea',
                'created_at' => '2017-02-16 06:09:39',
                'updated_at' => '2017-03-31 03:29:46',
            ),
            2 => 
            array (
                'id' => 5,
                'option_title' => '网站描述',
                'option_name' => 'description',
                'option_value' => 'MyPersimmon 是一个基于Laravel开发的开源博客',
                'option_group' => '',
                'option_remark' => '',
                'option_status' => 'base',
                'data_type' => 'textarea',
                'created_at' => '2017-02-16 06:10:00',
                'updated_at' => '2017-03-31 03:29:46',
            ),
            3 => 
            array (
                'id' => 6,
                'option_title' => '导航配置',
                'option_name' => 'navigations',
                'option_value' => '[{"name":"MySQL","url":"\\/category\\/mysql","sorting":0},{"name":"PHP","url":"\\/category\\/php","sorting":0}]',
                'option_group' => '',
                'option_remark' => '',
                'option_status' => 'hidden',
                'data_type' => 'textarea',
                'created_at' => '2017-02-16 14:36:34',
                'updated_at' => '2017-03-31 03:28:55',
            ),
            4 => 
            array (
                'id' => 7,
                'option_title' => '新浪微博',
                'option_name' => 'weibo',
                'option_value' => 'https://weibo.com/',
                'option_group' => '',
                'option_remark' => '',
                'option_status' => 'extends',
                'data_type' => 'text',
                'created_at' => '2017-02-20 15:13:04',
                'updated_at' => '2017-03-31 03:30:37',
            ),
            5 => 
            array (
                'id' => 8,
                'option_title' => 'GitHub 地址',
                'option_name' => 'github',
                'option_value' => 'https://github.com/',
                'option_group' => NULL,
                'option_remark' => NULL,
                'option_status' => 'extends',
                'data_type' => 'text',
                'created_at' => '2017-02-20 15:13:31',
                'updated_at' => '2017-03-31 03:30:37',
            ),
            6 => 
            array (
                'id' => 9,
                'option_title' => 'Google Plus',
                'option_name' => 'google_plus',
                'option_value' => 'https://plus.google.com',
                'option_group' => '',
                'option_remark' => '',
                'option_status' => 'extends',
                'data_type' => 'text',
                'created_at' => '2017-02-20 15:14:14',
                'updated_at' => '2017-03-31 03:30:37',
            ),
            7 => 
            array (
                'id' => 10,
                'option_title' => '统计代码',
                'option_name' => 'analysis',
                'option_value' => NULL,
                'option_group' => '',
                'option_remark' => '',
                'option_status' => 'extends',
                'data_type' => 'textarea',
                'created_at' => '2017-02-20 15:23:13',
                'updated_at' => '2017-03-31 03:29:46',
            ),
            8 => 
            array (
                'id' => 11,
                'option_title' => '备案号',
                'option_name' => 'icp',
                'option_value' => NULL,
                'option_group' => '',
                'option_remark' => '',
                'option_status' => 'extends',
                'data_type' => 'text',
                'created_at' => '2017-02-20 15:30:00',
                'updated_at' => '2017-03-31 03:29:46',
            ),
            9 => 
            array (
                'id' => 12,
                'option_title' => 'Wunderlist授权Token',
                'option_name' => 'wunderlist_access_token',
                'option_value' => '',
                'option_group' => NULL,
                'option_remark' => NULL,
                'option_status' => 'hidden',
                'data_type' => 'text',
                'created_at' => '2017-02-24 13:08:07',
                'updated_at' => '2017-02-24 08:24:06',
            ),
        ));
        
        
    }
}