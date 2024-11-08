<?php

namespace Netcard\Dao\Impl;

use Exception;
use Netcard\Dao\CoordinatesDao;
use Netcard\Model\ResponseMessage;
use PDOException;
use Netcard\Database\Connection;
use Netcard\Helper\Helper;
use Netcard\Helper\HelperCoordinates;
use PDO;

class CoordinatesImpl implements CoordinatesDao
{
    public function getAllCoordinates(): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $connection = Connection::openConnection();
            
            $command = $connection->prepare(HelperCoordinates::selectUserCoordinates());
            $command->execute();

            $data = $command->fetchAll(PDO::FETCH_ASSOC);

            $returned_data = array();

            foreach ($data as $key => $value) {
                $returned_data[$key]['Id'] = $value['Id'];
                $returned_data[$key]['User_id'] = $value['User_id'];
                $returned_data[$key]['User_name'] = $value['User_name'];
                $returned_data[$key]['Profile_picture'] = $value['Profile_picture'];
                $returned_data[$key]['Birth_date'] = $value['Birth_date'];
                $returned_data[$key]['Job_name'] = $value['Job_name'];
                $returned_data[$key]['Coordinates'] = array('lat' => floatval($value['Latitude']), 'lng' => floatval($value['Longitude']));
            }

            $response_message->buildMessage(200, true, null, $returned_data);
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

    public function addUserCoordinate(object $request_body, string $accessToken): ResponseMessage
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

            $user_id = Helper::getJWTData($accessToken);

            $connection = Connection::openConnection();
            $connection->beginTransaction();

            $command = $connection->prepare(HelperCoordinates::insertUserCoordinate());
            $command->bindParam(':user_id', $user_id->id);
            $command->bindParam(':latitude', $json_data->latitude);
            $command->bindParam(':longitude', $json_data->longitude);
            $command->execute();

            $connection->commit();

            $response_message->buildMessage(200, true, ['Suas coordenadas foram adicionadas.'], null);
            return $response_message;
        }
        catch(PDOException $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        catch(Exception $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        finally
        {
            $connection = Connection::closeConnection();
        }
    }

    public function deleteUserCoordinate(string $accessToken): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $user_id = Helper::getJWTData($accessToken);

            $connection = Connection::openConnection();
            $connection->beginTransaction();

            $command = $connection->prepare(HelperCoordinates::deleteUserCoordinate());
            $command->bindParam(':userId', $user_id->id, PDO::PARAM_INT);
            $command->execute();

            $connection->commit();

            $response_message->buildMessage(200, true, ['Coordenadas do usuário foram exclídas.'], null);
            return $response_message;
        }
        catch(PDOException $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        catch(Exception $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        finally
        {
            $connection = Connection::closeConnection();
        }
    }

    public function updateUserCoordinate(object $request_body, string $accessToken): ResponseMessage
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

            $user_id = Helper::getJWTData($accessToken);

            $connection = Connection::openConnection();
            $connection->beginTransaction();
            
            $command = $connection->prepare(HelperCoordinates::updateUserCoordinate());
            $command->bindParam(':latitude', $json_data->latitude);
            $command->bindParam(':longitude', $json_data->longitude);
            $command->bindParam(':userId', $user_id->id, PDO::PARAM_INT);
            $command->execute();

            $connection->commit();

            $response_message->buildMessage(200, true, ['Coordenadas foram alteradas.'], null);
            return $response_message;
        }
        catch(PDOException $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        catch(Exception $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        finally
        {
            $connection = Connection::closeConnection();
        }
    }
}

?>