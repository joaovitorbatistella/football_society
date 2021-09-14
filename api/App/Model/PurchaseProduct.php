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
     * @param $fullPrice
     * @param $unityPrice
     * @return int
     */
    public function insertPurchaseProduct($purchaseId, $productId, $quantity, $fullPrice, $unityPrice)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (cod_compra, cod_produto, quantidade, valor_total, valor_unitario) VALUES (:purchaseId, :productId, :quantity, :fullPrice, :unityPrice)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':purchaseId', $purchaseId);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':fullPrice', $fullPrice);
        $stmt->bindParam(':unityPrice', $unityPrice);
        $stmt->execute();
        return $stmt;
    }

    /**
     * @param $purchaseId
     * @param $productId
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
