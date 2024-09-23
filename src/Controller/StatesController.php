<?php

namespace Netcard\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use Exception;
use Netcard\Model\ResponseMessage;
use Netcard\Dao\Impl\StatesImpl;

class StatesController
{
    public function getAllStates(Request $request, Response $response) : Response
    {
        try
        {
            $get_all_states = new StatesImpl();

            $response_message = $get_all_states->getAllStates();

            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
        catch(PDOException $ex)
        {
            $response_message = new ResponseMessage();
            $response_message->buildMessage(500, false, ['Ocorreu um erro na conexão com o servidor.'], null);
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
        catch(Exception $ex)
        {
            $response_message = new ResponseMessage();
            $response_message->buildMessage(500, false, ['Ocorreu um erro ao se cadastrar: ' . $ex->getMessage()], null);
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
    }
}

?>