<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    private static function read()
    {
        return DB::table('blogs')
            ->select(
                'blogs.id',
                'blogs.title',
                'blogs.description',
                'users.name',
                DB::raw('IFNULL(CONCAT("[", GROUP_CONCAT(tags.name SEPARATOR ", "), "]"), "[]") as tags'),
                DB::raw('(SELECT COUNT(*) FROM likes WHERE likes.blog_id = blogs.id) as likes_count')
            )
            ->leftJoin('users', 'blogs.user_id', '=', 'users.id')
            ->leftJoin('blog_tags', 'blogs.id', '=', 'blog_tags.blog_id')
            ->leftJoin('tags', 'blog_tags.tag_id', '=', 'tags.id')
            ->leftJoin('likes', 'blogs.id', '=', 'likes.blog_id')
            ->groupBy('blogs.id', 'blogs.title', 'blogs.description', 'users.name');
    }

    public static function readAll()
    {
        return self::read()
            ->get();
    }

    public static function readByUserId($userId)
    {
        return self::read()
            ->where('blogs.user_id', '=', $userId)
            ->get();
    }

    public static function create($title, $description, $userId)
    {
        return DB::table('blogs')->insertGetId([
            'title' => $title,
            'description' => $description,
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function searchBetweenAField($between, $value)
    {
        return self::read()
            ->where($between, 'LIKE', '%' . $value . '%')
            ->get();
    }

    public static function searchBetweenAll($betweenArray, $value)
    {
        return self::read()
            ->whereAny($betweenArray, 'LIKE', '%' . $value . '%')
            ->get();
    }

    public static function isExistByUserIdAndBlogId($userId, $blogId)
    {
        return DB::table('blogs')->select('id')
            ->where('user_id', $userId)->where('id', $blogId)
            ->exists();
    }

    public static function deleteById($blogId)
    {
        DB::table('blogs')->where('id', $blogId)->delete();
    }

    public static function readByBlogId($blogId) #todo.ma: duplicate query
    {
        return DB::table('blogs')
            ->select(
                'blogs.id',
                'blogs.title',
                'blogs.description',
                DB::raw('IFNULL(CONCAT("[", GROUP_CONCAT(tags.name SEPARATOR ", "), "]"), "[]") as tags')
            )
            ->leftJoin('blog_tags', 'blogs.id', '=', 'blog_tags.blog_id')
            ->leftJoin('tags', 'blog_tags.tag_id', '=', 'tags.id')
            ->groupBy('blogs.id', 'blogs.title', 'blogs.description')
            ->where('blogs.id', $blogId)
            ->get();
    }

    public static function updateByBlogId($blogId, $title, $description)
    {
        DB::table('blogs')->where('id', $blogId)
            ->update(['title' => $title, 'description' => $description, 'updated_at' => now()]);
    }
}
