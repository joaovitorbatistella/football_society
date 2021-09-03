<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

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
     * @param $proproductIdductId
     * @param $quantity
     * @param $fullPrice
     * @param $unityPrice
     * @return int
     */
    public function insertProductAttendance($attendanceId, $productId, $quantity, $fullPrice, $unityPrice)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (cod_atendimento, cod_produto, quantidade, valor_total, valor_unitario) VALUES (:attendanceId, :productId, :quantity, :fullPrice, :unityPrice)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':attendanceId', $attendanceId);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':fullPrice', $fullPrice);
        $stmt->bindParam(':unityPrice', $unityPrice);
        $stmt->execute();
        return $stmt;
    }

     /**
     * @param $param
     * @return int
     */
    public function getProductAttendanceByParams($param)
    {
        if($param[0] == 'attendanceId'){
            $id = (int)$param[1];
            $sql = "SELECT * FROM produto_atendimento  WHERE cod_atendimento = ". $param[1];
        }
        $stmt = $this->getConn()->getDb()->query($sql);

        if($stmt) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } header("HTTP/1.1 406 Not Acceptable");
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);        
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
