<?php

use Slim\App;

use Netcard\Controller\UserController;
use Netcard\Controller\LoginController;
use Netcard\Controller\StatesController;
use Netcard\Controller\CitiesController;
use Netcard\Controller\JobsController;
use Netcard\Controller\SocialMediasController;

return function(App $app)
{
    // User Endpoints
    $app->post('/api/user', UserController::class . ':addUser'); // Register new user
    $app->patch('/api/user/{id}', UserController::class . ':updateUser'); // Update User
    $app->get('/api/user/coordinates', UserController::class . ':getAllCoordinates'); // Get all coordinates from DB
    $app->get('/api/user/{id}', UserController::class . ':getUser'); // Get User by Id
    $app->post('/api/user/{id}/connection', UserController::class . ':addUserConnection'); // Add new connection to user
    $app->get('/api/user/{id}/connections', UserController::class . ':getAllUserConnections'); // Get all user connections
    $app->get('/api/user/{id}/connection/{connectionId}', UserController::class . ':getUserConnectionById'); // Get user connection by Id
    $app->post('/api/user/{id}/coordinate', UserController::class . ':addUserCoordinate'); // Add new coordinate from user
    $app->delete('/api/user/{id}/coordinate', UserController::class . ':deleteCoordinate'); // Delete coordinate from user
    
    
    // Login Endpoints
    $app->post('/api/login', LoginController::class . ':login');

    // Helpers Endpoints
    $app->get('/api/states', StatesController::class . ':getAllStates');
    $app->get('/api/cities/{stateId}', CitiesController::class . ':getAllCities');
    $app->get('/api/jobs', JobsController::class . ':getAllJobs');
    $app->get('/api/socialmedias', SocialMediasController::class . 'getAllSocialMedias');
}

?>