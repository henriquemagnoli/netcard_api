<?php

namespace Netcard\Helper;

class HelperCoordinates
{
    public static function selectUserCoordinates() : string
    {
        return "SELECT UC.Id, UC.User_id, U.Name AS User_name, U.Birth_date, U.Profile_picture, J.Name AS Job_name, UC.Latitude, UC.Longitude FROM tb_users_coordinates AS UC, tb_users AS U, tb_jobs AS J
                WHERE (UC.User_id = U.Id) AND (U.Job_id = J.Id); ";
    }

    public static function insertUserCoordinate() : string
    {
        return "INSERT INTO tb_users_coordinates (User_id,
                                                  Latitude,
                                                  Longitude)
                                          VALUES (:user_id,
                                                  :latitude,
                                                  :longitude);";
    }

    public static function deleteUserCoordinate() : string
    {
        return "DELETE FROM tb_users_coordinates WHERE User_id = :userId;";
    }

    public static function updateUserCoordinate() : string
    {
        return "UPDATE tb_users_coordinates SET Latitude = :latitude, Longitude = :longitude WHERE User_id = :userId;";
    }
}

?>