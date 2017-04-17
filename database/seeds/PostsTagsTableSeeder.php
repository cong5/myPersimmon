<?php

use Illuminate\Database\Seeder;

class PostsTagsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('posts_tags')->delete();
        
        \DB::table('posts_tags')->insert(array (
            0 => 
            array (
                'posts_id' => 1,
                'tags_id' => 1,
                'created_at' => '2017-03-31 08:20:23',
                'updated_at' => '2017-03-31 08:20:23',
            ),
        ));
        
        
    }
}