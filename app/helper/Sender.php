<?php


namespace deli13\Loader\helper;

use deli13\Loader\Exceptions\SenderException;
use deli13\Loader\Interfaces\SenderInterface;


class Sender implements SenderInterface
{
    private $smtp_config = [];
    private $from;
    private $transport;
    private $attach;


    public function withAttachment(string $attach_file_path)
    {
        $this->attach = \Swift_Attachment::fromPath($attach_file_path);
        return $this;
    }

    /**
     * Установка от кого
     * @param string $from
     * @return $this
     */
    public function setFrom(array $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Функция отправки письма
     * @param mixed $sender Массив адресатов
     * @param string $subject Тема письма
     * @param string $message сообщение
     * @return bool
     */
    public function sendMail($sender, $subject, $message)
    {
        $mailer = new \Swift_Mailer($this->transport);
        $message = (new \Swift_Message($subject))
            ->setFrom($this->from)
            ->setCharset("UTF-8")
            ->setBody($message, "text/html");
        if ($this->attach) {
            $message->attach($this->attach);
        }
        if (is_array($sender) && count($sender) > 1) {
            $message->setTo(array_shift($sender));
            $message->setCc($sender);
        } else if (is_string($sender) || (is_array($sender) && count($sender) == 1)) {
            $message->setTo($sender);
        } else {
            throw new SenderException("Отправители должны быть массивом или строкой");
        }


        if ($mailer->send($message)) {
            $this->attach = null;
            return true;
        } else {
            print_r($mailer->ErrorInfo);
            return false;
        }
    }

    /**
     * Установка конфигурации SMTP
     * @param array $config
     * @return $this
     * @throws SenderException
     */
    public function isSMTP(array $config)
    {
        self::validateSMTP($config);
        $this->transport = (new \Swift_SmtpTransport($config["host"], $config["port"]))
            ->setUsername($config["username"])
            ->setPassword($config["password"])
            ->setEncryption($config["secure"]);
        return $this;
    }

    /**
     * Установка механизма рассылки через SMTP
     */
    public function isSendmail()
    {
        $this->transport = new \Swift_SendmailTransport();
        return $this;
    }

    /**
     * Валидация Массива с данными для SMTP подключения
     * @param array $config
     * @return bool
     * @throws SenderException
     */
    private static function validateSMTP(array $config)
    {
        $field = ["host", "port", "username", "password", "secure"];
        foreach ($field as $value) {
            if (!array_key_exists($value, $config)) {
                throw new SenderException("Не верная конфигурация SMTP. Не указано " . $value);
            }
        }
        return true;
    }
}