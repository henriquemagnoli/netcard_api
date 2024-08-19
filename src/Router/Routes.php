<?php

use Slim\App;

use Netcard\Controller\UserController;
use Netcard\Controller\LoginController;

return function(App $app)
{
    $app->post('user/register', UserController::class . ':addUser');
    $app->post('/login', LoginController::class . ':login');
}

?>