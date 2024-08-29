<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface UserDao
{
    public function addUser(object $request_body) : ResponseMessage;
    public function updateUser(object $request_body, int $user_id) : ResponseMessage;
}

?>