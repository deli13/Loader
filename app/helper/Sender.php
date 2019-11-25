<?php


namespace deli13\helper;

use deli13\Exceptions\SenderException;
use PHPMailer\PHPMailer\PHPMailer;

class Sender
{
    private $smtp = false;
    private $sendmail = true;
    private $smtp_config = [];
    private $from;




    /**
     * Установка от кого
     * @param string $from
     * @return $this
     */
    public function setFrom(string $from)
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
        $mailer = $this->getMailer();
        $mailer->setFrom($this->from);
        if (is_array($sender)) {
            foreach ($sender as $val) {
                $mailer->addAddress($val);
            }
        } else if (is_string($sender)) {
            $mailer->addAddress($sender);
        } else {
            throw new SenderException("Отправители должны быть массивом или строкой");
        }
        $mailer->isHTML(true);
        $mailer->Subject = $subject;
        $mailer->Body = $message;
        return $mailer->send();
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
        $this->smtp = true;
        $this->sendmail = false;
        $this->smtp_config = $config;
        return $this;
    }

    /**
     * Установка механизма рассылки через SMTP
     */
    public function isSendmail(){
        $this->sendmail=true;
        $this->smtp=false;
    }

    private static function validateSMTP(array $config)
    {
        $field = ["host", "port", "username", "password", "secure"];
        foreach ($field as $value) {
            if (!array_key_exists($value, $config)) {
                throw new SenderException("Не верная конфигурация SMTP. Не указано ".$value);
            }
        }
        return true;
    }

    /**
     * Получение Мейлера
     * @return PHPMailer
     */
    private function getMailer()
    {
        $mailer = new PHPMailer(true);
        $mailer->CharSet = "UTF-8";
        if ($this->sendmail) {
            $mailer->isSendmail();
        } else if ($this->smtp) {
            $mailer->isSMTP();
            $mailer->SMTPAuth=true;
            $mailer->Host=$this->smtp_config["host"];
            $mailer->Username=$this->smtp_config["username"];
            $mailer->Password=$this->smtp_config["password"];
            $mailer->SMTPSecure=$this->smtp_config["secure"];
            $mailer->Port=$this->smtp_config["port"];
            $mailer->SMTPDebug=2;
        } else {
            throw new SenderException("Не выбрана авторизация");
        }
        return $mailer;
    }
}