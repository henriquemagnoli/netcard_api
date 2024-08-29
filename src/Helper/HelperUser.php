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
        return "SELECT ";
    }
}

?>