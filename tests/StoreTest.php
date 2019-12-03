<?php

use deli13\Loader\Loader;

class StoreTest extends \PHPUnit\Framework\TestCase
{
    public function testStore()
    {
        echo "Тест хранилища\n";
        $loader = Loader::getInstance();
        $loader->setStore(new \deli13\Loader\Container\Container());
        $this->assertInstanceOf(\deli13\Loader\Interfaces\ContainerInterface::class,$loader->getStore());
    }

    public function testStoreDependency()
    {
        echo "Тест сохранения объекта в хранилище\n";
        $loader=Loader::getInstance();
        $loader->setStore(new \deli13\Loader\Container\Container());
        $loader->getStore()->set("test",new Dependency());
        $this->assertInstanceOf(Dependency::class,$loader->getStore()->get("test"));
    }

    public function testStoreSaved()
    {
        echo "Тест изъятия объекта";
        $loader=Loader::getInstance();
        $this->assertStringContainsString($loader->getStore()->get("test")->Name(),"name");
    }

    public function testBad(){
        echo "Тест ошибок в случае взятия \n";
        $this->expectException(\deli13\Loader\Exceptions\ContainerException::class);
        Loader::getInstance()->getStore()->get("qwe");
    }
}

class Dependency implements \deli13\Loader\Interfaces\DependencyInterface
{
    public function Name(){
        return "name";
    }

}