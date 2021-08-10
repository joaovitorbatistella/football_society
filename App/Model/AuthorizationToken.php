<?php

namespace Model;

use database\DBConnection;
use InvalidArgumentException;
use Infra\GenericConsts;

class AuthorizationToken
{
    private object $pg;
    public const TABLE = 'authorized_tokens';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->pg = new DBConnection();
    }

    /**
     * @param $token
     */
    public function validToken($token)
    {
        $token = str_replace([' ', 'Bearer'], '', $token);

        if ($token) {
            $sql = 'SELECT id FROM ' . self::TABLE . ' WHERE token = :token AND status = :status';
            $stmt = $this->getConn()->getDb()->prepare($sql);
            $stmt->bindValue(':token', $token);
            $stmt->bindValue(':status', GenericConsts::SIM);
            $stmt->execute();
            if ($stmt->rowCount() !== 1) {
                header("HTTP/1.1 401 Unauthorized");
                throw new InvalidArgumentException(GenericConsts::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
            }
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_TOKEN_VAZIO);
        }
    }

    /**
     * @return pg|object
     */
    public function getConn()
    {
        return $this->pg;
    }
}