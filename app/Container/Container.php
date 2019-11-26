<?php


namespace deli13\Container;

use deli13\Exceptions\ContainerException;
use deli13\Interfaces\ContainerInterface;
use deli13\Interfaces\DependencyInterface;

class Container implements ContainerInterface
{
    private $container_store = [];

    public function get(string $name): DependencyInterface
    {
        if(!$this->has($name)){
            throw new ContainerException("Зависимость не найдена ".$name);
        } else {
            return $this->container_store[$name];
        }
    }

    public function set(string $name, DependencyInterface $dependency)
    {
        if (!$this->has($name)) {
            $this->container_store[$name] = $dependency;
        } else {
            throw new ContainerException($name . " Зависимость уже имеется в контейнере");
        }
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->container_store);
    }
}