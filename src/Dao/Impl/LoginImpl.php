<?php

namespace Netcard\Dao\Impl;

use Netcard\Dao\LoginDao;
use Netcard\Model\ResponseMessage;
use PDOException;
use Exception;
use Netcard\Database\Connection;
use Netcard\Helper\HelperLogin;
use PDO;
use Firebase\JWT\JWT;

class LoginImpl implements LoginDao
{
    public function login(object $request_body) : ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage;

            if(!$json_data = json_decode(strval($request_body)))
                throw new Exception('Corpo da requisição não é um JSON válido.', 400);

            if(!isset($json_data->email) || empty($json_data->email))
            {
                (!isset($json_data->email) ? throw new Exception('E-mail não foi inserido no corpo da requisição.', 400) : false);
                (empty($json_data->email) ? throw new Exception('E-mail deve ser preenchido.') : false);
            }

            if(!isset($json_data->password) || empty($json_data->password))
            {
                (!isset($json_data->password) ? throw new Exception('Senha não foi inserida no corpo da requisição.', 400) : false);
                (empty($json_data->password) ? throw new Exception('Senha deve ser preenchida.') : false);
            }

            $connection = Connection::openConnection();

            $command = $connection->prepare(HelperLogin::selectAllUserInfo());
            $command->bindParam(':email', $json_data->email);
            $command->execute();

            $row_count = $command->rowCount();

            if($row_count === 0)
                throw new Exception('E-mail ou senha inválidos.', 401);

            $user_data = $command->fetch(PDO::FETCH_ASSOC);

            if($user_data['Blocked'] === 1)
                throw new Exception('Sua conta está bloqueada. Contate o suporte.', 403);

            if($user_data['Tries'] >= $user_data['Max_tries'])
                throw new Exception('Sua conta foi bloqueada devido ao número de tentativas ao realizar o login. Contate o suporte.', 403);

            if(!password_verify($json_data->password, $user_data['Password']))
            {
                $connection->beginTransaction();

                $command = $connection->prepare(HelperLogin::updateUserTries(1));
                $command->bindParam(':loginId', $user_data['LoginId']);
                $command->execute();

                $connection->commit();

                throw new Exception('E-mail ou senha inválidos.', 401);
            }

            $payload = [
                'id' => $user_data['LoginId'],
                'name' => $user_data['Name'],
                'email' => $user_data['Email'],
                'iat' => time(),
                'exp' => 259200
            ];

            $jwt = JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');

            $connection->beginTransaction();

            $command = $connection->prepare(HelperLogin::updateUserTries(0));
            $command->bindParam(':loginId', $user_data['LoginId']);
            $command->execute();

            $connection->commit();

            $returned_data = array();
            $returned_data['ID'] = $user_data['LoginId'];
            $returned_data['Name'] = $user_data['Name'];
            $returned_data['Email'] = $user_data['Email'];
            $returned_data['Token'] = $jwt;

            $response_message->setSuccess(true);
            $response_message->setHttpStatusCode(200);
            $response_message->setMessages('Login efetuado.');
            $response_message->setData($returned_data);

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
    }
}

?>