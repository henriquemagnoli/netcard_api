<?php

namespace Netcard\Middleware;

// Slim classes to receive and return data

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Server\MiddlewareInterface;
use Netcard\Model\ResponseMessage;
use Netcard\Database\Connection;
use PDO;

class AuthToken implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        try
        {
            $response_factory = new ResponseFactory();
            $response_message = new ResponseMessage();

            if(empty($request->getHeader("HTTP_AUTHORIZATION")))
            {
                $response_message->buildMessage(404, false, ["Cabeçalho da autorização não foi definido."], null); 
                $response = $response_factory->createResponse();
                $response->getBody()->write(json_encode($response_message->send()));
                return $response;
            }

            if(empty($request->getHeader("HTTP_AUTHORIZATION")[0]))
            {
                $response_message->buildMessage(400, false, ["Token de acesso não foi preenchido."], null); 
                $response = $response_factory->createResponse();
                $response->getBody()->write(json_encode($response_message->send()));
                return $response;
            }

            $decoded = JWT::decode($request->getHeader("HTTP_AUTHORIZATION")[0], new Key($_ENV['SECRET_KEY'], 'HS256'));

            $connection = Connection::openConnection();
    
            $command = $connection->prepare("SELECT Id FROM tb_users WHERE Id = :id;");
            $command->bindParam(':id', $decoded->id, PDO::PARAM_INT);
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(401, false, ["Token de acesso é inválido."], null); 
                $response = $response_factory->createResponse();
                $response->getBody()->write(json_encode($response_message->send()));
                return $response;
            }

            $response = $handler->handle($request);
            return $response;
        }
        catch(Exception $ex)
        {
            $response_message = new ResponseMessage();

            $response_message->buildMessage($ex->getCode(), false, ['Ocorreu um erro ao validar o JWT.'], null);
            $response = $response_factory->createResponse();
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
        catch(InvalidArgumentException $ex)
        {
            $response_message = new ResponseMessage();

            $response_message->buildMessage($ex->getCode(), false, [$ex->getMessage()], null);
            $response = $response_factory->createResponse();
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
        catch(ExpiredException $ex)
        {
            $response_message = new ResponseMessage();

            $response_message->buildMessage($ex->getCode(), false, [$ex->getMessage()], null);
            $response = $response_factory->createResponse();
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
        catch(SignatureInvalidException $ex)
        {
            $response_message = new ResponseMessage();

            $response_message->buildMessage($ex->getCode(), false, [$ex->getMessage()], null);
            $response = $response_factory->createResponse();
            $response->getBody()->write(json_encode($response_message->send()));
            return $response;
        }
        finally
        {
            $connection = Connection::closeConnection();
        }

    }
}

?>