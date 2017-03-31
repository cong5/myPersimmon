<?php

namespace Persimmon\Creator;


use Illuminate\Http\Request;
use Models\Options;
use Naux\AutoCorrect;
use Persimmon\Interfaces\CreatorInterface;

class OptionsCreator
{

    public function create(CreatorInterface $observer, Request $request)
    {
        $options = new Options;
        $options = $this->transform($options, $request);

        if (!$options) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($options);
    }


    public function update(CreatorInterface $observer, Request $request)
    {
        if (empty($request->id)) {
            $observer->creatorFail('ID 不能为空');
        }
        $options = Options::firstOrCreate(['option_name' => $request->option_name]);
        $options = $this->transform($options, $request);

        if (!$options) {
            $observer->creatorFail('error');
        }

        $observer->creatorSuccess($options);
    }

    public function transform(Options $options, Request $request)
    {
        $autoCorrect = new AutoCorrect();
        $options->option_title = $autoCorrect->convert($request->option_title);
        if($options->option_status == 'base'){

        }
        $options->option_name = $request->option_name;
        $options->option_value = $autoCorrect->convert($request->option_value);
        $options->option_group = $request->option_group;
        $options->option_remark = $request->option_remark;
        $options->save();
        return $options;
    }

}