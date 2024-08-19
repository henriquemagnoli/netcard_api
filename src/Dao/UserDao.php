<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface UserDao
{
    public function addUser(object $request_body) : ResponseMessage;
}

?>