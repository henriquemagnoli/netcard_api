<?php

namespace NetCard\Dao\Impl;

use Exception;
use NetCard\Dao\UserDao;
use NetCard\Model\ResponseMessage;
use PDOException;
use NetCard\Database\Connection;
use NetCard\Helper\HelperUser;

class UserImpl implements UserDao
{
    public function addUser(object $request_body): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            if(!$json_data = json_decode(strval($request_body)))
                throw new Exception('Corpo da requisição não é um JSON válido.', 400);

            // Test body
            if(!isset($json_data->name))
                throw new Exception('Nome deve ser preenchido.', 400);

            if(!isset($json_data->password))
                throw new Exception('Senha deve ser preenchida.', 400);

            if(!isset($json_data->email))
                throw new Exception('E-mail deve ser preenchido.', 400);

            if(!isset($json_data->cpf))
                throw new Exception('Cpf deve ser preenchido.', 400);

            if(!isset($json_data->profilePicture))
                throw new Exception('Foto deve ser inserida.', 400);

            if(!isset($json_data->sex))
                throw new Exception('Sexo ser preenchido.', 400);

            if(!isset($json_data->birthDate))
                throw new Exception('Data de nascimento deve ser preenchido.', 400);

            if(!isset($json_data->address))
            {
                throw new Exception('Endereço deve ser preenchido.', 400);
            }
            else
            {
                if(!isset($json_data->address->street))
                    throw new Exception('Rua deve ser preenchida.', 400);

                if(!isset($json_data->address->streetNumber))
                    throw new Exception('Número da residência deve ser preenchida.', 400);

                if(!isset($json_data->address->cityId))
                    throw new Exception('Cidade deve ser preenchida.', 400);

                if(!isset($json_data->address->district))
                    throw new Exception('Bairro deve ser preenchido.', 400);

                if(!isset($json_data->address->zipCode))
                    throw new Exception('CEP deve ser preenchido.', 400);
            }
            
            if(!isset($json_data->jobId))
                throw new Exception('Profissão deve ser preenchida.', 400);
           
            $connection = Connection::openConnection();

            $connection->beginTransaction();

            $command = $connection->prepare(HelperUser::insertUser());
            $command->bindParam(':name', );
            $command->execute();

            $user_id = $connection->lastInsertId();

            $command = $connection->prepare(HelperUser::insertLogin());
            $command->execute();    

            $connection->commit();

            $response_message->setSuccess(true);
            $response_message->setHttpStatusCode(200);

            return $response_message;
        }
        catch(PDOException $ex)
        {
            throw $ex;
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }
}

?>