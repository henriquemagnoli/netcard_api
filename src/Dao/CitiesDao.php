<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface CitiesDao
{
    public function getAllCities(int $stateId) : ResponseMessage;
}

?>