<?php

namespace Sakurairo\Tests\Container;

require __DIR__.'/../../vendor/autoload.php';

class A
{
    public $count = 100;
}

class B
{
    protected $count = 1;

    public function __construct(A $a)
    {
        $this->count = $a->count;
    }

    public function getCount()
    {
        return $this->count + 10;
    }
}

var_dump(app(B::class)->getCount());