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
        $limit = 5;

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
}
