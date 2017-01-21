<?php

namespace Envify;

use Envify\Service\HotelBedsService;
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

$app->get('/', function () use ($app) {
    $output = ['Bienvenido a Envify'];

    return $app->json($output);
});

$app->get('/hbstatus', function () use ($app) {
    /** @var HotelBedsService $logService */
    $hotelbedsService = $app['hotelbeds_service'];

    $result = $hotelbedsService->getStatus();
    $output = ['status' => $result->status];
    return $app->json($output);
});


$app->post('/locations', function (Request $request) use ($app) {
    /** @var HotelBedsService $logService */
    $hotelbedsService = $app['hotelbeds_service'];

    $keywords = $request->get('keywords');

    $output = $keywords;
    return $app->json($output);
});

$app->get('/locations/{keywords}', function ($keywords) use ($app) {
    /** @var HotelBedsService $logService */
    $hotelbedsService = $app['hotelbeds_service'];

    $keywords = explode(',', $keywords);

    $output = $keywords;
    return $app->json($output);
});

// CORS to allow calls to the endpoint
$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

return $app;
