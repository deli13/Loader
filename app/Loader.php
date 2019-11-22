<?php


namespace deli13;
use ParagonIE\EasyDB\EasyDB;

class Loader
{
    private static $_instance;
    private $connection;
    private $base_dir;
    private $env;
    private $logger;
    private $mailer;


    protected function __construct()
    {

        $this->logger = new Logger();
        $this->mailer = new Sender();
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
    public function setEnv($env)
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
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Получение хелпера UMI
     * @return UmiHelper
     */
    public function getHelper()
    {
        return $this->helper;
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
    public function startSaveRunner(\Closure $func, bool $send_error = true): bool
    {
        try {
            $func();
        } catch (\Exception|\Error|\TypeError $e) {
            if ($send_error) {
                $this->logger()->sendLog($e);
            } else {
                print_r("\n".$e->getMessage()."\n");
            }
            return false;
        }
        return true;
    }
}