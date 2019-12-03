<?php


namespace deli13\Loader\helper;


use deli13\Loader\Interfaces\LoggerInterface;
use deli13\Loader\Interfaces\SenderInterface;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

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
            $trace = $this->createStringTrace($trace);
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

    /**
     * Создание письма на отправку для
     * @param \Exception|\Throwable $trace
     * @return false|string
     */
    private function createStringTrace($trace)
    {
        if($trace instanceof \Exception){
            $e = FlattenException::create($trace);
        } else if ($trace instanceof \Throwable) {
            $e= FlattenException::createFromThrowable($trace);
        }
        $handler = new ExceptionHandler();
        print_r($e->getAsString());
        return $handler->getHtml($e);
    }

}