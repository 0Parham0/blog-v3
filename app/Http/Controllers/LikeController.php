<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use ApiResponses;

    public function likeOrUnlikeBlog(Request $request, $id) #todo.ma: check for id exists for blog
    {
        if (Like::isLiked($request->user()->id, $id)) {
            Like::unlike($request->user()->id, $id);

            return $this->ok('unliked');
        } else {
            Like::like($request->user()->id, $id);

            return $this->ok('liked');
        }
    }
}
