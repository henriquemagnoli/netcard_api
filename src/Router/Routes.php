<?php

use Slim\App;

use Netcard\Controller\UserController;
use Netcard\Controller\LoginController;
use Netcard\Controller\StatesController;
use Netcard\Controller\CitiesController;
use Netcard\Controller\JobsController;

return function(App $app)
{
    // User Endpoints
    $app->post('/api/user', UserController::class . ':addUser');
    $app->patch('/api/user/{id}', UserController::class . ':updateUser');
    $app->get('/api/user/{id}', UserController::class . ':getUser');
    $app->post('/api/user/{id}/connection', UserController::class . ':addUserConnection');
    $app->get('/api/user/{id}/connections', UserController::class . 'getAllUserConnections');
    $app->get('/api/user/{id}/connection/{connectionId}', UserController::class . 'getUserConnectionById');
    $app->post('/api/user/{id}/coordinate', UserController::class . ':addUserCoordinate');
    $app->get('/api/user/coordinates', UserController::class . ':getAllCoordinates');
    
    // Login Endpoints
    $app->post('/api/login', LoginController::class . ':login');

    // Helpers Endpoints
    $app->get('/api/states', StatesController::class . ':getAllStates');
    $app->get('/api/cities/{stateId}', CitiesController::class . ':getAllCities');
    $app->get('/api/jobs', JobsController::class . ':getAllJobs');
}

?>