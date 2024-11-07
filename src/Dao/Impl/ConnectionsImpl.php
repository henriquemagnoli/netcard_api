<?php

namespace Netcard\Dao\Impl;

use Exception;
use Netcard\Dao\ConnectionsDao;
use Netcard\Model\ResponseMessage;
use PDOException;
use Netcard\Database\Connection;
use Netcard\Helper\Helper;
use Netcard\Helper\HelperConnections;
use PDO;

class ConnectionsImpl implements ConnectionsDao
{
    public function getAllUserConnections(array $query_params, string $accessToken): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $user_id = Helper::getJWTData($accessToken);   

            $connection = Connection::openConnection();        
            $sql_params = HelperConnections::createSqlUserParams($query_params);

            $command = $connection->prepare(HelperConnections::selectAllUserConnections() . $sql_params);
            $command->bindParam(':user_id', $user_id->id, PDO::PARAM_INT);
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(400, false, ['Nenhum registro foi encontrado.'], null);
                return $response_message;
            }

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
   
    public function addUserConnection(object $request_body, string $accessToken) : ResponseMessage
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
            
            $user_id = Helper::getJWTData($accessToken);
        
            $connection = Connection::openConnection();
            $connection->beginTransaction();

            $command = $connection->prepare(HelperConnections::insertUserConnection());
            $command->bindParam(':masterId', $user_id->id);
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

    public function getUserConnectionById(int $connection_id, string $accessToken): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $user_id = Helper::getJWTData($accessToken);

            $connection = Connection::openConnection();
            
            $command = $connection->prepare(HelperConnections::selectUserConnectionById());
            $command->bindParam(':connectionId', $connection_id, PDO::PARAM_INT);
            $command->bindParam(':id', $user_id->id, PDO::PARAM_INT);
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(404, false, ['Nenhum registro foi encontrado.'], null);
                return $response_message;
            }

            $user_data = $command->fetch(PDO::FETCH_ASSOC);

            $command = $connection->prepare(HelperConnections::selectUserSocialMediaById());
            $command->bindParam(':connectionId', $connection_id, PDO::PARAM_INT);
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

    public function deleteUserConnection(int $connection_id, string $accessToken): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $user_id = Helper::getJWTData($accessToken);

            $connection = Connection::openConnection();
            $connection->beginTransaction();

            $command = $connection->prepare(HelperConnections::deleteUserConnection());
            $command->bindParam(':userId', $user_id->id, PDO::PARAM_INT);
            $command->bindParam(':connectionId', $connection_id, PDO::PARAM_INT);
            $command->execute();

            $connection->commit();

            $response_message->buildMessage(200, true, ['Conexão rompida com sucesso.'], null);
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