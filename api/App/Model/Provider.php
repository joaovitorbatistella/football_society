<?php

namespace Model;

use database\DBConnection;

class Provider
{
    private object $Conn;
    const TABLE = 'fornecedor';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
    }

    /**
     * @param $name
     * @param $address
     * @param $telephone
     * @param $cityId
     * @return int
     */
    public function insertProvider($name, $description, $telephone, $cityId)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (nome, logradouro, telefone, cod_cidade) VALUES (:name, :description, :telephone, :cityId)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':cityId', $cityId);
        $stmt->execute();
        var_dump($stmt);exit;
        return $stmt;
    }

    /**
     * @param $id
     * @param $name
     * @param $address
     * @param $telephone
     * @param $cityId
     * @param $data
     * @return int
     */
    public function updateProvider($id, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET nome = :name, logradouro = :address, telefone = :telephone, cod_cidade = :cityId WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':address', $data['address']);
        $stmt->bindValue(':telephone', $data['telephone']);
        $stmt->bindValue(':cityId', $data['cityId']);
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
