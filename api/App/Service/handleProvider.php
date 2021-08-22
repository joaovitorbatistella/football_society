<?php

namespace Service;

use InvalidArgumentException;
use Model\Provider;
use Model\AuthorizationToken;
use Infra\GenericConsts;

class handleProvider
{
    public const TABLE = 'fornecedor';
    public const GET_RESOURCES = ['list', 'filterByName'];
    public const POST_RESOURCES = ['store'];
    public const DELETE_RESOURCES = ['delete'];
    public const PUT_RESOURCES = ['update'];

    private array $data;
    private array $bodyDataRequests;
    /**
     * @var object|Provider
     */
    private object $Provider;
    private object $AuthorizationToken;

    /**
     * handleProvider constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        $this->Provider = new Provider();
        $this->AuthorizationToken = new AuthorizationToken();
    }

    /**
     * @return mixed
     */
    public function validateGet()
    {
        $return = null;
        $resource = $this->data['resource'];
        if (in_array($resource, self::GET_RESOURCES, true)) {
            if( $this->data['params'] != null){
                $return = $this->data['id'] > 0 ? $this->getOneByKey() : $this->filterByParams( $this->data['params']);
            } else {
                $return = $this->data['id'] > 0 ? $this->getOneByKey() : $this->$resource();
            }
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($return === null) {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
        }

        return $return;
    }

    /**
     * @return mixed
     */
    public function validateDelete()
    {
        $return = null;
        $resource = $this->data['resource'];
        if (in_array($resource, self::DELETE_RESOURCES, true)) {
            if ($this->data['id'] > 0) {
                $return = $this->$resource();
            } else {
                throw new InvalidArgumentException(GenericConsts::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($return === null) {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
        }

        return $return;
    }

    /**
     * @return mixed
     */
    public function validatePost()
    {
        $return = null;
        $resource = $this->data['resource'];
        if (in_array($resource, self::POST_RESOURCES, true)) {
            $return = $this->$resource();
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($return === null) {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
        }

        return $return;
    }

    /**
     * @return mixed
     */
    public function validatePut()
    {
        $return = null;
        $resource = $this->data['resource'];
        if (in_array($resource, self::PUT_RESOURCES, true)) {
            if ($this->data['id'] > 0) {
                $return = $this->$resource();
            } else {
                throw new InvalidArgumentException(GenericConsts::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($return === null) {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
        }

        return $return;
    }

    /**
     * @param array $bodyDataRequests
     */
    public function setBodyDataRequests($bodyDataRequests)
    {
        $this->bodyDataRequests = $bodyDataRequests;

    }

    /**
     * @return mixed
     */
    private function list()
    {
        return $this->Provider->getAllProviders(self::TABLE);
    }

    /**
     * @param $data
     * @return mixed
     */
    private function filterByParams($data)
    {
        $var = explode('&', $data);
        $params=[];
        for($i=0; $i < count($var); $i++) {
            $params[$i] = $var[$i];
        }
        for($i=0; $i < count($params); $i++) {
            $params[$i] = str_replace('%20', ' ', $params[$i]);
            $params[$i] = str_replace('%40', '@', $params[$i]);
        }
        $param= explode('=', $params[0]);
        return $this->Provider->getProvidersByParams($param);
    }

    /**
     * @return mixed
     */
    private function getOneByKey()
    {
        return $this->User->getConn()->getOneByKey(self::TABLE, $this->data['id']);
    }

    private function store()
    {
        [
            $name,
            $telephone,
            $address,
            $cityId
        ] = [
            $this->bodyDataRequests['name'],
            $this->bodyDataRequests['telephone'],
            $this->bodyDataRequests['address'],
            $this->bodyDataRequests['cityId'],
        ];

        if ($name && $telephone && $address && $cityId) {
            if ($this->Provider->insertProvider($name, $address, $telephone, $cityId) > 0) {
                $insertedId = $this->Provider->getConn()->getDb()->lastInsertId();
                $this->Provider->getConn()->getDb()->commit();
                return ['insertedId' => $insertedId];
            }

            $this->Provider->getConn()->getDb()->rollBack();

            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
        }
        throw new InvalidArgumentException(GenericConsts::MSG_ERROR_EMPTY_FIELDS);
    }

    /**
     * @return string
     */
    private function delete()
    {
        return $this->Provider->getConn()->delete(self::TABLE, $this->data['id']);
    }

    /**
     * @return string
     */
    private function update()
    {
        if ($this->Provider->updateProvider($this->data['id'], $this->bodyDataRequests) > 0) {
            $this->Provider->getConn()->getDb()->commit();
            return GenericConsts::MSG_ATUALIZADO_SUCESSO;
        }
        $this->Provider->getConn()->getDb()->rollBack();
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_NAO_AFETADO);
    }

}
