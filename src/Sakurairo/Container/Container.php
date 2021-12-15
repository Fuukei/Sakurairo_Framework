<?php

namespace Sakurairo\Container;

use Illuminate\Container\Container as BaseContainer;

class Container extends BaseContainer
{
    public function __construct()
    {
        static::setInstance($this);
    }
}
