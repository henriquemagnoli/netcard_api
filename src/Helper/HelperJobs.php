<?php

namespace Netcard\Helper;

class HelperJobs
{
    public static function selectAllJobs() : string
    {
        return "SELECT Id, Name FROM tb_jobs;";
    }
}

?>