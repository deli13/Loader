<?php


namespace deli13\Loader;

use deli13\Loader\Exceptions\ContainerException;
use deli13\Loader\Interfaces\ContainerInterface;
use deli13\Loader\Interfaces\LoggerInterface;
use deli13\Loader\Interfaces\SenderInterface;
use deli13\Loader\Util\Handlers;
use ParagonIE\EasyDB\EasyDB;
use deli13\Loader\helper\Logger;
use deli13\Loader\helper\Sender;

class Loader
{
    private static $_instance;
    private $connection;
    private $base_dir;
    private $env;
    private $logger;
    private $mailer;
    private $store;


    protected function __construct()
    {
        $this->logger = new Logger();
        $this->mailer = new Sender();
    }

    /**
     * Установка отправщика почты
     * @param SenderInterface $sender
     */
    public function setMailer(SenderInterface $sender)
    {
        $this->mailer = new $sender();
    }

    /**
     * Установка класса логирования
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = new $logger();
    }

    public function setStore(ContainerInterface $store)
    {
        $this->store=$store;
    }

    public function getStore()
    {
        if(!($this->store instanceof ContainerInterface)){
            throw new ContainerException("Контейнер не найден");
        }
        return $this->store;
    }

    /**
     * Создание инстанса приложения
     * @return Loader
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * Установка соединения с БД
     * @param EasyDB $easyDB
     */
    public function setConnection(EasyDB $easyDB)
    {
        $this->connection = $easyDB;
    }


    /**
     * Установка переменной окружения dev|prod
     * @param $env
     * @throws \ErrorException
     */
    public function setEnv(string $env)
    {
        if (!in_array($env, ["dev", "prod"])) {
            throw new \ErrorException("Не правильно установлено окружение" . $env);
        }
        $this->env = $env;
    }

    /**
     * Получение переменной окружения dev|prod
     * @return mixed
     */
    public function getEnv(): string
    {
        return $this->env;
    }


    /**
     * Получение экземпляра БД
     * @return EasyDB
     * @throws \ErrorException
     */
    public function getConnection(): EasyDB
    {
        if (is_null($this->connection)) {
            throw new \ErrorException("БД не инициализировано");
        }
        return $this->connection;
    }

    /**
     * Установка базовой директории
     * @param string $dir
     * @throws \ErrorException
     */
    public function setBaseDir(string $dir)
    {
        if (!is_dir($dir)) {
            throw new \ErrorException($dir . " не является директорией");
        }
        $this->base_dir = $dir;
    }

    /**
     * Получение базовой директории
     * @return string
     */
    public function getBaseDir(): string
    {
        return $this->base_dir;
    }

    /**
     * Отправка логов
     * @return Logger
     */
    public function logger()
    {
        $this->logger->setSender($this->mailer);
        return $this->logger;
    }

    /**
     * Отправка писем
     * @return Sender
     */
    public function mailer()
    {
        return $this->mailer;
    }

    /**
     * Запуск безопасной функции с логированием в случае ошибки
     * @param \Closure $func
     * @return bool
     */
    public function startSaveRunner(\Closure $func, bool $send_error = true)
    {
        try {
            return $func();
        } catch (\Exception|\Error|\TypeError $e) {
            if ($send_error) {
                $this->logger()->sendLog($e);
            } else {
                print_r("\n" . $e->getMessage() . "\n");
            }
            return false;
        }
    }

    public function setHandler(\Closure $function){
        Handlers::setExceptionHandler($function);
        Handlers::setErrorHandler($function);
    }
}