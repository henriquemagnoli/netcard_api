<?php

namespace Netcard\Helper;

class HelperLogin
{
    public static function selectAllUserInfo() : string
    {
        return "SELECT L.Id AS LoginId, U.Id AS UserId, U.Name, U.Password, U.Email, L.Blocked, L.Tries, L.Max_tries, L.Show_user FROM tb_login AS L, tb_users AS U WHERE U.Email = :email;";
    }

    public static function updateUserTries(int $tries) : string
    {
        return "UPDATE tb_login SET Tries = Tries + $tries WHERE Id = :loginId;";
    }
}

?>