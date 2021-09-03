<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

class PurchaseProduct
{
    private object $Conn;
    const TABLE = 'compra_produto';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
    }

    /**
     * @param $purchaseId
     * @param $productId
     * @param $quantity
     * @param $fullValue
     * @param $unityValue
     * @return int
     */
    public function insertPurchaseProduct($purchaseId, $productId, $quantity, $fullValue, $unityValue)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (cod_compra, cod_produto, quantidade, valor_total, valor_unit) VALUES (:purchaseId, :productId, :quantity, :fullValue, :unityValue)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':purchaseId', $purchaseId);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':fullValue', $fullValue);
        $stmt->bindParam(':unityValue', $unityValue);
        $stmt->execute();
        var_dump($stmt);exit;
        return $stmt;
    }

    /**
     * @param $purchaseId
     * @param $productId
     * @param $quantity
     * @param $fullValue
     * @param $unityValue
     * @param $data
     * @return int
     */
    public function updatePurchaseProduct($purchaseId, $productId, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET cod_atendimento = :purchaseId, cod_produto = :productId, quantidade = :quantity, valor_total = :fullValue, valor_unit = :unityValue WHERE cod_atendimento = :purchaseId AND cod_produto = :productId';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':purchaseId',$purchaseId);
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
