<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

class City
{
    private object $Conn;
    const TABLE = 'cidade';

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
    public function getAllCities($table)
    {
        if ($table) {
            $sql = "SELECT * FROM cidade ORDER BY codigo desc";
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
    public function getCitiesByParams($param)
    {
        if($param[0] == 'id'){
            $id = (int)$param[1];
            $sql = "SELECT * FROM cidade WHERE codigo = ". $id." ORDER BY codigo desc" ;
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
     * @param $UF
     * @return int
     */
    public function insertCity($name, $UF)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (nome, UF) VALUES (:name, :UF)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':UF', $UF);
        $stmt->execute();
        var_dump($stmt);exit;
        return $stmt;
    }

    /**
     * @param $id
     * @param $name
     * @param $UF
     * @param $data
     * @return int
     */
    public function updateCity($id, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET nome = :name, UF = :UF WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':UF', $data['UF']);
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
