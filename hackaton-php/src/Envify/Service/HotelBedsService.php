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

    public function getHotelsByCoords($lat, $lng, $limit = 5)
    {
        // TODO: Give new dates based on current date
        $rawBody = '{
"stay": {
"checkIn": "2017-02-01",
"checkOut": "2017-02-07",
"shiftDays": "2"
},
"filter": {
 "maxRatesPerRoom": 1,
 "maxRooms": 1,
 "maxHotels": ' . $limit . '
},
"occupancies": [
{
  "rooms": 1,
  "adults": 2,
  "children": 0
}
],
  "geolocation": {
    "longitude": ' . $lng . ',
    "latitude": ' . $lat . ',
    "radius": 1,
    "unit": "km"
  }
}';

        try {
            $res = $this->client->request(
                'POST',
                $this->parameters['endpoint'] . '/hotel-api/1.0/hotels',
                [
                    'headers' => [
                        'Api-Key' => $this->parameters['apiKey'],
                        'X-Signature' => $this->getSignature(),
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'body' => $rawBody
                ]
            );
        } catch (\Exception $e) {
            var_dump($e);
            throw new \Exception('Impossible to connect with HotelBeds');
        }

        $result = json_decode($res->getBody()->getContents());
        $hotels = [];
        $i = 0;
        if (property_exists($result, 'hotels') && property_exists($result->hotels, 'hotels')) {
            foreach ($result->hotels->hotels as $hotel) {
                $hotels[] = [
                    'name' => $hotel->name
                ];

                if (++$i === $limit) {
                    break;
                }
            }
        }

        return $hotels;
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
