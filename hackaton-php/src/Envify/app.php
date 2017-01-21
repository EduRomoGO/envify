<?php

namespace Envify;

use Envify\Service\HotelBedsService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Application();

$app->error(function (\Exception $e) use ($app) {
    return $app->json(['error' => $e->getCode(), 'message' => $e->getMessage()], 400);
});

$app['hotelbeds_service'] = function ($app) {
    return new HotelBedsService();
};

$app->get('/', function () use ($app) {
    $output = ['Bienvenido a Envify'];

    return $app->json($output);
});


$app->post('/locations', function ($keywords) use ($app) {
    /** @var HotelBedsService $logService */
    $hotelbedsService = $app['hotelbeds_service'];

    $output = ['a'];
    return $app->json($output);
});

// CORS to allow calls to the endpoint
$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

return $app;
