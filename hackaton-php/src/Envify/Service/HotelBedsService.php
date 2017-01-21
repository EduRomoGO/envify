<?php

namespace Envify\Service;

use GuzzleHttp\Client;

class HotelBedsService
{
    private $parameters;
    /** @var  Client */
    private $client;

    public function __construct($parameters, $client)
    {
        $this->parameters = $parameters;
        $this->client = $client;
    }

    public function getLocationsByKeywords(array $keywords)
    {
        try {
            $res = $this->client->request(
                'GET',
                $this->parameters['endpoint'] . '/hotel-api/1.0/status',
                [
                    'headers' => [
                        'Api-Key' => $this->parameters['apiKey'],
                        'X-Signature' => $this->getSignature(),
                        'Accept' => 'application/json'
                    ]
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception('Impossible to connect with HotelBeds');
        }

        return json_decode($res->getBody()->getContents());
    }

    /**
     * Get status of HotelBeds API (just for testing)
     */
    public function getStatus()
    {
        try {
            $res = $this->client->request(
                'GET',
                $this->parameters['endpoint'] . '/hotel-api/1.0/status',
                [
                    'headers' => [
                        'Api-Key' => $this->parameters['apiKey'],
                        'X-Signature' => $this->getSignature(),
                        'Accept' => 'application/json'
                    ]
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception('Impossible to connect with HotelBeds');
        }

        return json_decode($res->getBody()->getContents());
    }

    private function getSignature()
    {
        return hash('sha256', $this->parameters['apiKey'] . $this->parameters['secret'] . time());
    }
}
