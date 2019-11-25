<?php


namespace deli13\helper;


use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

class Logger
{
    private $email = ["Mikhail.Yakovlev@wagner-auto.ru"];

    /**
     * Отправка логов
     * @param $trace
     */
    public function sendLog($trace)
    {
        $sender = new Sender();
        if (!is_string($trace)) {
            $trace = $this->createStringTrace($trace);
        }
        $sender->sendMail($this->email, "Ошибки на wagner-auto.ru", $trace);
    }

    /**
     * Смена получателей логов
     * @param array $addr
     */
    public function switchAddress(array $addr)
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