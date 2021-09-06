<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

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
     * @param $table
     * @return Array
     */
    public function getAllProduct($table)
    {
        if ($table) {
            $sql = 'SELECT codigo AS cod_produto, nome, descricao, preco, estoque FROM ' . $table;
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
    public function getProductByParams($param)
    {
        if($param[0] == 'name'){
            $sql = "SELECT * FROM " . self::TABLE ." WHERE nome LIKE '%".$param[1]."%'";
        } else if($param[0] == 'id'){
            $sql = "SELECT * FROM " . self::TABLE . " WHERE codigo = ". $param[1];
        }
        
        $stmt = $this->getConn()->getDb()->query($sql);

        if($stmt) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } header("HTTP/1.1 406 Not Acceptable");
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);        
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
