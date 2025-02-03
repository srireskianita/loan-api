<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

// Create Slim App
$app = AppFactory::create();

// Define a POST route for /loan
$app->post('/loan', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $response->getBody()->write(json_encode(['message' => 'Loan application submitted successfully']));
    return $response->withHeader('Content-Type', 'application/json');
});

// Run the application
var_dump($app->getRouteCollector()->getRoutes());
$app->run();
