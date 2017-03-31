<?php

namespace Persimmon\Creator;


use Illuminate\Http\Request;
use Models\Links;
use Naux\AutoCorrect;
use Persimmon\Interfaces\CreatorInterface;

class LinksCreator
{

    public function create(CreatorInterface $observer, Request $request)
    {
        $links = new Links();
        $links = $this->transform($links, $request);

        if (!$links) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($links);
    }

    public function update(CreatorInterface $observer, Request $request)
    {
        if (empty($request->id)) {
            $observer->creatorFail('ID 不能为空');
        }
        $links = Links::firstOrCreate(['name' => $request->name]);
        $links = $this->transform($links, $request);

        if (!$links) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($links);
    }

    public function transform(Links $links, Request $request)
    {
        $links->name = (new AutoCorrect())->convert($request->name);
        $links->url = strtolower($request->url);
        $links->save();
        return $links;
    }

}