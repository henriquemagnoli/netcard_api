<?php

use Slim\App;

use NetCard\Controller\UserController;

return function(App $app)
{
    $app->post('/register', UserController::class . ':addUser');
}

?>