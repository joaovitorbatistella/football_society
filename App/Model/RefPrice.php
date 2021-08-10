<?php

namespace Model;

use database\DBConnection;

class RefPrice
{
    private object $Conn;
    const TABLE = 'valor_ref';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
    }

    /**
     * @param $price
     * @param $start
     * @param $end
     * @return int
     */
    public function insertRefPrice($price, $start, $end)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (valor, inicio, fim) VALUES (:price, :start, :end)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        $stmt->execute();
        var_dump($stmt);exit;
        return $stmt;
    }

    /**
     * @param $id
     * @param $price
     * @param $start
     * @param $end
     * @param $data
     * @return int
     */
    public function updateRefPrice($id, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET valor = :price, inicio = :start, fim = :end WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':price', $data['price']);
        $stmt->bindValue(':start', $data['start']);
        $stmt->bindValue(':end', $data['end']);
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
