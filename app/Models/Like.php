<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    public static function isLiked($userId, $blogId)
    {
        return DB::table('likes')->where('blog_id', $blogId)->where('user_id', $userId)
            ->exists();
    }

    public static function unlike($userId, $blogId)
    {
        Db::table('likes')->where('blog_id', $blogId)->where('user_id', $userId)
            ->delete();
    }

    public static function like($userId, $blogId)
    {
        DB::table('likes')->insert([
            'blog_id' => $blogId,
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
