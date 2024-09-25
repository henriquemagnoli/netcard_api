<?php

namespace NetCard\Helper;

class HelperUser
{
    // SQL Functions
    public static function insertUser() : string
    {
        return "INSERT INTO tb_users (Name, 
                                      Password, 
                                      Email,
                                      Cpf, 
                                      Profile_picture, 
                                      Sex, 
                                      Birth_date, 
                                      Street, 
                                      Street_number, 
                                      City_id, 
                                      Street_complement, 
                                      District, 
                                      Zip_code, 
                                      Job_id)
                              VALUES (:name,
                                      :password,
                                      :email,
                                      :cpf,
                                      :profilePicture,
                                      :sex,
                                      :birthDate,
                                      :street,
                                      :streetNumber,
                                      :cityId,
                                      :streetComplement,
                                      :district,
                                      :zipCode,
                                      :jobId);";
    }

    public static function updateUser() : string
    {
        return "UPDATE tb_users SET Name = :name,
                                    Profile_picture = :profilePicture,
                                    Sex = :sex,
                                    Street = :street,
                                    Street_number = :streetNumber,
                                    City_id = :cityId,
                                    Street_complement = :streetComplement,
                                    District = :district,
                                    Zip_code = :zipCode,
                                    Biography = :biography,
                                    Job_id = :jobId
                              WHERE Id = :userId;";
    }

    public static function selectUser() : string
    {
        return "SELECT U.Id, 
                       U.Name AS UserName, 
                       U.Email, 
                       U.Cpf,
                       U.Birth_date,
                       U.Profile_picture, 
                       U.Sex, 
                       U.Street, 
                       U.Street_number, 
                       U.City_id, 
                       U.Street_complement, 
                       U.District, 
                       U.Zip_code,
                       U.Biography,
                       J.Name AS JobName
                    FROM tb_users AS U
                    INNER JOIN tb_jobs AS J ON U.Job_id = J.Id
                    WHERE U.Id = :userId";
    }

    public static function insertLogin() : string
    {
        return "INSERT INTO tb_login (User_id) VALUES (:user_id);";
    }

    public static function insertUserConnection() : string
    {
        return "INSERT INTO tb_users_connections (Master_id, User_id) VALUES (:masterId, :userId);";
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

    public static function selectUserSocialMediaById() : string
    {
        return "SELECT USM.Id, USM.Social_media_id, SM.Name, USM.Url 
                FROM tb_user_social_media AS USM
                INNER JOIN tb_social_media AS SM ON USM.Social_media_id = SM.Id
                WHERE USM.User_id = :connectionId";
    }

    public static function insertUserCoordinate() : string
    {
        return "INSERT INTO tb_user_coordinates (User_id,
                                                 Latitude,
                                                 Longitude)
                                          VALUES (:user_id,
                                                  :latitude,
                                                  :longitude);";
    }

    public static function selectUserCoordinates() : string
    {
        return "SELECT Id, User_id, Latitude, Longitude FROM tb_users_coordinates;";
    }
}

?>