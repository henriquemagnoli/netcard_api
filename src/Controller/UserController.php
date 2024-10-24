<?php

namespace Netcard\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use Exception;
use Netcard\Model\ResponseMessage;
use Netcard\Dao\Impl\UserImpl;

class UserController
{
    public function getUser(Request $request, Response $response, array $args) : Response
    {
        try
        {
            $get_user = new UserImpl();

            $response_message = $get_user->getUser($args['id']);

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
            $response_message->buildMessage(500, false, ['Ocorreu um erro ao retornar seus dados: ' . $ex->getMessage()], null);
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
    }

    public function updateUser(Request $request, Response $response) : Response
    {
        try
        {
            $update_user = new UserImpl();

            $response_message = $update_user->updateUser($request->getBody(), $request->getHeader("HTTP_AUTHORIZATION")[0]);

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

    public function updateUserVisible(Request $request, Response $response) : Response
    {
        try
        {
            $update_user_visible = new UserImpl();

            $response_message = $update_user_visible->updateUserVisible($request->getBody(), $request->getHeader("HTTP_AUTHORIZATION")[0]);
            
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
        catch(PDOException $ex)
        {
            $response_message = new ResponseMessage();
            $response_message->buildMessage(500, false, ['Ocorreu um erro na conexão com o servidor.' . $ex->getMessage()], null);
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