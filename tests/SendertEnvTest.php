<?php

use deli13\Loader\Loader;
use deli13\Loader\helper\Sender;
use PHPUnit\Framework\TestCase;

class SenderEnvTest extends TestCase
{
    public function testSenderSMTP()
    {
        $config = [
            "host" => "your host",
            "username" => "your name",
            "password" => "pass",
            "secure" => "encryption",
            "port" => 000
        ];
        $sender = Loader::getInstance()->mailer()->isSMTP($config)->setFrom(["test@test.ru" => "Тест"]);
        $this->assertTrue($sender->sendMail("test@test.ru", "Тест", "ТЕСТ"));
    }



}