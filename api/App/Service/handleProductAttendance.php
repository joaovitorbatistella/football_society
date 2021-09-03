<?php

namespace Service;

use InvalidArgumentException;
use Model\ProductAttendance;
use Model\AuthorizationToken;
use Infra\GenericConsts;

class handleProductAttendance
{
    public const TABLE = 'produto_atendimento';
    public const GET_RESOURCES = ['list'];
    public const POST_RESOURCES = ['store'];
    public const DELETE_RESOURCES = ['delete'];
    public const PUT_RESOURCES = ['update'];

    private array $data;
    private array $bodyDataRequests;
    /**
     * @var object|ProductAttendance
     */
    private object $ProductAttendance;
    private object $AuthorizationToken;

    /**
     * handleProductAttendance constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        $this->ProductAttendance = new ProductAttendance();
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
        return $this->ProductAttendance->getAllProductAttendance(self::TABLE);
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
            $params[$i] = str_replace('%3A', ':', $params[$i]);
            $params[$i] = str_replace('%40', '@', $params[$i]);
            $params[$i] = str_replace('+', ' ', $params[$i]);
        }
        if(count($params) > 1) {
            $startDate= explode('=', $params[0]);
            $endDate= explode('=', $params[1]);
            $param= [
                $startDate,
                $endDate
            ];
        } else{
           $param= explode('=', $params[0]); 
        }
        return $this->ProductAttendance->getProductAttendanceByParams($param);
    }


    private function store()
    {
        [
            $attendanceId,
            $productId,
            $quantity,
            $fullPrice,
            $unityPrice
        ] = [
            $this->bodyDataRequests['attendanceId'],
            $this->bodyDataRequests['productId'],
            $this->bodyDataRequests['quantity'],
            $this->bodyDataRequests['fullPrice'],
            $this->bodyDataRequests['unityPrice'],
        ];

        if ($attendanceId && $productId && $quantity && $fullPrice && $unityPrice) {
            if ($this->ProductAttendance->insertProductAttendance($attendanceId, $productId, $quantity, $fullPrice, $unityPrice) > 0) {
                $this->ProductAttendance->getConn()->getDb()->commit();
                return ['message' => 'Product has been registered in attendance relationship'];
            }

            $this->ProductAttendance->getConn()->getDb()->rollBack();

            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
        }
        throw new InvalidArgumentException(GenericConsts::MSG_ERROR_EMPTY_FIELDS);
    }

    /**
     * @return string
     */
    private function delete()
    {
        return $this->ProductAttendance->getConn()->delete(self::TABLE, $this->data['id']);
    }

    /**
     * @return string
     */
    private function update()
    {
        if ($this->ProductAttendance->updateProductAttendance($this->data['id'], $this->bodyDataRequests) > 0) {
            $this->ProductAttendance->getConn()->getDb()->commit();
            return GenericConsts::MSG_ATUALIZADO_SUCESSO;
        }
        $this->ProductAttendance->getConn()->getDb()->rollBack();
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_NAO_AFETADO);
    }

}
