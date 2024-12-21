<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Http\Requests\SearchRequest;
use App\Traits\ApiResponses;

class SearchController extends Controller
{
    use ApiResponses;

    public function search(SearchRequest $searchRequest)
    {
        if ($searchRequest->between == 'all') {
            return $this->ok(
                'searched',
                Blog::searchBetweenAll(['users.name', 'title', 'description'], $searchRequest->value)
            );
        } else {
            if ($searchRequest->between == 'name') {
                $searchRequest->between = 'users.name';
            }
            return $this->ok(
                'searched',
                Blog::searchBetweenAField($searchRequest->between, $searchRequest->value)
            );
        }
    }
}
