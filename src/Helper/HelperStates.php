<?php

class HelperStates
{
    public static function selectAllStates() : string
    {
        return "SELECT Id, Name, Acronym  FROM tb_states;";
    }
}

?>