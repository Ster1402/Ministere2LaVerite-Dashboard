<?php

namespace App\Services\messages;

use App\Services\Commons\MessageService;
use App\Services\Interfaces\SmsService;
use Twilio\Exceptions\TwilioException;

class ApiMessageService
{
    protected SmsService $defaultService;
    protected TwilioService $twilioService;
    protected NexahService $nexahService;

    public function __construct(TwilioService $twilioService, NexahService $nexahService)
    {
        $this->twilioService = $twilioService;
        $this->nexahService = $nexahService;

        $this->defaultService = $this->nexahService;
    }

    public function send($to, $message, $from = null, $service = null): bool
    {
        if ($service === null) {
            // Send the message using the default service
            $this->defaultService->send($to, $message, $from);
            return true;
        }

        if ($service === MessageService::$TWILIO) {
            return $this->sendTwilio($to, $message, $from);
        }

        if ($service === MessageService::$NEXAH) {
            return $this->sendNexah($to, $message, $from);
        }

        // Send the message
        return true;
    }

    public function sendTwilio($to, $message, $from): bool
    {
        // Send the message using Twilio
        $this->twilioService->send($to, $message, $from);
        return true;
    }

    /**
     * @throws TwilioException
     */
    public function getInfoMessageAccount($service = null): array
    {
        $accountInfo = null;

        if ($service === null) {
            // Get the account information using the default service
            $accountInfo = $this->defaultService->getAccountInfo();
        }

        if ($service === MessageService::$TWILIO) {
            // Get the account information using Twilio
            $accountInfo = $this->twilioService->getAccountInfo();
        }

        if ($service === MessageService::$NEXAH) {
            // Get the account information using Nexah
            $accountInfo = $this->nexahService->getAccountInfo();
        }

        $balance = $accountInfo['balance'] ?? null;
        $account = $accountInfo['account'] ?? null;
        $name = $accountInfo['name'] ?? null;

        return [
            'balance' => $balance,
            'account' => $account,
            'name' => $name,
        ];
    }

    public function sendNexah($to, $message, $from = null): bool
    {
        // Send the message using Nexah
        $this->nexahService->send($to, $message, $from);
        return true;
    }
}
