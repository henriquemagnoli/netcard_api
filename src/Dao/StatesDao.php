<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface StatesDao
{
    public function getAllStates() : ResponseMessage;
}

?>