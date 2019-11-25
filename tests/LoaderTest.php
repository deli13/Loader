<?php
use PHPUnit\Framework\TestCase;
use deli13\Loader;

class LoaderTest extends TestCase
{

    public function testCanBeLoader(){
        echo "Can Be Loader\n";
        $this->assertInstanceOf(Loader::class,Loader::getInstance());
    }

    public function testCanBeSender(){
        echo "Can Be Sender\n";
        $this->assertInstanceOf(\deli13\helper\Sender::class,Loader::getInstance()->mailer());
    }

    public function testCanBeLogger(){
        echo "Can Be Logger\n";
        $this->assertInstanceOf(\deli13\helper\Logger::class,Loader::getInstance()->logger());
    }

    public function testSetEnv(){
        Loader::getInstance()->setEnv("dev");
        $this->assertStringContainsString("dev",Loader::getInstance()->getEnv());
    }

    public function testSetDB(){
        Loader::getInstance()->setConnection(\ParagonIE\EasyDB\Factory::create("mysql:host=127.0.0.1;dbname=admindb","root","1234"));
        $this->assertInstanceOf(\ParagonIE\EasyDB\EasyDB::class,Loader::getInstance()->getConnection());
    }

    public function testDirectory(){
        Loader::getInstance()->setBaseDir(dirname(__DIR__));
        $this->assertStringContainsString(dirname(__DIR__),Loader::getInstance()->getBaseDir());
    }
}