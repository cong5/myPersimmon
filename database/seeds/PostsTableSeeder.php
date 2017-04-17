<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('posts')->delete();
        
        \DB::table('posts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'flag' => 'hello-world',
                'title' => '你好 MyPersimmon！',
                'thumb' => NULL,
                'category_id' => 1,
                'user_id' => 1,
                'content' => '<p>你好 MyPersimmon！<br />
MyPersimmon 是一个基于Laravel 5.4 开发的开源博客.<br />
您对MyPersimmon的使用就是对MyPersimmon最好的支持。<br />
如果您在使用过程中遇到什么问题的，或者遇到什么bug的，或者新功能的建议和意见，欢迎到 <a href="https://github.com/Cong5/myPersimmon/issues">https://github.com/Cong5/myPersimmon/issues</a> 来给我提建议。</p>',
                'markdown' => '你好 MyPersimmon！  
MyPersimmon 是一个基于Laravel 5.4 开发的开源博客.  
您对MyPersimmon的使用就是对MyPersimmon最好的支持。  
如果您在使用过程中遇到什么问题的，或者遇到什么bug的，或者新功能的建议和意见，欢迎到 https://github.com/Cong5/myPersimmon/issues 来给我提建议。',
                'views' => 3,
                'comments' => 0,
                'ipaddress' => '127.0.0.1',
                'deleted_at' => NULL,
                'created_at' => '2017-03-31 08:12:58',
                'updated_at' => '2017-03-31 08:36:49',
            ),
            1 => 
            array (
                'id' => 6,
                'flag' => 'mypersimmon-test',
                'title' => 'MyPersimmon Test',
                'thumb' => NULL,
                'category_id' => 1,
                'user_id' => 1,
                'content' => '<p>This is MyPersimmon Test Article.</p>',
                'markdown' => 'This is MyPersimmon Test Article.',
                'views' => 2,
                'comments' => 0,
                'ipaddress' => '127.0.0.1',
                'deleted_at' => NULL,
                'created_at' => '2017-03-31 08:27:33',
                'updated_at' => '2017-03-31 08:27:52',
            ),
        ));
        
        
    }
}