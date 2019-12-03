<?php


namespace deli13\Loader\helper;


use deli13\Loader\Interfaces\LoggerInterface;
use deli13\Loader\Interfaces\SenderInterface;
use deli13\Loader\Util\Tracer;

class Logger implements LoggerInterface
{
    private $email = [];
    private $sender;
    /**
     * Отправка логов
     * @param $trace
     */
    public function sendLog($trace)
    {
        if (!is_string($trace)) {
            $trace = Tracer::createHtmlStringTrace($trace);
        }
        $this->sender->sendMail($this->email, "Ошибки", $trace);
    }

    public function setSender(SenderInterface $sender){
        $this->sender=$sender;
    }

    /**
     * Установка получателей логов
     * @param array $addr
     */
    public function setAddress(array $addr)
    {
        $this->email = $addr;
    }



}