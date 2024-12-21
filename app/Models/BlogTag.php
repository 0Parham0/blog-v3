<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogTag extends Model
{
    use HasFactory;

    public static function create($tagAndBlogIdsArray)
    {
        foreach ($tagAndBlogIdsArray as &$entry) {
            $entry['created_at'] = now();
            $entry['updated_at'] = now();
        }

        DB::table('blog_tags')->insert($tagAndBlogIdsArray);
    }

    public static function readByBlogId($blogId)
    {
        return DB::table('blog_tags')->where('blog_id', $blogId)->select('tag_id')->get();
    }

    public static function deleteByBlogId($blogId)
    {
        DB::table('blog_tags')->where('blog_id', $blogId)->delete();
    }

    public static function readByTagId($tagIdsArray)
    {
        return DB::table('blog_tags')->whereIn('tag_id', $tagIdsArray)->select('tag_id')->get();
    }
}
