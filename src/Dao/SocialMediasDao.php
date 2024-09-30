<?php

namespace Netcard\Dao;

use Netcard\Model\ResponseMessage;

interface SocialMediasDao
{
    public function getAllSocialMedias() : ResponseMessage;
}

?>