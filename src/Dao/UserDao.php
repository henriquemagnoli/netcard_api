<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface UserDao
{
    // USER
    public function getUser(int $user_id) : ResponseMessage;
    public function updateUser(object $request_body, string $accessToken) : ResponseMessage;
    public function setUserVisible(object $request_body, string $accessToken) : ResponseMessage;    
}
?>