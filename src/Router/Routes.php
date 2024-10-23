<?php

use Slim\App;

use Netcard\Controller\UserController;
use Netcard\Controller\StatesController;
use Netcard\Controller\CitiesController;
use Netcard\Controller\JobsController;
use Netcard\Controller\SocialMediasController;

return function(App $app)
{
    // User Endpoints
    $app->patch('/api/user/{id}', UserController::class . ':updateUser'); // Update User
    $app->get('/api/user/coordinates', UserController::class . ':getAllCoordinates'); // Get all coordinates from DB
    $app->get('/api/user/{id}', UserController::class . ':getUser'); // Get User by Id
    $app->post('/api/user/{id}/connection', UserController::class . ':addUserConnection'); // Add new connection to user
    $app->get('/api/user/{id}/connections', UserController::class . ':getAllUserConnections'); // Get all user connections
    $app->get('/api/user/{id}/connection/{connectionId}', UserController::class . ':getUserConnectionById'); // Get user connection by Id
    $app->delete('/api/user/{id}/connection/{connectionId}', UserController::class . ':deleteUserConnection'); // Delete connection between users
    $app->post('/api/user/{id}/coordinate', UserController::class . ':addUserCoordinate'); // Add new coordinate from user
    $app->delete('/api/user/{id}/coordinate', UserController::class . ':deleteUserCoordinate'); // Delete coordinate from user
    $app->patch('/api/user/{id}/coordinate', UserController::class . ':updateUserCoordinate'); // Update coordinate from user
    $app->post('/api/user/{id}/visible', UserController::class . ':setUserVisible'); // Set user visible

    // Helpers Endpoints
    $app->get('/api/states', StatesController::class . ':getAllStates');
    $app->get('/api/cities/{stateId}', CitiesController::class . ':getAllCities');
    $app->get('/api/jobs', JobsController::class . ':getAllJobs');
    $app->get('/api/socialmedias', SocialMediasController::class . ':getAllSocialMedias');
}

?>