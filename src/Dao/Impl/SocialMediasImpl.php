<?php

namespace Netcard\Dao\Impl;

use Exception;
use Netcard\Dao\SocialMediasDao;
use NetCard\Database\Connection;
use Netcard\Helper\HelperSocialMedias;
use Netcard\Model\ResponseMessage;
use PDOException;
use PDO;

class SocialMediasImpl implements SocialMediasDao 
{
    public function getAllSocialMedias(): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $connection = Connection::openConnection();
            $command = $connection->prepare(HelperSocialMedias::selectAllSocialMedias());
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(404, false, ['Não foi possível localizar nenhuma rede social.'], null);
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