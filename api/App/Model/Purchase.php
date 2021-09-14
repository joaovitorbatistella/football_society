<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

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
        $this->DateTime = new DateTime();
    }

    /**
     * @param $table
     * @return Array
     */
    public function getAllPurchases($table)
    {
        if ($table) {
            $sql = "SELECT c.codigo AS cod_compra, c.data, sum(cp.valor_total) AS valor_total_compra 
                        FROM compra c 
                        LEFT JOIN compra_produto cp ON c.codigo = cp.cod_compra 
                        GROUP BY cp.cod_compra, c.codigo 
                        ORDER BY c.codigo desc";
            $stmt = $this->getConn()->getDb()->query($sql);
            if($stmt) {
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (is_array($row) && count($row) > 0) {
                    return $row;
                }
            }
            header("HTTP/1.1 406 Not Acceptable");
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);
        }
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);
    }

    /**
     * @param $param
     * @return int
     */
    public function getPurchasesByParams($param)
    {
        if($param[0] == 'id'){
            $sql = "SELECT c.codigo AS cod_compra, c.data, sum(cp.valor_total) AS valor_total_compra 
                        FROM compra c 
                        LEFT JOIN compra_produto cp ON c.codigo = cp.cod_compra 
                        GROUP BY cp.cod_compra, c.codigo
                        WHERE c.codigo = ". $param[1]." 
                        ORDER BY c.codigo desc";
        } else if($param[0] == 'data'){
            $sql = "SELECT c.codigo AS cod_compra, c.data, sum(cp.valor_total) AS valor_total_compra 
                        FROM compra c 
                        LEFT JOIN compra_produto cp ON c.codigo = cp.cod_compra 
                        GROUP BY cp.cod_compra, c.codigo
                        WHERE c.nome LIKE '%".$param[1]."%'
                        ORDER BY c.codigo desc";
        }
        
        $stmt = $this->getConn()->getDb()->query($sql);

        if($stmt) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } header("HTTP/1.1 406 Not Acceptable");
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);        
    }

    /**
     * @param $quantity
     * @param $providerId
     * @return int
     */
    public function insertPurchase($providerId)
    {
        $date = $this->DateTime->getNow();
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (data, cod_fornecedor) VALUES (:date, :providerId)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':providerId', $providerId);
        $stmt->execute();
        return $stmt;
    }

    /**
     * @param $id
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
