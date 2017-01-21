<?php

namespace Envify;

use Envify\Service\HotelBedsService;
use Envify\Transformer\KeywordTransformer;
use Envify\Transformer\LocationTransformer;
use Envify\Transformer\TransformerInterface;
use GuzzleHttp\Client;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Application();

$app->error(function (\Exception $e) use ($app) {
    return $app->json(['error' => $e->getCode(), 'message' => $e->getMessage()], 400);
});

$app['hotelbeds_credentials'] = require __DIR__ . '/config.php';

$app['http_client'] = function () {
    return new Client();
};

$app['hotelbeds_service'] = function ($app) {
    return new HotelBedsService($app['hotelbeds_credentials'], $app['http_client']);
};

$app['keywords_service'] = function ($app) {
    return new KeywordTransformer();
};

$app['locations_service'] = function ($app) {
    return new LocationTransformer();
};

$searchCallback = function ($keywords) use ($app) {
    if (!is_array($keywords)) {
        $keywords = explode(',', $keywords);
    }

    /** @var TransformerInterface $keywordTransformer */
    $keywordTransformer = $app['keywords_service'];

    /** @var TransformerInterface $locationsTransformer */
    $locationsTransformer = $app['locations_service'];

    /** @var HotelBedsService */
    $hotelbedsService = $app['hotelbeds_service'];

    $searchCriteria = $keywordTransformer->transform($keywords);
    $locations = $locationsTransformer->transform($searchCriteria);

    arsort($locations);

    $output = $locations;
    // $output = $searchCriteria;
    return $app->json($output);
};

$app->post('/locations', function (Request $request) use ($searchCallback, $app) {
    $keywords = $request->get('keywords');

    return $searchCallback($keywords);
});

$app->get('/locations/{keywords}', $searchCallback);

$app->get('/', function () use ($app) {
    $output = ['Bienvenido a Envify'];

    return $app->json($output);
});

$app->get('/hbstatus', function () use ($app) {
    /** @var HotelBedsService */
    $hotelbedsService = $app['hotelbeds_service'];

    $result = $hotelbedsService->getStatus();
    $output = ['status' => $result->status];
    return $app->json($output);
});

// CORS to allow calls to the endpoint
$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

return $app;
