<?php

namespace Model;

use database\DBConnection;

class ProductAttendance
{
    private object $Conn;
    const TABLE = 'produto_atendimento';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
    }

    /**
     * @param $attendanceId
     * @param $productId
     * @param $quantity
     * @param $fullValue
     * @param $unityValue
     * @return int
     */
    public function insertProductAttendance($attendanceId, $productId, $quantity, $fullValue, $unityValue)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (cod_atendimento, cod_produto, quantidade, valor_total, valor_unit) VALUES (:attendanceId, :productId, :quantity, :fullValue, :unityValue)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':attendanceId', $attendanceId);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':fullValue', $fullValue);
        $stmt->bindParam(':unityValue', $unityValue);
        $stmt->execute();
        var_dump($stmt);exit;
        return $stmt;
    }

    /**
     * @param $attendanceId
     * @param $productId
     * @param $quantity
     * @param $fullValue
     * @param $unityValue
     * @param $data
     * @return int
     */
    public function updateProductAttendance($attendanceId, $productId, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET cod_atendimento = :attendanceId, cod_produto = :productId, quantidade = :quantity, valor_total = :fullValue, valor_unit = :unityValue WHERE cod_atendimento = :attendanceId AND cod_produto = :productId';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':attendanceId',$attendanceId);
        $stmt->bindValue(':productId', $productId);
        $stmt->bindValue(':quantity', $data['quantity']);
        $stmt->bindValue(':fullValue', $data['fullValue']);
        $stmt->bindValue(':unityValue', $data['unityValue']);
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
