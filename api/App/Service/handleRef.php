<?php

namespace Service;

use InvalidArgumentException;
use Model\Ref;
use Model\AuthorizationToken;
use Infra\GenericConsts;

class handleRef
{
    public const TABLE = 'valor_ref';
    public const GET_RESOURCES = ['list'];

    private array $data;
    private array $bodyDataRequests;
    /**
     * @var object|Ref
     */
    private object $Ref;
    private object $AuthorizationToken;

    /**
     * handleRef constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        $this->Ref = new Ref();
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
    private function list()
    {
        return $this->Ref->getAllRefs(self::TABLE);
    }

    private function store()
    {
        [
            $dateAndTime,
            $price,
            $description,
            $discount,
            $attendanceId
        ] = [
            $this->bodyDataRequests['dateAndTime'],
            $this->bodyDataRequests['price'],
            $this->bodyDataRequests['description'],
            $this->bodyDataRequests['discount'],
            $this->bodyDataRequests['attendanceId'],
        ];

        if ($dateAndTime && $price && $description && $attendanceId) {
            if ($this->Ref->insertRef($dateAndTime, $price, $description, $discount, $attendanceId) > 0) {
                $this->Ref->getConn()->getDb()->commit();
                return 'Ref has been inserted.';
            }

            $this->Ref->getConn()->getDb()->rollBack();

            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
        }
        throw new InvalidArgumentException(GenericConsts::MSG_ERROR_EMPTY_FIELDS);
    }

}
