<?php

namespace Netcard\Dao\Impl;

use Exception;
use Netcard\Dao\UserDao;
use Netcard\Model\ResponseMessage;
use PDOException;
use Netcard\Database\Connection;
use Netcard\Helper\HelperUser;

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

            $password = password_hash($json_data->password, PASSWORD_DEFAULT);
            $streetComplement = (!isset($json_data->address->streetComplement) ? NULL : $json_data->address->streetComplement);

            $command = $connection->prepare(HelperUser::insertUser());
            $command->bindParam(':name', $json_data->name);
            $command->bindParam(':password', $password);
            $command->bindParam(':email', $json_data->email);
            $command->bindParam(':cpf', $json_data->cpf);
            $command->bindParam(':profilePicture', $json_data->profilePicture);
            $command->bindParam(':sex', $json_data->sex);
            $command->bindParam(':birthDate', $json_data->birthDate);
            $command->bindParam(':street', $json_data->address->street);
            $command->bindParam(':streetNumber', $json_data->address->streetNumber);
            $command->bindParam(':cityId', $json_data->address->cityId);
            $command->bindParam(':streetComplement', $streetComplement);
            $command->bindParam(':district', $json_data->address->district);
            $command->bindParam(':zipCode',$json_data->address->zipCode);
            $command->bindParam(':jobId', $json_data->jobId);
            $command->execute();

            $user_id = $connection->lastInsertId();

            $command = $connection->prepare(HelperUser::insertLogin());
            $command->bindParam(':user_id', $user_id);
            $command->execute();    

            $connection->commit();

            $response_message->setSuccess(true);
            $response_message->setHttpStatusCode(200);
            $response_message->setMessages("Conta cadastrada com sucesso.");

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