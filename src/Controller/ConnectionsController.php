<?php

namespace Netcard\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use Exception;
use Netcard\Model\ResponseMessage;
use Netcard\Dao\impl\ConnectionsImpl;

class ConnectionsController
{
    public function getAllUserConnections(Request $request, Response $response) : Response
    {
        try
        {
            $get_all_user_connections = new ConnectionsImpl();

            $response_message = $get_all_user_connections->getAllUserConnections($request->getQueryParams(), $request->getHeader("HTTP_AUTHORIZATION")[0]);

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
            $response_message->buildMessage(500, false, ['Ocorreu um erro ao listar todas as suas conexões: ' . $ex->getMessage()], null);
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
    }

    public function addUserConnection(Request $request, Response $response, array $args) : Response
    {
        try
        {
            $add_user_connection = new ConnectionsImpl();

            $response_message = $add_user_connection->addUserConnection($request->getBody(), $request->getHeader("HTTP_AUTHORIZATION")[0]);

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
            $response_message->buildMessage(500, false, ['Ocorreu um erro ao se conectar a outro usuário: ' . $ex->getMessage()], null);
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
    }
   
    public function getUserConnectionById(Request $request, Response $response, array $args) : Response
    {
        try
        {
            $get_user_connection_by_id = new ConnectionsImpl();

            $response_message = $get_user_connection_by_id->getUserConnectionById($args['id'], $request->getHeader("HTTP_AUTHORIZATION")[0]);

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
            $response_message->buildMessage(500, false, ['Ocorreu um erro ao listar a conexão pelo Id: ' . $ex->getMessage()], null);
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
    }

    public function deleteUserConnection(Request $request, Response $response, array $args) : Response
    {
        try
        {
            $delete_user_connection = new ConnectionsImpl();

            $response_message = $delete_user_connection->deleteUserConnection($args['id'], $request->getHeader("HTTP_AUTHORIZATION")[0]);

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
            $response_message->buildMessage(500, false, ['Ocorreu um erro ao listar a conexão pelo Id: ' . $ex->getMessage()], null);
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
    }
}

?>