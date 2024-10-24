<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface CoordinatesDao
{
    public function getAllCoordinates() : ResponseMessage;
    public function addUserCoordinate(object $request_body, string $accessToken) : ResponseMessage;    
    public function deleteUserCoordinate(string $accessToken) : ResponseMessage;
    public function updateUserCoordinate(object $request_body, string $accessToken) : ResponseMessage;
}

?>