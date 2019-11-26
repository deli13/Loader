<?php


namespace deli13\Interfaces;


interface SenderInterface
{
    public function setFrom(array $from);

    public function sendMail($sender, $subject, $message);


}