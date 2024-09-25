<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface JobsDao
{
    public function getAllJobs() : ResponseMessage;
} 

?>