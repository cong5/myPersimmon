<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tags')->delete();
        
        \DB::table('tags')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tags_name' => 'MyPersimmon',
                'tags_flag' => 'MyPersimmon',
                'created_at' => '2017-03-31 08:12:58',
                'updated_at' => '2017-03-31 08:12:58',
            ),
        ));
        
        
    }
}