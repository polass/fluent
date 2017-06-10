<?php

namespace Polass\Tests\Stubs;

use Polass\Fluent\Model;

class ModelWithMutators extends Model
{
    public function setFooAttribute($value)
    {
        $this->attributes['foo'] = sprintf("mutated `%s`", (string)$value);
    }

    public function getFooAttribute($value)
    {
        return sprintf("mutated `%s`", (string)$value);
    }

    public function hasFooAttribute($value)
    {
        return true;
    }
}
