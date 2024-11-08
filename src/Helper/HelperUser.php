<?php

namespace NetCard\Helper;

class HelperUser
{

    /* ===== USER ===== */
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
                       S.Id AS StateId,
                       U.City_id, 
                       U.Street_complement, 
                       U.District, 
                       U.Zip_code,
                       U.Biography,
                       J.Name AS JobName,
                       J.Id AS JobId
                    FROM tb_users AS U
                    INNER JOIN tb_jobs AS J ON U.Job_id = J.Id
                    INNER JOIN tb_cities AS C ON U.City_id = C.Id
                    INNER JOIN tb_states AS S ON C.State_id = S.Id
                    WHERE U.Id = :userId";
    }

    public static function insertLogin() : string
    {
        return "INSERT INTO tb_login (User_id) VALUES (:user_id);";
    }

    /* ===== USER SOCIAL MEDIA ===== */
    public static function selectSocialMediaByUserId() : string
    {
        return "SELECT USM.Id, USM.Social_media_id, SM.Name, USM.Url 
                FROM tb_user_social_media AS USM
                INNER JOIN tb_social_media AS SM ON USM.Social_media_id = SM.Id
                WHERE USM.User_id = :userId";
    }

    public static function insertUserSocialMedia() : string
    {
        return "INSERT INTO tb_user_social_media (User_id, Social_media_id, Url) VALUES (:userId, :socialMediaId, :url);";
    }

    public static function selectUserSocialMediaById() : string
    {
        return "SELECT Id, User_id, Social_media_id, Url FROM tb_user_social_media WHERE Id = :id AND User_id = :userId;";
    }

    public static function updateUserSocialMedia() : string
    {
        return "UPDATE tb_user_social_media SET Url = :url WHERE Id = :id AND User_id = :userId;";
    }

    public static function deleteUserSocialMedia() : string
    {
        return "DELETE FROM tb_user_social_media WHERE Id = :id AND User_id = :userId;";
    }

    /* ===== USER VISIBLE ===== */
    public static function updateShowUser() : string
    {
        return "UPDATE tb_login SET Show_user = :visible WHERE User_id = :id";
    }

    public static function selectShowUser() : string
    {
        return "SELECT Show_user FROM tb_login WHERE User_id = :id;";
    }
}

?>