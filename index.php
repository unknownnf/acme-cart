<?php

require __DIR__ . 'bootstrap.php';

use Bramus\Router\Router;

$router = new Router();

$router->get('/(\d)?', static function (?int $scenario = 1) use ($scenarios,$renderer) {
    $scenarios($scenario);
    $renderer();
});

$router->run();