<?php


class TracerTest extends \PHPUnit\Framework\TestCase
{
    public function testHtmlTrace(){
        echo "html трейс\n";
        $str="extest";
        $exception=new \Exception($str);
        $trace=\deli13\Loader\Util\Tracer::createHtmlStringTrace($exception);
        $this->assertIsString($trace);
        $this->assertContains($str,$trace);
    }

    public function testStringTrace(){
        echo "string trace\n";
        $str="extest";
        $exception=new \Exception($str);
        $trace=\deli13\Loader\Util\Tracer::createHtmlStringTrace($exception);
        $this->assertIsString($trace);
        $this->assertContains($str,$trace);
    }
}