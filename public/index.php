<?php
require '../vendor/autoload.php';

use Slim\Factory\AppFactory;

// Create Slim app instance
$app = AppFactory::create();
$app->addBodyParsingMiddleware(); 

// Include and configure routes from App.php
(require __DIR__ . '/../src/App.php')($app);

// Run the app
$app->run();