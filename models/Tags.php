<?php
/**
 * Created by PhpStorm.
 * User: MrCong <i@cong5.net>
 * Date: 2017/2/11
 * Time: 15:45
 */

namespace Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tags extends Model
{

    protected $fillable = ['tags_name', 'tags_flag'];

    public static function createPostsTags($post_id, $tags)
    {
        $posts_tags = [];
        foreach ($tags as $k => $tag) {
            $tagsInfo = self::firstOrCreate(['tags_name' => $tag]);
            $tagsInfo->tags_name = $tag;
            $tagsInfo->tags_flag = urlencode($tag);
            $tagsInfo->save();
            $posts_tags[$k] = [
                'posts_id' => $post_id,
                'tags_id' => $tagsInfo->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        PostsTags::where('posts_id', $post_id)->delete();
        DB::table('posts_tags')->insert($posts_tags);
    }

    public function posts()
    {
        return $this->belongsToMany(Posts::class);
    }

}