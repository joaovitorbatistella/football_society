<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

class Game
{
    private object $Conn;
    const TABLE = 'jogo';

    /**
     * GameRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
    }

    /**
     * @param $table
     * @return Array
     */
    public function getAllGames($table)
    {
        if ($table) {
            $sql = "SELECT j.data_hora, j.valor, j.descricao, j.desconto, a.descricao AS descricao_atendimento, cl.nome AS nome_cliente FROM jogo j INNER JOIN atendimento a ON j.cod_atendimento = a.codigo INNER JOIN cliente cl ON a.cod_cliente = cl.codigo";
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
    public function getGamesByParams($param)
    {
        if($param[0][0] == 'startDate' && $param[1][0] == 'endDate'){
            $sql = "SELECT * FROM " . self::TABLE . " WHERE data_hora BETWEEN '".$param[0][1]."' AND '".$param[1][1]."'";
        }
        else if($param[0] == 'dateTime'){
            $sql = "SELECT * FROM " . self::TABLE . " WHERE data_hora = '".$param[1]."'";
        } else if($param[0] == 'attendanceId'){
            $sql = "SELECT * FROM " . self::TABLE . " WHERE cod_atendimento = '".$param[1]."'";
        } 
        $stmt = $this->getConn()->getDb()->query($sql);

        if($stmt) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } header("HTTP/1.1 406 Not Acceptable");
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);        
    }

/**
     * @param $dateAndTime
     * @param $price
     * @param $description
     * @param $discount
     * @param $attendanceId
     * @return int
     */
    public function insertGame(
        $dateAndTime,
        $price,
        $description,
        $discount,
        $attendanceId
    ){
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (data_hora, valor, descricao, desconto, cod_atendimento) VALUES (:dateAndTime, :price, :description, :discount, :attendanceId)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':dateAndTime', $dateAndTime);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':discount', $discount);
        $stmt->bindParam(':attendanceId', $attendanceId);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $oldDateAndTime
     * @param $newDateAndTime
     * @param $price
     * @param $description
     * @param $discount
     * @param $attendanceId
     * @return int
     */
    public function updateGame($data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET data_hora = :newDateAndTime, valor = :price, descricao = :description, desconto = :discount, cod_atendimento = :attendanceId WHERE data_hora = :oldDateAndTime';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':oldDateAndTime', $data['oldDateAndTime']);
        $stmt->bindParam(':newDateAndTime', $data['newDateAndTime']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':discount', $data['discount']);
        $stmt->bindParam(':attendanceId', $data['attendanceId']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $dateAndTime
     * @return int
     */
    public function deleteGame($data)
    {
        $sqlDelete = 'DELETE FROM ' . self::TABLE . ' WHERE data_hora = :dateAndTime';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlDelete);
        $stmt->bindParam(':dateAndTime', $data['dateAndTime']);
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
