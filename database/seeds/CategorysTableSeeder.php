<?php

use Illuminate\Database\Seeder;

class CategorysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categorys')->delete();
        
        \DB::table('categorys')->insert(array (
            0 => 
            array (
                'id' => 1,
                'category_name' => 'PHP',
                'category_parent' => 0,
                'category_flag' => 'php',
                'category_description' => '',
                'ipaddress' => '127.0.0.1',
                'created_at' => '2017-03-31 03:26:06',
                'updated_at' => '2017-03-31 03:26:06',
            ),
            1 => 
            array (
                'id' => 2,
                'category_name' => 'Laravel',
                'category_parent' => 0,
                'category_flag' => 'laravel',
                'category_description' => '',
                'ipaddress' => '127.0.0.1',
                'created_at' => '2017-03-31 03:26:15',
                'updated_at' => '2017-03-31 03:26:15',
            ),
            2 => 
            array (
                'id' => 3,
                'category_name' => 'Linux',
                'category_parent' => 0,
                'category_flag' => 'linux',
                'category_description' => '',
                'ipaddress' => '127.0.0.1',
                'created_at' => '2017-03-31 03:26:23',
                'updated_at' => '2017-03-31 03:26:23',
            ),
            3 => 
            array (
                'id' => 4,
                'category_name' => 'MySQL',
                'category_parent' => 0,
                'category_flag' => 'database',
                'category_description' => '',
                'ipaddress' => '127.0.0.1',
                'created_at' => '2017-03-31 03:26:33',
                'updated_at' => '2017-03-31 03:26:33',
            ),
        ));
        
        
    }
}