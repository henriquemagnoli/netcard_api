<?php

use Slim\App;

use Netcard\Controller\UserController;
use Netcard\Controller\LoginController;

return function(App $app)
{
    $app->post('/user', UserController::class . ':addUser');
    $app->patch('/user/{id}', UserController::class . ':updateUser');
    $app->post('/login', LoginController::class . ':login');
}

?>