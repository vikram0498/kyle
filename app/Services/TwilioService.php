<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        // Use the API SID and Secret for authentication
        $this->client = new Client(config('services.twilio.key'), config('services.twilio.secret'), config('services.twilio.sid'));
    }

    public function send_SMS($to, $message)
    {
        return $this->client->messages->create($to, [
            'from' => config('services.twilio.from'),
            'body' => $message,
        ]);
    }

    public function makeCall($to, $url)
    {
        return $this->client->calls->create($to, config('services.twilio.from'), [
            'url' => $url, // This URL should return TwiML instructions
        ]);
    }
}
