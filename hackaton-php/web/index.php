<?php
$app = require __DIR__ . '/../src/Envify/app.php';

$app['debug'] = true;
$app->run();
