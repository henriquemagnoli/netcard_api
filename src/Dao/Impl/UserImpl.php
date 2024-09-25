<?php

namespace Netcard\Dao\Impl;

use Exception;
use Netcard\Dao\UserDao;
use Netcard\Model\ResponseMessage;
use PDOException;
use Netcard\Database\Connection;
use Netcard\Helper\HelperUser;
use PDO;

class UserImpl implements UserDao
{
    public function addUser(object $request_body): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            if(!$json_data = json_decode(strval($request_body)))
            {
                $response_message->buildMessage(400, false, ['Corpo da requisição não é um JSON válido.'], null);
                return $response_message;
            }

            // Test body
            if(!isset($json_data->name))
            {
                $response_message->buildMessage(400, false, ['Nome deve ser preenchido.'], null);
                return $response_message;
            }
    
            if(!isset($json_data->password))
            {
                $response_message->buildMessage(400, false, ['Senha deve ser preenchida.'], null);
                return $response_message;
            }

            if(!isset($json_data->email))
            {
                $response_message->buildMessage(400, false, ['E-mail deve ser preenchido.'], null);
                return $response_message;
            }

            if(!isset($json_data->cpf))
            {
                $response_message->buildMessage(400, false, ['Cpf deve ser preenchido.'], null);
                return $response_message;
            }

            if(!isset($json_data->profilePicture))
            {
                $response_message->buildMessage(400, false, ['Foto deve ser inserida.'], null);
                return $response_message;
            }

            if(!isset($json_data->sex))
            {
                $response_message->buildMessage(400, false, ['Sexo ser preenchido.'], null);
                return $response_message;
            }

            if(!isset($json_data->birthDate))
            {
                $response_message->buildMessage(400, false, ['Data de nascimento deve ser preenchido.'], null);
                return $response_message;
            }

