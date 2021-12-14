<?php

namespace Sakurairo\Container;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * 容器中存储依赖的数组
     * 存储的是闭包，运行闭包会返回对应的依赖实例
     *
     * @var array[]
     */
    protected $bindings = [];

    /**
     * 绑定的方法
     *
     * @var \Closure[]
     */
    protected $methodBindings = [];

    /**
     * 依赖别名
     *
     * @var string[]
     */
    protected $aliases = [];

    /**
     * 已创建的单例实例
     *
     * @var array
     */
    protected $instances = [];

    /**
     * 绑定
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @param  bool  $shared
     * @return void
     *
     * @throws \TypeError
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        //去除原有注册
        $this->dropStaleInstances($abstract);

        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        
        //加装闭包
        if ($concrete instanceof Closure) {
            $this->bindings[$abstract] = $concrete;
        } else {
            $this->instances[$abstract] = $concrete;
        }
        //注册
        //回调

    }

    /**
     * 单例绑定
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @return void
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * 实例化对象
     * 
     * @param  string|callable  $abstract
     * @param  array  $parameters
     * @return mixed
     * 
     */
    public function make($abstract, array $parameters = []): mixed
    {
        try {
            $reflector = new ReflectionClass($abstract);
        } catch (ReflectionException $e) {
            throw new ReflectionException($e);
        }
        $constructor = $reflector->getConstructor();

        $args = [];
        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $class = $param->getType();
                assert($class instanceof ReflectionNamedType);
                if ($class) {
                    $args[] = $this->make($class->getName());
                }
            }
        }

        $args = array_merge($args, $parameters);

        return $reflector->newInstanceArgs($args);
    }

    /**
     * 清除绑定
     * 
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @return void
     */
    protected function dropStaleInstances($abstract)
    {
        unset($this->instances[$abstract], $this->aliases[$abstract]);
    }

    public function get(string $id)
    {
        # code...
    }

    public function has(string $id): bool
    {
        return false;
    }
}
