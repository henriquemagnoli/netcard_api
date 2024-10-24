<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface ConnectionsDao
{
    public function getAllUserConnections(array $query_params, string $accessToken) : ResponseMessage;
    public function addUserConnection(object $request_body, string $accessToken) : ResponseMessage;  
    public function getUserConnectionById(int $connection_id, string $accessToken) : ResponseMessage;
    public function deleteUserConnection(int $connection_id, string $accessToken) : ResponseMessage;    
}

?>