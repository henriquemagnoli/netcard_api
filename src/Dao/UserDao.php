<?php

namespace NetCard\Dao;

use NetCard\Model\ResponseMessage;

interface UserDao
{
    public function addUser(object $request_body) : ResponseMessage;
}

?>