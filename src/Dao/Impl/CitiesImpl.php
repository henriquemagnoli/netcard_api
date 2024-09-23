<?php

namespace Netcard\Dao\Impl;

use Exception;
use Netcard\Dao\CitiesDao;
use Netcard\Model\ResponseMessage;
use PDOException;
use Netcard\Database\Connection;
use Netcard\Helper\HelperCities;
use PDO;

class CitiesImpl implements CitiesDao
{
    public function getAllCities(int $stateId): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            if(empty($stateId))
            {
                $response_message->buildMessage(400, false, ['O id do estado deve ser inserido.'], null);
                return $response_message;
            }

            $connection = Connection::openConnection();

            $command = $connection->prepare(HelperCities::selectCitiesBasedOnStateId());
            $command->bindParam('stateId', $stateId);
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(404, false, ['Não foi possível localizar nenhuma cidade nesse estado.'], null);
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
}

?>