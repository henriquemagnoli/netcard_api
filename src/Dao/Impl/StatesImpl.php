<?php

namespace Netcard\Dao\Impl;

use Exception;
use Netcard\Helper\HelperStates;
use Netcard\Dao\StatesDao;
use Netcard\Model\ResponseMessage;
use PDOException;
use Netcard\Database\Connection;
use PDO;

class StatesImpl implements StatesDao
{
    public function getAllStates(): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $connection = Connection::openConnection();

            $command = $connection->prepare(HelperStates::selectAllStates());
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(404, false, ['Não foi possível localizar nenhum estado.'], null);
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