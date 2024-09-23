<?php

namespace Netcard\Helper;

class HelperCities
{
    public static function selectCitiesBasedOnStateId() : string
    {
        return "SELECT Id, Name, State_id FROM tb_cities WHERE State_id = :stateId;";
    }
}

?>