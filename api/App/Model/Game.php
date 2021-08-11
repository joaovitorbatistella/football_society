<?php

namespace Model;

use database\DBConnection;

class Game
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
     * @param $timestamps
     * @param $price
     * @param $description
     * @param $attendanceId
     * @return int
     */
    public function insertGame($timestamps, $price, $description, $attendanceId)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (data_hora, valor, descricao, desconto, cod_atendimento) VALUES (:timestamps, :price, :description, :attendanceId)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':timestamps', $timestamps);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':attendanceId', $attendanceId);
        $stmt->execute();
        var_dump($stmt);exit;
        return $stmt;
    }

    /**
     * @param $timestamps
     * @param $price
     * @param $description
     * @param $attendanceId
     * @param $data
     * @return int
     */
    public function updateGame($timestamps, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET data_hora = :timestamps, valor = :price, descricao = :description, cod_atendimento = :attendanceId WHERE data_hora = :timestamps';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':timestamps', $timestamps);
        $stmt->bindValue(':price', $data['price']);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':attendanceId', $data['attendanceId']);
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
