<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

class User
{
    private object $Conn;
    const TABLE = 'users';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
    }

    /**
     * @param $username
     * @param $password
     * @return int
     */
    public function handleLogin($username, $password)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE nome_de_usuario = :username AND senha = :password';
        $stmt = $this->Conn->getDb()->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getAllUsers($table)
    {
        if ($table) {
            $sql = 'SELECT codigo, nome, nome_de_usuario FROM ' . $table;
            $stmt = $this->getConn()->getDb()->query($sql);
            if($stmt) {
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (is_array($row) && count($row) > 0) {
                    return $row;
                }
            }
            header("HTTP/1.1 406 Not Acceptable");
            throw new InvalidArgumentException(GenericConsts::MSG_TABLE_NOT_FOUND);
        }
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);
    }

    /**
     * @param $login
     * @return int
     */
    public function getRegisterByLogin($login)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE login = :login';
        $stmt = $this->Conn->getDb()->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $username
     * @return int
     */
    public function getUserByUsernae($username)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE nome_de_usuario = :username';
        $stmt = $this->Conn->getDb()->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->rowCount();
    }
    

    /**
     * @param $login
     * @param $senha
     * @return int
     */
    public function insertUser($login, $senha)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (login, senha) VALUES (:login, :senha)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $id
     * @param $login
     * @param $senha
     * @return int
     */
    public function updateUser($id, $dados)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET login = :login, senha = :senha WHERE id = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':login', $dados['login']);
        $stmt->bindValue(':senha', $dados['senha']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @return Conn|object
     */
    public function getConn()
    {
        return $this->Conn;
    }
}
