<?php

namespace Persimmon\Interfaces;


interface CreatorInterface
{
    public function creatorFail($error);
    public function creatorSuccess($model);
}