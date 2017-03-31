<?php

namespace Persimmon\Creator;


use Illuminate\Http\Request;
use Models\Tags;
use Naux\AutoCorrect;
use Persimmon\Interfaces\CreatorInterface;

class TagsCreator
{

    public function create(CreatorInterface $observer, Request $request)
    {
        $tags = new Tags;
        $tags = $this->transform($tags, $request);

        if (!$tags) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($tags);
    }

    public function update(CreatorInterface $observer, Request $request)
    {
        if (empty($request->id)) {
            $observer->creatorFail('ID 不能为空');
        }
        $tags = Tags::firstOrCreate(['tags_name' => $request->tags_name]);
        $tags = $this->transform($tags, $request);

        if (!$tags) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($tags);
    }

    public function transform(Tags $tags, Request $request)
    {
        $tags->tags_name = (new AutoCorrect())->convert($request->tags_name);
        $tags->tags_flag = urlencode(strtolower($request->tags_flag));
        $tags->save();
        return $tags;
    }

}