<?php

use deli13\Loader;
use deli13\helper\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testSenderSMTP()
    {
        $config = [
            "host" => "autodiscover.wagner-auto.ru",
            "username" => "webservice",
            "password" => "Av-993762",
            "secure" => "tls",
            "port" => 587
        ];
        $sender = Loader::getInstance()->mailer()->isSMTP($config)->setFrom(["webservice@wagner-auto.ru" => "Тест"]);
        $this->assertTrue($sender->sendMail("Mikhail.Yakovlev@wagner-auto.ru", "Тест", "ТЕСТ"));
    }



}