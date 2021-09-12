<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

class Provider
{
    private object $Conn;
    const TABLE = 'fornecedor';

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
    public function getAllProviders($table)
    {
        if ($table) {
            $sql = "SELECT f.codigo AS cod_fornecedor, f.nome, f.logradouro, f.telefone, f.cod_cidade, ci.nome AS nome_cidade, ci.uf FROM fornecedor f INNER JOIN cidade ci ON f.cod_cidade = ci.codigo ORDER BY f.codigo desc";
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
    public function getProvidersByParams($param)
    {
        if($param[0] == 'id'){
            $sql = "SELECT f.codigo AS cod_fornecedor, f.nome, f.logradouro, f.telefone, f.cod_cidade, ci.nome AS nome_cidade, ci.uf FROM fornecedor f INNER JOIN cidade ci ON f.cod_cidade = ci.codigo WHERE f.codigo = ". $param[1]." ORDER BY f.codigo desc";
        } else if($param[0] == 'name'){
            $sql = "SELECT f.codigo AS cod_fornecedor, f.nome, f.logradouro, f.telefone, f.cod_cidade, ci.nome AS nome_cidade, ci.uf FROM fornecedor f INNER JOIN cidade ci ON f.cod_cidade = ci.codigo WHERE f.nome LIKE '%".$param[1]."%' ORDER BY f.codigo desc";
        } else if($param[0] == 'cityId'){
            $sql = "SELECT f.codigo AS cod_fornecedor, f.nome, f.logradouro, f.telefone, f.cod_cidade, ci.nome AS nome_cidade, ci.uf FROM fornecedor f INNER JOIN cidade ci ON f.cod_cidade = ci.codigo WHERE cod_cidade = ". $param[1]." ORDER BY f.codigo desc";
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
     * @param $address
     * @param $telephone
     * @param $cityId
     * @return int
     */
    public function insertProvider($name, $address, $telephone, $cityId)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (nome, logradouro, telefone, cod_cidade) VALUES (:name, :address, :telephone, :cityId)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':cityId', $cityId);
        $stmt->execute();
        return $stmt;
    }

    /**
     * @param $id
     * @param $data
     * @return int
     */
    public function updateProvider($id, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET nome = :name, logradouro = :address, telefone = :telephone, cod_cidade = :cityId WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':address', $data['address']);
        $stmt->bindValue(':telephone', $data['telephone']);
        $stmt->bindValue(':cityId', $data['cityId']);
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
