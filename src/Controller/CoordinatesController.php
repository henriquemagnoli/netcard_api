<?php

namespace Netcard\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use Exception;
use Netcard\Model\ResponseMessage;
use Netcard\Dao\Impl\CoordinatesImpl;

class CoordinatesController
{
    public function getAllCoordinates(Request $request, Response $response) : Response
    {
        try
        {
            $get_all_coordinates = new CoordinatesImpl();

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

    public function addUserCoordinate(Request $request, Response $response) : Response
    {
        try
        {
            $add_user_coordinate = new CoordinatesImpl();

            $response_message = $add_user_coordinate->addUserCoordinate($request->getBody(), $request->getHeader("HTTP_AUTHORIZATION")[0]);

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

    public function deleteUserCoordinate(Request $request, Response $response) : Response
    {
        try
        {
            $delete_user_coordinate = new CoordinatesImpl();

            $response_message = $delete_user_coordinate->deleteUserCoordinate($request->getHeader("HTTP_AUTHORIZATION")[0]);
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
            $response_message->buildMessage(500, false, ['Ocorreu um erro excluir a coordenada: ' . $ex->getMessage()], null);
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
    }

    public function updateUserCoordinate(Request $request, Response $response) : Response
    {
        try
        {
            $update_user_coordinate = new CoordinatesImpl();

            $response_message = $update_user_coordinate->updateUserCoordinate($request->getBody(), $request->getHeader("HTTP_AUTHORIZATION")[0]);
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
            $response_message->buildMessage(500, false, ['Ocorreu um erro alterar a coordenada: ' . $ex->getMessage()], null);
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
    }
}

?>