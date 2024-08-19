<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface LoginDao
{
    public function login(object $request_body) : ResponseMessage;
}

?>