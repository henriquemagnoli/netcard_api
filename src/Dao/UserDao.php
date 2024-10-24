<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface UserDao
{
    public function getUser(int $user_id) : ResponseMessage;
    public function updateUser(object $request_body, string $accessToken) : ResponseMessage;
    public function updateUserVisible(object $request_body, string $accessToken) : ResponseMessage;    
}
?>