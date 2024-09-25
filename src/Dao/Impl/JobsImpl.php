<?php

namespace Netcard\Dao\Impl;

use Exception;
use Netcard\Dao\JobsDao;
use Netcard\Model\ResponseMessage;
use PDOException;
use Netcard\Database\Connection;
use Netcard\Helper\HelperJobs;
use PDO;

class JobsImpl implements JobsDao
{
    public function getAllJobs(): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $connection = Connection::openConnection();

            $command = $connection->prepare(HelperJobs::selectAllJobs());
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(404, false, ['Não foi possível localizar nenhuma profissão.'], null);
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