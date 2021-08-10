<?php

namespace Validator;

use InvalidArgumentException;
use Model\AuthorizationToken;
use Service\handleUser;
use Service\handleAttendance;
use Infra\GenericConsts;
use Infra\Json;

class RequestValidator
{
    private array $request;
    private array $requestData;
    private object $AuthorizationToken;

    const GET = 'GET';
    const DELETE = 'DELETE';
    const POST = 'POST';
    const USERS = 'USERS';
    const ATTENDANCE = 'ATTENDANCE';

    /**
     * RequestValidator constructor.
     * @param array $request
     */
    public function __construct($request = [])
    {
        $this->AuthorizationToken = new AuthorizationToken();
        $this->request = $request;
    }

    /**
     * @return array|mixed|string|null
     */
    public function handleRequest()
    {
        $return = utf8_encode(GenericConsts::MSG_ERROR_ROUTER_TYPE);
        if(in_array($this->request['method'], GenericConsts::REQUEST_TYPES, true)) {
            $return = $this->directRequest();
        }
        return $return;
    }

    /**
     * Metodo para direcionar o tipo de Request
     * @return array|mixed|string|null
     */
    private function directRequest()
    {
        if ($this->request['route'] === 'USERS' && $this->request['resource'] === 'login' && $this->request['method'] === 'POST'){
            $this->requestData = Json::handleBodyRequest();
            $method = $this->request['method'].'login';
        } else {
            if ($this->request['method'] !== self::GET && $this->request['method'] !== self::DELETE) {
                $this->requestData = Json::handleBodyRequest();
                $method = $this->request['method'];
            } else if($this->request['resource'] === 'logout') {
                $method = $this->request['method'].'logout';
            }
            $this->AuthorizationToken->validToken(getallheaders()['Authorization']);
        }
        return $this->$method();
    }

    /**
     * Metodo para tratar os DELETES
     * @return mixed|string
     */
    private function delete()
    {
        $return = utf8_encode(GenericConsts::MSG_ERRO_TIPO_ROTA);
        if (in_array($this->request['route'], GenericConsts::DELETE_TYPE, true)) {
            switch ($this->request['route']) {
                case self::USERS:
                    $handleUser = new handleUser($this->request);
                    $return = $handleUser->validateDelete();
                    break;
                case self::ATTENDANCE:
                    $handleAttendance = new handleAttendance($this->request);
                    $return = $handleAttendance->validateDelete();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }
        return $return;
    }

        /**
     * Metodo para tratar o POSTlogin
     * @return array|null|string
     */
    private function POSTlogin()
    {
        $return = null;
        if (in_array($this->request['route'], GenericConsts::POST_TYPE, true)) {
            switch ($this->request['route']) {
                case self::USERS:
                    $handleUser = new handleUser($this->request);
                    $handleUser->setBodyDataRequests($this->requestData);
                    $return = $handleUser->validatelogin();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConsts::MSG_ERRO_TIPO_ROTA);
            }
            return $return;
        }
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_TIPO_ROTA);
    }

    /**
     * Metodo para tratar o GETlogin
     * @return array|mixed|string
     */
    private function GETlogout()
    {
        $return = utf8_encode(GenericConsts::MSG_ERROR_ROUTER_TYPE);
        if (in_array($this->request['route'], GenericConsts::GET_TYPE, true)) {
            switch ($this->request['route']) {
                case self::USERS:
                    $handleUser = new handleUser($this->request);
                    $return = $handleUser->validateLogout();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }
        return $return;
    }

    /**
     * Metodo para tratar os GETS
     * @return array|mixed|string
     */
    private function get()
    {
        $return = utf8_encode(GenericConsts::MSG_ERROR_ROUTER_TYPE);
        if (in_array($this->request['route'], GenericConsts::GET_TYPE, true)) {
            switch ($this->request['route']) {
                case self::USERS:
                    $handleUser = new handleUser($this->request);
                    $return = $handleUser->validateGet();
                    break;
                case self::ATTENDANCE:
                    $handleAttendance = new handleAttendance($this->request);
                    $return = $handleAttendance->validateGet();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }
        return $return;
    }

    /**
     * Metodo para tratar os POSTS
     * @return array|null|string
     */
    private function post()
    {
        $return = null;
        if (in_array($this->request['route'], GenericConsts::POST_TYPE, true)) {
            switch ($this->request['route']) {
                case self::USERS:
                    $handleUser = new handleUser($this->request);
                    $handleUser->setBodyDataRequests($this->requestData);
                    $return = $handleUser->validatePost();
                    break;
                case self::ATTENDANCE:
                    $handleAttendance = new handleAttendance($this->request);
                    $handleAttendance->setBodyDataRequests($this->requestData);
                    $return = $handleAttendance->validatePost();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConsts::MSG_ERRO_TIPO_ROTA);
            }
            return $return;
        }
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_TIPO_ROTA);
    }

    /**
     * Metodo para tratar os PUTS
     * @return array|null|string
     */
    private function put()
    {
        $return = null;
        if (in_array($this->request['route'], GenericConsts::PUT_TYPE, true)) {
            switch ($this->request['route']) {
                case self::USERS:
                    $handleUser = new handleUser($this->request);
                    $handleUser->setBodyDataRequests($this->requestData);
                    $return = $handleUser->validatePut();
                    break;
                case self::ATTENDANCE:
                    $handleAttendance = new handleAttendance($this->request);
                    $handleAttendance->setBodyDataRequests($this->requestData);
                    $return = $handleAttendance->validatePut();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConsts::MSG_ERRO_TIPO_ROTA);
            }
            return $return;
        }
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_TIPO_ROTA);
    }
}