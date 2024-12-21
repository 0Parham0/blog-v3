<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\BlogTag;
use App\Models\Tag;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use ApiResponses;

    public function getAllBlogs()
    {
        $blogs = Blog::readAll();

        return $this->ok('Successful', $blogs);
    }

    public function getUserBlogs(Request $request)
    {
        $blogs = Blog::readByUserId($request->user()->id);

        return $this->ok('Successful', $blogs);
    }

    public function createABlog(BlogRequest $blogRequest)
    {
        $blogId = Blog::create($blogRequest->title, $blogRequest->description, $blogRequest->user()->id);

        self::createBlogTags($blogRequest->tags, $blogId); #todo.ma: error when don't send tags field in post body

        return $this->ok('Blog created.');
    }

    public function deleteABlog(Request $request, $id)
    {
        $userId = $request->user()->id;

        if (!(Blog::isExistByUserIdAndBlogId($userId, $id))) {
            return $this->error('do not have permission or the blog id does not exist.', 403);
        } else {
            self::deleteTagsAndItsRelations($id);

            Blog::deleteById($request->id);
            return $this->ok('blog deleted.');
        }
    }

    public function getABlog(Request $request, $id)
    {
        $userId = $request->user()->id;

        if (!(Blog::isExistByUserIdAndBlogId($userId, $id))) {
            return $this->error('do not have permission or the blog id does not exist.', 403);
        } else {
            return $this->ok(
                'Blog fields to edit (except the id)',
                Blog::readByBlogId($id)
            );
        }
    }

    public function editABlog(BlogRequest $blogRequest, $id)
    {
        $userId = $blogRequest->user()->id;

        if (!(Blog::isExistByUserIdAndBlogId($userId, $id))) {
            return $this->error('Do not have permission or the blog id does not exist.', 403);
        } else {
            Blog::updateByBlogId($id, $blogRequest->title, $blogRequest->description);
            self::deleteTagsAndItsRelations($id);
            self::createBlogTags($blogRequest->tags, $id);

            return $this->ok('Blog edited successfully');
        }
    }

    private function deleteTagsAndItsRelations($blogId) #todo.ma: single responsibility violation
    {
        $tagIdsObject = BlogTag::readByBlogId($blogId);
        $tagIds = [];
        foreach ($tagIdsObject as $item) {
            $tagIds[] = $item->tag_id; #todo.ma: just use pluck
        }
        BlogTag::deleteByBlogId($blogId);

        $shouldRemainTagsObject = BlogTag::readByTagId($tagIds);
        $shouldRemainTags = [];
        foreach ($shouldRemainTagsObject as $item) {
            $shouldRemainTags[] = $item->tag_id;
        }
        $shouldDeleteTags = array_diff($tagIds, $shouldRemainTags);
        Tag::deleteByIds($shouldDeleteTags); #todo.ma: why delete unused tags
    }

    private function createBlogTags($tags, $blogId) #todo.ma: this method should not be here and where is input and return types
    {
        $tagsArray = array_map('trim', $tags);
        $blogTags = [];
        foreach ($tagsArray as $tag) {
            if (!($tagId = Tag::readByName($tag))) { #todo.ma: unreadable if
                $tagId = Tag::create($tag);
            }
            if (!in_array(['tag_id' => $tagId, 'blog_id' => $blogId], $blogTags)) {
                array_push($blogTags, ['tag_id' => $tagId, 'blog_id' => $blogId]);
            }
        }
        BlogTag::create($blogTags);
    }
}
