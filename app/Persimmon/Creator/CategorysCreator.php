<?php

namespace Persimmon\Creator;


use Illuminate\Http\Request;
use Models\Categorys;
use Models\Tags;
use Naux\AutoCorrect;
use Persimmon\Interfaces\CreatorInterface;

class CategorysCreator
{

    public function create(CreatorInterface $observer, Request $request)
    {
        $category = new Categorys;
        $category = $this->transform($category, $request)->save();
        if (!$category) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($category);
    }

    public function update(CreatorInterface $observer, Request $request)
    {
        if (empty($request->id)) {
            $observer->creatorFail('ID 不能为空');
        }
        $category = Categorys::firstOrCreate(['category_flag' => $request->category_flag]);
        $category = $this->transform($category, $request);
        if (!$category) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($category);
    }

    public function transform(Categorys $categorys, Request $request)
    {
        $correct = new AutoCorrect();
        $categorys->category_name = $correct->convert($request->category_name);
        $categorys->category_flag = $request->category_flag;
        $categorys->category_description = $correct->convert($request->category_description);
        $categorys->category_parent = intval($request->category_parent);
        $categorys->ipaddress = $request->ip();
        $categorys->save();
        return $categorys;
    }


}