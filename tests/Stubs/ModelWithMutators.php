<?php

namespace Polass\Tests\Stubs;

use Polass\Fluent\Model;

class ModelWithMutators extends Model
{
    public $visible;

    public $hidden;

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

    public function getFoobarAttribute()
    {
        return 'FOOBAR';
    }
}
