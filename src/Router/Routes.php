<?php

use Slim\App;

use Netcard\Controller\UserController;
use Netcard\Controller\CoordinatesController;
use Netcard\Controller\ConnectionsController;
use Netcard\Controller\StatesController;
use Netcard\Controller\CitiesController;
use Netcard\Controller\JobsController;
use Netcard\Controller\SocialMediasController;
use Netcard\Middleware\AuthToken;

return function(App $app)
{   
    $app->group('/api/user', function() use ($app) {
        $app->get('/api/user/{id}', UserController::class . ':getUser'); // Get User by Id
        $app->patch('/api/user', UserController::class . ':updateUser'); // Update User

        $app->group('/visible', function() use ($app) {
            $app->patch('/api/user/visible', UserController::class . ':updateUserVisible'); // Set user visible
        });

        $app->group('/social-media', function() use ($app) {
            $app->post('/api/user/social-media', UserController::class . ':setUserSocialMedia'); // Set User Social Media
            $app->patch('/api/user/social-media/{id}', UserController::class . ':updateUserSocialMedia'); // Update user social media
            $app->delete('/api/user/social-media/{id}', UserController::class . ':deleteUserSocialMedia'); // Delete user social media
        }); 
    })->add(new AuthToken);

    $app->group('/api/coordinates', function() use ($app) {
        $app->get('/api/coordinates', CoordinatesController::class . ':getAllCoordinates'); // Get all coordinates from DB
        $app->post('/api/coordinates', CoordinatesController::class . ':addUserCoordinate'); // Add new coordinate from user
        $app->delete('/api/coordinates/{id}', CoordinatesController::class . ':deleteUserCoordinate'); // Delete coordinate from user
        $app->patch('/api/coordinates/{id}', CoordinatesController::class . ':updateUserCoordinate'); // Update coordinate from user
    })->add(new AuthToken);

    $app->group('/api/connections', function() use ($app) {
        $app->get('/api/connections', ConnectionsController::class . ':getAllUserConnections'); // Get all user connections
        $app->post('/api/connections', ConnectionsController::class . ':addUserConnection'); // Add new connection to user
        $app->get('/api/connections/{id}', ConnectionsController::class . ':getUserConnectionById'); // Get user connection by Id
        $app->delete('/api/connections/{id}', ConnectionsController::class . ':deleteUserConnection'); // Delete connection between users
    })->add(new AuthToken);

    $app->group('/api/states', function() use ($app) {
        $app->get('/api/states', StatesController::class . ':getAllStates');
    });

    $app->group('/api/cities', function() use ($app) {
        $app->get('/api/cities/{stateId}', CitiesController::class . ':getAllCities');
    });

    $app->group('/api/jobs', function() use ($app) {
        $app->get('/api/jobs', JobsController::class . ':getAllJobs');
    });

    $app->group('/api/socialmedias', function() use ($app) {
        $app->get('/api/socialmedias', SocialMediasController::class . ':getAllSocialMedias');
    })->add(new AuthToken); 
}

?>