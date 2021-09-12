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
        if($param[0] == 'attendanceId' && $param[0][0] == 'a'){
            $id = (int)$param[1];
            $sql = "SELECT pa.cod_produto, pa.quantidade, pa.valor_unitario, p.estoque, p.nome as nome FROM produto_atendimento pa INNER JOIN produto p ON pa.cod_produto = p.codigo WHERE pa.cod_atendimento = ". $param[1];
        } else if($param[0][0] == 'attendanceId' && $param[1][0] == 'productId'){
            $sql = "SELECT pa.cod_produto, pa.quantidade, pa.valor_unitario, p.estoque, p.nome as nome FROM produto_atendimento pa INNER JOIN produto p ON pa.cod_produto = p.codigo WHERE pa.cod_atendimento = ". $param[0][1]." AND pa.cod_produto = ".$param[1][1];
        }
        $stmt = $this->getConn()->getDb()->query($sql);

        if($stmt) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } header("HTTP/1.1 406 Not Acceptable");
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);        
    }
    

    /**
     * @param $data
     * @return int
     */
    public function updateProductAttendance($data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET cod_atendimento = :attendanceId, cod_produto = :productId, quantidade = :quantity, valor_total = :fullValue, valor_unit = :unityValue WHERE cod_atendimento = :attendanceId AND cod_produto = :productId';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':attendanceId',$data['attendanceId']);
        $stmt->bindValue(':productId', $data['productId']);
        $stmt->bindValue(':quantity', $data['quantity']);
        $stmt->bindValue(':fullValue', $data['fullValue']);
        $stmt->bindValue(':unityValue', $data['unityValue']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deletePA($data) {
        $sqlUpdate = 'DELETE FROM ' . self::TABLE . ' WHERE cod_atendimento = :attendanceId AND cod_produto = :productId';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':attendanceId',$data['attendanceId']);
        $stmt->bindValue(':productId', $data['productId']);
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
