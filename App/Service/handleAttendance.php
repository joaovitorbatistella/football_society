<?php

namespace Service;

use InvalidArgumentException;
use Model\Attendance;
use Infra\GenericConsts;

class handleAttendance
{
    public const TABLE = 'atendimento';
    public const GET_RESOURCES = ['list'];
    public const POST_RESOURCES = ['store'];
    public const DELETE_RESOURCES = ['delete'];
    public const PUT_RESOURCES = ['update'];

    private array $data;
    private array $bodyDataRequests;
    /**
     * @var object|Attendance
     */
    private object $Attendance;

    /**
     * handleAttendance constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        $this->Attendance = new Attendance();
    }

    /**
     * @return mixed
     */
    public function validateGet()
    {
        $return = null;
        $resource = $this->data['resource'];
        if (in_array($resource, self::GET_RESOURCES, true)) {
            $return = $this->data['id'] > 0 ? $this->getOneByKey() : $this->$resource();
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
        return $this->Attendance->getConn()->getAll(self::TABLE);
    }

    /**
     * @return mixed
     */
    private function getOneByKey()
    {
        return $this->Attendance->getConn()->getOneByKey(self::TABLE, $this->data['id']);
    }

    /**
     * @return array
     */
    private function store()
    {
        [
          $description,
          $timestamps,
          $payed,
          $customerId
        ] = [
          $this->bodyDataRequests['description'],
          $this->bodyDataRequests['timestamps'],
          $this->bodyDataRequests['payed'],
          $this->bodyDataRequests['customerId']
        ];

        if ($description && $timestamps && $payed && $customerId) {
            if ($this->Attendance->insertAttendance($description, $timestamps, $payed, $customerId) > 0) {
                $insertedId = $this->Attendance->getConn()->getDb()->lastInsertId();
                $this->Attendance->getConn()->getDb()->commit();
                return ['insertedId' => $insertedId];
            }

            $this->Attendance->getConn()->getDb()->rollBack();

            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
        }
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
    }

    /**
     * @return string
     */
    private function delete()
    {
        return $this->Attendance->getConn()->delete(self::TABLE, $this->data['id']);
    }

    /**
     * @return string
     */
    private function update()
    {
        if ($this->Attendance->updateAttendance($this->data['id'], $this->bodyDataRequests) > 0) {
            $this->Attendance->getConn()->getDb()->commit();
            return GenericConsts::MSG_ATUALIZADO_SUCESSO;
        }
        $this->Attendance->getConn()->getDb()->rollBack();
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_NAO_AFETADO);
    }

}
