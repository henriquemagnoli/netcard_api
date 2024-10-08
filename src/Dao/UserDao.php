<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface UserDao
{
    public function addUser(object $request_body) : ResponseMessage;
    public function updateUser(object $request_body, int $user_id) : ResponseMessage;
    public function getUser(int $user_id) : ResponseMessage;
    public function addUserConnection(object $request_body, int $user_id) : ResponseMessage;
    public function getAllUserConnections(int $user_id, array $query_params) : ResponseMessage;
    public function getUserConnectionById(int $user_id, int $connection_id) : ResponseMessage;
    public function addUserCoordinate(int $user_id, object $request_body) : ResponseMessage;
    public function getAllCoordinates() : ResponseMessage;
}

?>