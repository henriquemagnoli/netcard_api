<?php

namespace Netcard\Helper;

class HelperConnections
{
    public static function createSqlUserParams(array $query_params) : string
    {
        $sql_params = "";

        if(array_key_exists('name', $query_params))
            $sql_params = " AND (U.Name = '" . $query_params['name'] . "')";

        if(array_key_exists('job', $query_params))
            $sql_params = " AND (U.Job_id = " . $query_params['job'] . ")";

        if(array_key_exists('sex', $query_params))
            $sql_params = " AND (U.Sex = '" . $query_params['sex'] . "')";

        if(array_key_exists('state', $query_params))
            $sql_params = " AND (C.State_id = " . $query_params['state'] . ")";

        if(array_key_exists('city', $query_params))
            $sql_params = " AND (U.City_id = " . $query_params['city'] . ")";

        return $sql_params;
    }

    public static function selectAllUserConnections() : string
    {
        return "SELECT U.Id, 
                       U.Name AS UserName, 
                       U.Birth_date,
                       U.Profile_picture, 
                       J.Name AS JobName
                    FROM tb_users_connections AS UC 
                    INNER JOIN tb_users AS U ON UC.Connection_id = U.Id
                    INNER JOIN tb_jobs AS J ON U.Job_id = J.Id
                    INNER JOIN tb_cities AS C ON U.City_id = C.Id
                    INNER JOIN tb_states AS S ON C.State_id = S.Id
                    WHERE UC.Master_id = :user_id";
    }

    public static function insertUserConnection() : string
    {
        return "INSERT INTO tb_users_connections (Master_id, User_id) VALUES (:masterId, :userId);";
    }

    public static function deleteUserConnection() : string
    {
        return "DELETE FROM tb_users_connections WHERE Master_id = :userId AND Connection_id = :connectionId;";
    }

    public static function selectUserConnectionById() : string
    {
        return "SELECT U.Id, 
                       U.Name, 
                       U.Email, 
                       U.Profile_picture, 
                       U.Sex, 
                       U.Street, 
                       U.Street_number, 
                       U.City_id, 
                       U.Street_complement, 
                       U.District, 
                       U.Biography,
                       J.Name
                FROM tb_users_connections AS UC
                INNER JOIN tb_users AS U ON UC.Connection_id = U.Id
                INNER JOIN tb_jobs AS J ON U.Job_id = J.Id
                WHERE UC.Connection_id = :connectionId AND UC.Master_id = :masterId";
    }
}

?>