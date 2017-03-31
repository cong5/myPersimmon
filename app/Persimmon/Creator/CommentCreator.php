<?php

namespace Persimmon\Creator;


use Illuminate\Http\Request;
use Models\Comment;
use Models\Tags;
use Naux\AutoCorrect;
use Persimmon\Interfaces\CreatorInterface;

class CommentCreator
{

    public function create(CreatorInterface $observer, Request $request)
    {
        $comment = new Comment();
        $comment = $this->transform($comment, $request);

        if (!$comment) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($comment);
    }

    public function update(CreatorInterface $observer, Request $request)
    {
        if (empty($request->id)) {
            $observer->creatorFail('ID 不能为空');
        }
        $comment = Comment::firstOrCreate(['id' => $request->id]);
        $comment = $this->transform($comment, $request);

        if (!$comment) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($comment);
    }

    public function transform(Comment $comment, Request $request)
    {
        $comment_text = strip_tags($request->markdown);
        $comment->posts_id = $request->posts_id;
        $comment->name = preg_replace('/\s/','_',$request->name);
        $comment->email = $request->email;
        $comment->url = $request->url;
        $comment->content = (new \Parsedown())->text($comment_text);
        $comment->markdown = $request->markdown;
        $comment->save();
        return $comment;
    }

}