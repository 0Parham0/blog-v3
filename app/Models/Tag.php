<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    use HasFactory;

    public static function create($tagName)
    {
        return DB::table('tags')->insertGetId([
            'name' => $tagName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function readByName($tagName)
    {
        return DB::table('tags')->where('name', $tagName)->pluck('id')->first();
    }

    public static function deleteByIds($idsArray)
    {
        DB::table('tags')->whereIn('id', $idsArray)->delete();
    }
}
