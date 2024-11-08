<?php
// php -S localhost:8000 -t public

//https://napoleon.com.br/glossario/o-que-e-jwt-secret-key/#:~:text=Trata%2Dse%20de%20uma%20chave,para%20autenticar%20e%20autorizar%20usu%C3%A1rios.
//https://stackoverflow.com/questions/31309759/what-is-secret-key-for-jwt-based-authentication-and-how-to-generate-it
use Slim\Factory\AppFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Dotenv\Dotenv;
use Netcard\Model\ResponseMessage;

require __DIR__ . '../../vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = AppFactory::create();

$custom_error_handler = function (Request $request, Throwable $exception) use ($app){

    $payload = ['statusCode' => $exception->getCode(),
                'success' => false,
                'messages' => [$exception->getMessage()]];

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
    return $response;
};

// Add error middlewares and handlers
$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_middleware->setDefaultErrorHandler($custom_error_handler);

// Add middleware to set the content type
$app->add(function(Request $request, RequestHandler $handler){
    $response = $handler->handle($request);

    return $response->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type, Accept')
                    ->withHeader('Content-type', 'application/json;charset=utf-8')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Add middleware to verify if JSON body is correct
$app->add(function(Request $request, RequestHandler $handler) use ($app) {

    $responseMessage = new ResponseMessage();

    if($request->getMethod() == "POST" || $request->getMethod() == "PATCH")
    {
        if(!json_decode(strval($request->getBody())))
        {
            $errorMessage['error'] = array("code" => 2, "description" => "Corpo da requisição não é um JSON válido.");
            $responseMessage->buildMessage(400, false, $errorMessage, null);
            
            $response = $app->getResponseFactory()->createResponse();
            $response->getBody()->write(json_encode($responseMessage->send(), JSON_UNESCAPED_UNICODE));
            return $response;
        }
    }

    $response = $handler->handle($request);
    return $response;
});

$routes = require __DIR__ . '../../src/Router/Routes.php';
$routes($app);

$app->run();
?>