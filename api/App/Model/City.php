<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

class City
{
    private object $Conn;
    const TABLE = 'cidade';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
    }

    /**
     * @param $name
     * @param $UF
     * @return int
     */
    public function insertCity($name, $UF)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (nome, UF) VALUES (:name, :UF)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':UF', $UF);
        $stmt->execute();
        var_dump($stmt);exit;
        return $stmt;
    }

    /**
     * @param $id
     * @param $name
     * @param $UF
     * @param $data
     * @return int
     */
    public function updateCity($id, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET nome = :name, UF = :UF WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':UF', $data['UF']);
        $stmt->execute();
        var_dump($stmt);exit;
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
