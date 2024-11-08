<?php

namespace Netcard\Dao\Impl;

use Exception;
use Netcard\Dao\UserDao;
use Netcard\Model\ResponseMessage;
use PDOException;
use Netcard\Database\Connection;
use Netcard\Helper\Helper;
use Netcard\Helper\HelperUser;
use PDO;

class UserImpl implements UserDao
{
    public function getUser(int $user_id) : ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $connection = Connection::openConnection();

            // First query will fetch user datas 
            $command = $connection->prepare(HelperUser::selectUser());
            $command->bindParam(':userId', $user_id);
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(400, false, ['Id inserido não encontrado.'], null);
                return $response_message;
            }

            $user_data = $command->fetch(PDO::FETCH_ASSOC);

            // Second query will fetch user social medias
            $command = $connection->prepare(HelperUser::selectSocialMediaByUserId());
            $command->bindParam(':userId', $user_id);
            $command->execute();

            $user_social_media = $command->fetchAll(PDO::FETCH_ASSOC);

            $user_data['User_social_media'] = $user_social_media;

            $response_message->buildMessage(200, true, null, $user_data);
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

    public function updateUser(object $request_body, string $accessToken) : ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $json_data = json_decode(strval($request_body));

            $user_id = Helper::getJWTData($accessToken);

            // Then validate info`s body
            if(!isset($json_data->name))
            {
                $response_message->buildMessage(400, false, ['Nome deve ser preenchido.'], null);
                return $response_message;
            }

            if(!isset($json_data->sex))
            {
                $response_message->buildMessage(400, false, ['Sexo ser preenchido.'], null);
                return $response_message;
            }

            if(!isset($json_data->address))
            {   
                $response_message->buildMessage(400, false, ['Endereço deve ser preenchido.'], null);
                return $response_message;
            }
            else
            {
                if(!isset($json_data->address->street))
                {
                    $response_message->buildMessage(400, false, ['Rua deve ser preenchida.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->streetNumber))
                {
                    $response_message->buildMessage(400, false, ['Número da residência deve ser preenchida.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->cityId))
                {
                    $response_message->buildMessage(400, false, ['Cidade deve ser preenchida.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->district))
                {
                    $response_message->buildMessage(400, false, ['Bairro deve ser preenchido.'], null);
                    return $response_message;
                }

                if(!isset($json_data->address->zipCode))
                {
                    $response_message->buildMessage(400, false, ['CEP deve ser preenchido.'], null);
                    return $response_message;
                }
            }
            
            if(!isset($json_data->jobId))
            {
                $response_message->buildMessage(400, false, ['Profissão deve ser preenchida.'], null);
                return $response_message;
            }

            // Test if the actual user its the user using JWT
            $connection = Connection::openConnection();
            $connection->beginTransaction();

            // Update User
            $command = $connection->prepare(HelperUser::updateUser());
            $command->bindParam(':name', $json_data->name);

            $profile_picture = (isset($json_data->profilePicture) ? $json_data->profilePicture : null);
            $command->bindParam(':profilePicture', $profile_picture);

            $command->bindParam(':sex', $json_data->sex);
            $command->bindParam(':street', $json_data->address->street);
            $command->bindParam(':streetNumber', $json_data->address->streetNumber);
            $command->bindParam(':cityId', $json_data->address->cityId);

            $street_complement = (isset($json_data->streetComplement) ? $json_data->streetComplement : null);
            $command->bindParam(':streetComplement', $street_complement);

            $command->bindParam(':district', $json_data->address->district);
            $command->bindParam(':zipCode', $json_data->address->zipCode);

            $biography = (isset($json_data->biography) ? $json_data->biography : null);
            $command->bindParam(':biography', $biography);

            $command->bindParam(':jobId', $json_data->jobId);
            $command->bindParam(':userId', $user_id->id); 
            $command->execute();

            $connection->commit();

            $response_message->buildMessage(200, true, ['Alteração realizada com sucesso.'], null);
            return $response_message;
        }
        catch(PDOException $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        catch(Exception $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        finally
        {
            $connection = Connection::closeConnection();
        }
    }

    public function updateUserVisible(object $request_body, string $accessToken): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $json_data = json_decode(strval($request_body));

            $user_id = Helper::getJWTData($accessToken);

            $connection = Connection::openConnection();
            $connection->beginTransaction();

            if(!isset($json_data->visible))
            {
                $response_message->buildMessage(400, false, ['Vísivel deve ser preenchido.'], null);
                return $response_message;
            }

            $command = $connection->prepare(HelperUser::updateShowUser());
            $command->bindParam(':visible', $json_data->visible, PDO::PARAM_INT);
            $command->bindParam(':id', $user_id->id, PDO::PARAM_INT);
            $command->execute();

            $command = $connection->prepare(HelperUser::selectShowUser());
            $command->bindParam(':id', $user_id->id, PDO::PARAM_INT);
            $command->execute();

            $data = $command->fetch(PDO::FETCH_ASSOC);
            
            $connection->commit();
            
            $response_message->buildMessage(200, true, ['Visibilidade foi alterada.'], $data);
            return $response_message;
        }
        catch(PDOException $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        catch(Exception $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        finally
        {
            $connection = Connection::closeConnection();
        }
    }    

    public function setUserSocialMedia(object $request_body, string $accessToken): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $json_data = json_decode(strval($request_body));

            $user_id = Helper::getJWTData($accessToken);

            // Validade infos in JSON body
            if(!isset($json_data->socialMediaId))
            {
                $response_message->buildMessage(400, false, ['Rede Social deve ser preenchida.'], null);
                return $response_message;
            }

            if(!isset($json_data->url))
            {
                $response_message->buildMessage(400, false, ['Url da rede social deve ser preenchida.'], null);
                return $response_message;
            }

            $connection = Connection::openConnection();
            $connection->beginTransaction();

            $command = $connection->prepare(HelperUser::insertUserSocialMedia());
            $command->bindParam(':userId', $user_id->id, PDO::PARAM_INT);
            $command->bindParam(':socialMediaId', $json_data->socialMediaId, PDO::PARAM_INT);
            $command->bindParam(':url', $json_data->url, PDO::PARAM_STR);
            $command->execute();

            $connection->commit();
            
            $response_message->buildMessage(200, true, ['Rede social foi incluída com sucesso.'], null);
            return $response_message;
        }
        catch(PDOException $ex)
        {
            //$connection->rollBack();
            throw $ex;
        }
        catch(Exception $ex)
        {
            //$connection->rollBack();
            throw $ex;
        }
        finally
        {
            $connection = Connection::closeConnection();
        }
    }

    public function updateUserSocialMedia(int $userSocialMediaId, object $request_body, string $accessToken): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();

            $json_data = json_decode(strval($request_body));

            $user_id = Helper::getJWTData($accessToken);

            // Validate infos in JSON body
            if(!isset($json_data->url))
            {
                $response_message->buildMessage(400, false, ['Url da rede social deve ser preenchida.'], null);
                return $response_message;
            }

            $connection = Connection::openConnection();
            $connection->beginTransaction();

            $command = $connection->prepare(HelperUser::selectUserSocialMediaById());
            $command->bindParam(':id', $userSocialMediaId, PDO::PARAM_INT);
            $command->bindParam(':userId', $user_id->id, PDO::PARAM_INT);
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(404, false, ['Nenhum registro foi encontrado.'], null);
                return $response_message;
            }

            $command = $connection->prepare(HelperUser::updateUserSocialMedia());
            $command->bindParam(':url', $json_data->url, PDO::PARAM_STR);
            $command->bindParam(':id', $userSocialMediaId, PDO::PARAM_INT);
            $command->bindParam(':userId', $user_id, PDO::PARAM_INT);
            $command->execute();

            $connection->commit();

            $response_message->buildMessage(200, true, ['Rede social foi alterada com sucesso.'], null);
            return $response_message;
        }
        catch(PDOException $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        catch(Exception $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        finally
        {
            $connection = Connection::closeConnection();
        }
    }

    public function deleteUserSocialMedia(int $userSocialMediaId, string $accessToken): ResponseMessage
    {
        try
        {
            $response_message = new ResponseMessage();
            
            $user_id = Helper::getJWTData($accessToken);

            $connection = Connection::openConnection();
            $connection->beginTransaction();

            $command = $connection->prepare(HelperUser::selectUserSocialMediaById());
            $command->bindParam(':id', $userSocialMediaId, PDO::PARAM_INT);
            $command->bindParam(':userId', $user_id, PDO::PARAM_INT);
            $command->execute();

            if($command->rowCount() === 0)
            {
                $response_message->buildMessage(404, false, ['Nenhum registro foi encontrado.'], null);
                return $response_message;
            }

            $command = $connection->prepare(HelperUser::deleteUserSocialMedia());
            $command->bindParam(':id', $userSocialMediaId, PDO::PARAM_INT);
            $command->bindParam(':userId', $user_id->id, PDO::PARAM_INT);
            $command->execute();

            $connection->commit();

            $response_message->buildMessage(200, true, ['Rede social foi excluída com sucesso.'], null);
            return $response_message;
        }
        catch(PDOException $ex)
        {   
            $connection->rollBack();
            throw $ex;
        }
        catch(Exception $ex)
        {
            $connection->rollBack();
            throw $ex;
        }
        finally
        {
            $connection = Connection::closeConnection();
        }
    }
}

?>