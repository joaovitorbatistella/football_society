<?php

namespace Model;

use database\DBConnection;

class Purchase
{
    private object $Conn;
    const TABLE = 'compra';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
    }

    /**
     * @param $quantity
     * @param $providerId
     * @return int
     */
    public function insertPurchase($quantidade, $providerId)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (quantidade, cod_fornecedor) VALUES (:quantity, :providerId)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':providerId', $providerId);
        $stmt->execute();
        var_dump($stmt);exit;
        return $stmt;
    }

    /**
     * @param $id
     * @param $quantity
     * @param $providerId
     * @param $data
     * @return int
     */
    public function updatePurchase($id, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET quantidade = :quantity, cod_fornecedor = :providerId WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':quantity', $data['quantity']);
        $stmt->bindValue(':providerId', $data['providerId']);
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
