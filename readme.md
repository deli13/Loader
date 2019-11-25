Небольшой инстанс для личного пользования  
Пример инициализации
```php
<?php
require_once dirname(__FILE__) . "/vendor/autoload.php";

use deli13\Loader;
use ParagonIE\EasyDB\Factory;

function app()
{
    return Loader::getInstance();
}

/**
 * Указание базовой директории
 */
app()->setBaseDir(dirname(__FILE__));
/**
 * Установка подключения
 */
$connection = Factory::create("mysql:host=127.0.0.1;dbname=admindb", "root", "1234");
app()->setConnection($connection);
$all_action = app()->getConnection()->row("select * from t_action");


/**
 * Задание почты через sendmail
 */
app()->mailer()->isSendmail()->setFrom(["test@test.ru" => "Тестовый ящик"]);
/**
 * Задание почты через smtp
 */
app()
    ->mailer()
    ->isSMTP([
        "host" => "xxx.xx",
        "username" => "test",
        "password" => "xxx",
        "port" => 587,
        "secure" => "tls"
    ]);
/**
 * Запуск безопасного обработчика
 * с логированием на почту в случае ошибки
 */
app()->startSaveRunner(function () {
    throw new Exception("Тест");
}, true);
```