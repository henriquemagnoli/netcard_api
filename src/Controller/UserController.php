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

    public function updateUser(Request $request, Response $response, array $args) : Response
    {
        try
        {
            $update_user = new UserImpl();

            $response_message = $update_user->updateUser($request->getBody(), $args['id']);

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

    public function addUserConnection(Request $request, Response $response, array $args) : Response
    {
        try
        {
            $add_user_connection = new UserImpl();

            $response_message = $add_user_connection->addUserConnection($request->getBody(), $args['id']);

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

    public function getAllUserConnections(Request $request, Response $response, array $args) : Response
    {
        try
        {
            $get_all_user_connections = new UserImpl();

            $response_message = $get_all_user_connections->getAllUserConnections($args['id'], $request->getQueryParams());

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
        }
    }

    public function getUserConnectionById(Request $request, Response $response, array $args) : Response
    {
        try
        {
            $get_user_connection_by_id = new UserImpl();

            $response_message = $get_user_connection_by_id->getUserConnectionById($args['id'], $args['connectionId']);

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

    public function addUserCoordinate(Request $request, Response $response, array $args) : Response
    {
        try
        {
            $add_user_coordinate = new UserImpl();

            $response_message = $add_user_coordinate->addUserCoordinate($args['id'], $request->getBody());

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
            $response_message->buildMessage(500, false, ['Ocorreu um erro ao listar a conexão pelo Id: ' . $ex->getMessage()], null);
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
    }

    public function getAllCoordinates(Request $request, Response $response) : Response
    {
        try
        {
            $get_all_coordinates = new UserImpl();

            $response_message = $get_all_coordinates->getAllCoordinates();

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