            if(!isset($json_data->address))
            {   
                $response_message->buildMessage(400, false, ['Endereço deve ser preenchido.'], null);
                return $response_message;
            }
            else
            {
                if(!isset($json_data->address->street))
                {
                    $response_message->buildMessage(400, false, ['Rua deve ser preenchida.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->streetNumber))
                {
                    $response_message->buildMessage(400, false, ['Número da residência deve ser preenchida.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->cityId))
                {
                    $response_message->buildMessage(400, false, ['Cidade deve ser preenchida.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->district))
                {
                    $response_message->buildMessage(400, false, ['Bairro deve ser preenchido.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->zipCode))
                {
                    $response_message->buildMessage(400, false, ['CEP deve ser preenchido.'], null);
                    return $response_message;
                }
            }
            
            if(!isset($json_data->jobId))
            {
                $response_message->buildMessage(400, false, ['Profissão deve ser preenchida.'], null);
                return $response_message;
            }
           
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

            $response_message->buildMessage(200, true, ['Conta cadastrada com sucesso.'], null);
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

    public function updateUser(object $request_body, int $user_id) : ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            if(!$json_data = json_decode(strval($request_body)))
            {
                $response_message->buildMessage(400, false, ['Corpo da requisição não é um JSON válido.'], null);
                return $response_message;
            }

            // Test if the actual user its the user using JWT
            $connection = Connection::openConnection();
            $connection->beginTransaction();
            

            // Then validate info`s body
            if(!isset($json_data->name))
            {
                $response_message->buildMessage(400, false, ['Nome deve ser preenchido.'], null);
                return $response_message;
            }

            if(!isset($json_data->sex))
            {
                $response_message->buildMessage(400, false, ['Sexo ser preenchido.'], null);
                return $response_message;
            }

            if(!isset($json_data->address))
            {   
                $response_message->buildMessage(400, false, ['Endereço deve ser preenchido.'], null);
                return $response_message;
            }
            else
            {
                if(!isset($json_data->address->street))
                {
                    $response_message->buildMessage(400, false, ['Rua deve ser preenchida.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->streetNumber))
                {
                    $response_message->buildMessage(400, false, ['Número da residência deve ser preenchida.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->cityId))
                {
                    $response_message->buildMessage(400, false, ['Cidade deve ser preenchida.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->district))
                {
                    $response_message->buildMessage(400, false, ['Bairro deve ser preenchido.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->zipCode))
                {
                    $response_message->buildMessage(400, false, ['CEP deve ser preenchido.'], null);
                    return $response_message;
                }
            }
            
            if(!isset($json_data->jobId))
            {
                $response_message->buildMessage(400, false, ['Profissão deve ser preenchida.'], null);
                return $response_message;
            }

            // Update User
            $command = $connection->prepare(HelperUser::updateUser());
            $command->bindParam(':name', $json_data->name);

            $profile_picture = (isset($json_data->profilePicture) ? $json_data->profilePicture : null);
            $command->bindParam(':profilePicture', $profile_picture);

            $command->bindParam(':sex', $json_data->sex);
            $command->bindParam(':street', $json_data->address->street);
            $command->bindParam(':streetNumber', $json_data->address->streetNumber);
            $command->bindParam(':cityId', $json_data->address->cityId);

            $street_complement = (isset($json_data->streetComplement) ? $json_data->streetComplement : null);
            $command->bindParam(':streetComplement', $street_complement);

            $command->bindParam(':district', $json_data->address->district);
            $command->bindParam(':zipCode', $json_data->address->zipCode);

            $biography = (isset($json_data->biography) ? $json_data->biography : null);
            $command->bindParam(':biography', $biography);

            $command->bindParam(':jobId', $json_data->jobId);
            $command->bindParam(':userId', $user_id); 
            $command->execute();

            $connection->commit();

            $response_message->buildMessage(200, true, ['Alteração realizada com sucesso.'], null);
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
        finally
        {
            $connection = Connection::closeConnection();
        }
    }

    public function getUser(int $user_id) : ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $connection = Connection::openConnection();

            // First query will fetch user datas 
            $command = $connection->prepare(HelperUser::selectUser());
            $command->bindParam(':userId', $user_id);
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(400, false, ['Id inserido não encontrado.'], null);
                return $response_message;
            }

            $user_data = $command->fetch(PDO::FETCH_ASSOC);

            // Second query will fetch user social medias
            $command = $connection->prepare(HelperUser::selectUserSocialMediaById());
            $command->bindParam(':connectionId', $user_id);
            $command->execute();

            $user_social_media = $command->fetchAll(PDO::FETCH_ASSOC);

            $user_data['User_social_media'] = $user_social_media;

            $response_message->buildMessage(200, true, null, $user_data);
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
        finally
        {
            $connection = Connection::closeConnection();
        }
    }

    public function addUserConnection(object $request_body, int $user_id) : ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            if(!$json_data = json_decode(strval($request_body)))
            {
                $response_message->buildMessage(400, false, ['Corpo da requisição não é um JSON válido.'], null);
                return $response_message;
            }

            if(!isset($json_data->connectionId))
            {
                $response_message->buildMessage(400, false, ['O campo de id do usuário a ser adicionado deve ser preenchido.'], null);
                return $response_message;
            }
            else if(empty($json_data->connectionId))
            {
                $response_message->buildMessage(400, false, ['Id do usuário a ser adicionado não poder ser vazio.'], null);
                return $response_message;
            }
            else if(!is_numeric($json_data->connectionId))
            {   
                $response_message->buildMessage(400, false, ['Id do usuário a ser adicionado deve ser numérico.'], null);
                return $response_message;
            }
        
            $connection = Connection::openConnection();
            $connection->beginTransaction();

            $command = $connection->prepare(HelperUser::insertUserConnection());
            $command->bindParam(':masterId', $user_id);
            $command->bindParam(':userId', $json_data->connectionId);
            $command->execute();

            $connection->commit();

            $response_message->buildMessage(200, true, ['Conexão realizada com sucesso.'], null);
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
        finally
        {
            $connection = Connection::closeConnection();
        }
    }

    public function getUserConnectionById(int $user_id, int $connection_id): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $connection = Connection::openConnection();

            // First query needs to be testing if the connection id exists

            // Second query needs to be testing if the connection id belongs to the user id
            
            // Third query needs to get the user infos

            // Fourth query needs to get the user social media infos

            $command = $connection->prepare();

            $response_message->buildMessage(200, true, null, null);
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
        finally
        {
            $connection = Connection::closeConnection();
        }
    }

    public function addUserCoordinate(int $user_id, object $request_body): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            if(!$json_data = json_decode(strval($request_body)))
            {
                $response_message->buildMessage(400, false, ['Corpo da requisição não é um JSON válido.'], null);
                return $response_message;
            }

            if(!isset($json_data->latitude))
            {
                $response_message->buildMessage(400, false, ['Latitude não pode ser vazia.'], null);
                return $response_message;
            }

            if(!isset($json_data->longitude))
            {
                $response_message->buildMessage(400, false, ['Longitude não pode ser vazia.'], null);
                return $response_message;
            }

            $connection = Connection::openConnection();
            $connection->beginTransaction();

            $command = $connection->prepare(HelperUser::insertUserCoordinate());
            $command->bindParam(':user_id', $user_id);
            $command->bindParam(':latitude', $json_data->latitude);
            $command->bindParam(':longitude', $json_data->longitude);
            $command->execute();

            $connection->commit();

            $response_message->buildMessage(200, true, ['Suas coordenadas foram adicionadas.'], null);
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
        finally
        {
            $connection = Connection::closeConnection();
        }
    }

    public function getAllCoordinates(): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $connection = Connection::openConnection();
            
            $command = $connection->prepare(HelperUser::selectUserCoordinates());
            $command->execute();

            $data = $command->fetchAll(PDO::FETCH_ASSOC);

            $response_message->buildMessage(200, true, null, $data);
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
        finally
        {
            $connection = Connection::closeConnection();
        }
    }
}

?>