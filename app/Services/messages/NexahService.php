<?php

namespace App\Services\messages;

use App\Services\Interfaces\SmsService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class NexahService implements SmsService
{
    protected Client $client;
    protected string $url;
    protected string $user;
    protected string $password;
    protected string $defaultSender;

    public function __construct()
    {
        $this->user = $_ENV["NEXAH_USER"];
        $this->url = $_ENV["NEXAH_URL"];
        $this->password = $_ENV["NEXAH_PASSWORD"];
        $this->defaultSender = $_ENV["NEXAH_SENDER"];

        $this->client = new Client([
            "base_uri" => $this->url,
            "timeout" => 5.0
        ]);
    }

    public function send($to, $message, $from = null): void
    {
        // Send the message using Nexah API
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        try {
            $user = $this->user;
            $password = $this->password;
            $senderId = $from ?? $this->defaultSender;
            $this->client->request('POST', $this->url . "/sendsms", [
                'headers' => $headers,
                'json' => [
                    'user' => $user,
                    'password' => $password,
                    'senderid' => $senderId,
                    'sms' => $message,
                    'mobiles' => $to,
                ],
            ]);
        } catch (RequestException|GuzzleException $ex) {
            // Log the error
            \Log::error($ex->getMessage());
            dd($ex->getMessage());
        }
    }

    public function getAccountInfo(): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        try {
            $user = $this->user;
            $password = $this->password;
            $response = $this->client->request('POST', $this->url . "/smscredit", [
                'headers' => $headers,
                'json' => [
                    'user' => $user,
                    'password' => $password,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return [
                'balance' => $data['credit'] . ' Crédit',
                'account' => $this->user,
                'name' => $this->defaultSender,
            ];

        } catch (RequestException|GuzzleException $ex) {
            // Log the error
            \Log::error($ex->getMessage());
            dd($ex->getMessage());
        }
    }
}
