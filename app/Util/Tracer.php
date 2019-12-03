<?php


namespace deli13\Loader\Util;


use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler;

/**
 * Class Tracer
 * Создание текстового представления для логов
 * @package deli13\Loader\Util
 */
class Tracer
{
    /**
     * Создание письма на отправку для
     * @param \Exception|\Throwable $trace
     * @return false|string
     */
    public static function createHtmlStringTrace($trace): string
    {
        $e = self::flattenCreate($trace);
        $handler = new ExceptionHandler();
        return $handler->getHtml($e);
    }

    private static function flattenCreate($trace): FlattenException
    {
        if ($trace instanceof \Exception) {
            $e = FlattenException::create($trace);
        } else if ($trace instanceof \Throwable) {
            $e = FlattenException::createFromThrowable($trace);
        }
        return $e;
    }

    /**
     *
     * @param \Exception|\Throwable $trace
     * @return string
     */
    public static function createConsoleStringTrace($trace): string
    {
        $e = self::flattenCreate($trace);
        return $e->getAsString();
    }
}