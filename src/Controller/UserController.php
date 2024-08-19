<?php

namespace NetCard\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use Exception;
use NetCard\Model\ResponseMessage;
use NetCard\Dao\Impl\UserImpl;

class UserController
{
    public function addUser(Request $request, Response $response) : Response
    {
        try
        {
            $add_user = new UserImpl();

            $response_message = $add_user->addUser($request->getBody());

            $response->getBody()->write(json_encode($response_message->send()));    
            return $response;
        }
        catch(PDOException $ex)
        {
            $response_message = new ResponseMessage();
            $response_message->setSuccess(false);
            $response_message->setHttpStatusCode(500);
            $response_message->setMessages("Ocorreu um erro na conexão com o servidor.");

            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
        catch(Exception $ex)
        {
            $response_message = new ResponseMessage();
            $response_message->setSuccess(false);
            $response_message->setHttpStatusCode(empty($ex->getCode()) ? 500 : $ex->getCode());
            $response_message->setMessages(empty($ex->getMessage()) ? "Ocorreu um erro ao se cadastrar." : $ex->getMessage());

            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
    }
}

?>