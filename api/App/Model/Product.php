<?php

namespace Model;

use database\DBConnection;

class Product
{
    private object $Conn;
    const TABLE = 'produto';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
    }

    /**
     * @param $name
     * @param $description
     * @param $price
     * @param $inventory
     * @return int
     */
    public function insertProduct($name, $description, $price, $inventory)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (nome, descricao, preco, estoque) VALUES (:name, :description, :price, :inventory)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':inventory', $inventory);
        $stmt->execute();
        var_dump($stmt);exit;
        return $stmt;
    }

    /**
     * @param $id
     * @param $name
     * @param $description
     * @param $price
     * @param $inventory
     * @param $data
     * @return int
     */
    public function updateProduct($id, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET nome = :name, descricao = :description, preco = :price, estoque = :inventory WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':price', $data['price']);
        $stmt->bindValue(':inventory', $data['inventory']);
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
