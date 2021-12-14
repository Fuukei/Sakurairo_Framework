<?php

namespace Sakurairo\Tests\Container;

use Sakurairo\Container\Container;

require __DIR__.'/../../vendor/autoload.php';

class A
{
    public $count = 100;
}

class B
{
    protected $count = 1;

    public function __construct(A $a, $count)
    {
        $this->count = $a->count + $count;
    }

    public function getCount()
    {
        return $this->count;
    }
}

class C {
    protected $c;

    public function __construct () {
        return 'c';
    }
}

$container = new Container;

// $b = $container->make(B::class, [100]);
// var_dump($b->getCount());

$res = $container->bind('a',function($container){
    return new C;
});
var_dump($res);

