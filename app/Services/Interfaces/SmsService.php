<?php

namespace App\Services\Interfaces;

interface SmsService
{
    public function send($to, $message, $from = null): void;
    public function getAccountInfo(): array;
}
