<?php

namespace App\Services\messages;

use App\Services\Interfaces\SmsService;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioService implements SmsService
{
    protected Client $client;

    /**
     * @throws ConfigurationException
     */
    public function __construct()
    {
        $twilioSid = $_ENV["TWILIO_ACCOUNT_SID"];
        $twilioToken = $_ENV["TWILIO_ACCOUNT_TOKEN"];

        $this->client = new Client($twilioSid, $twilioToken);
    }

    /**
     * Get the Twilio client instance.
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    public function send($to, $message, $from = null): void
    {
        try {
            $this->client->messages->create(
                $to,
                [
                    'from' => $from ?? $_ENV["TWILIO_PHONE_NUMBER"],
                    'body' => $message,
                ]
            );
        } catch (TwilioException $ex) {
            // Log the error
            \Log::error($ex->getMessage());
            dd($ex->getMessage());
        }
    }

    /**
     * Get account information
     * @throws TwilioException
     */
    public function getAccountInfo(): array
    {
        // Get account information
        $account = $this->client->api->v2010->accounts($this->client->getAccountSid())->fetch();

        // Get balance information (Twilio does not provide balance directly through REST API)
        $balance = $this->client->balance->fetch();

        return [
            'balance' => $balance->balance . ' ' . $balance->currency,
            'account' => $account->sid,
            'name' => $account->friendlyName,
        ];
    }
}
