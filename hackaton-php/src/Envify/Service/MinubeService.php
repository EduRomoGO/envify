<?php

namespace Envify\Service;

use GuzzleHttp\Client;

class MinubeService
{
    private $parameters;
    /** @var  Client */
    private $client;

    private $lang = 'en';
    private $country_id = 63; // España

    public function __construct($parameters, $client)
    {
        $this->parameters = $parameters;
        $this->client = $client;
    }

    public function getPoisByCategoryId($categoryId)
    {
        $categoryId = (int)$categoryId;
        $limit = 12;

        try {
            $res = $this->client->request(
                'GET',
                $this->parameters['endpoint'] . '/pois?lang=' . $this->lang . '&country_id=' . $this->country_id . '&subcategory_id=' . $categoryId . '&api_key=' . $this->parameters['apiKey']
            );
        } catch (\Exception $e) {
            throw new \Exception('Impossible to connect with MiNube');
        }

        return array_slice(json_decode($res->getBody()->getContents()), 0, $limit);
    }

    /**
     * Get CategoryID by Name
     */
    public function getCategoryIdByName($name)
    {
        try {
            $res = $this->client->request(
                'GET',
                $this->parameters['endpoint'] . '/subcategories?lang=' . $this->lang . '&api_key=' . $this->parameters['apiKey']
            );
        } catch (\Exception $e) {
            throw new \Exception('Impossible to connect with MiNube');
        }

        $categories = json_decode($res->getBody()->getContents());
        $result = new \stdClass();

        foreach ($categories as $category) {
            if ($category->name == $name) {
                $result = $category;
                break;
            }
        }

        return $result;
    }

    /**
     * Get All the zones from API
     */
    public function getZones()
    {
        // http://papi.minube.com/cities?lang=es&country_id=63&api_key=TzZMM8SretR3ZevC
        try {
            $res = $this->client->request(
                'GET',
                $this->parameters['endpoint'] . '/cities?lang=' . $this->lang . '&country_id=' . $this->country_id . '&api_key=' . $this->parameters['apiKey']
            );
        } catch (\Exception $e) {
            throw new \Exception('Impossible to connect with MiNube');
        }

        $zones = json_decode($res->getBody()->getContents());
        $result = [];

        foreach ($zones as $zone) {
            // $result[$zone->zone_name] = $zone->zone_id;
            $result[$zone->zone_id] = $zone->zone_name;
        }

        return $result;
    }
}
