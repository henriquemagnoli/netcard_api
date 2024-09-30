<?php

namespace Netcard\Helper;

class HelperSocialMedias
{
    public static function selectAllSocialMedias() : string
    {
        return "SELECT Id, Name FROM tb_social_media;"; 
    }
}

?>