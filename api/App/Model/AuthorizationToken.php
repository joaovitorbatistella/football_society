<?php

namespace Model;

use database\DBConnection;
use InvalidArgumentException;
use Infra\GenericConsts;
use PDO;

class AuthorizationToken
{
    private object $Conn;
    public const TABLE = 'authorized_tokens';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
        $this->DateTime = new DateTime();
    }

    public function getOneByToken($token)
    {
        if ($token) {
            $token = str_replace([' ', 'Bearer'], '', $token);
            $sql = 'SELECT id FROM ' . self::TABLE .  ' WHERE token = :token';
            $stmt = $this->getConn()->getDb()->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $row = $stmt->rowCount();
            if ($row === 1) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);
        }

        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_ID_OBRIGATORIO);
    }

    public function destroyById($id)
    {
        if ($id) {
            $sql = 'DELETE FROM ' . self::TABLE .  ' WHERE id = :id';
            $stmt = $this->getConn()->getDb()->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $row = $stmt->rowCount();
            if ($row === 1) {
                return GenericConsts::MSG_DELETADO_SUCESSO;
            }
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);
        }

        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_ID_OBRIGATORIO);
    }

    /**
     * @param $token
     */
    public function generateToken($username, $password)
    {
        $key = SECRET;

        //Header Token
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        //Payload - Content
        $payload = [
            'exp' => $this->DateTime->getNow(),
            'uid' => 1,
            'username' => $username,
        ];

        //JSON
        $header = json_encode($header);
        $payload = json_encode($payload);

        //Base 64
        $header = base64_encode($header);
        $payload = base64_encode($payload);

        //Sign
        $sign = hash_hmac('sha256', $header . "." . $payload, $key, true);
        $sign = base64_encode($sign);

        //Token
        $token = $header . '.' . $payload . '.' . $sign;

        if ($token) {
            $sqlInsert = 'INSERT INTO authorized_tokens (token, status) VALUES (:token, :status)';
            $stmt = $this->getConn()->getDb()->prepare($sqlInsert);
            $stmt->bindParam(':token', $token);
            $stmt->bindValue(':status', GenericConsts::SIM);
            $stmt->execute();
            $stmt->rowCount();
            if($stmt) {
                return $token;
            }
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_TOKEN_VAZIO);
        }
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
     * @return Conn|object
     */
    public function getConn()
    {
        return $this->Conn;
    }
}