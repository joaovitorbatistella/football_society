<?php

namespace Model;

use database\DBConnection;

class Attendance
{
    private object $Conn;
    const TABLE = 'atendimento';

    /**
     * UsuariosRepository constructor.
     */
    public function __construct()
    {
        $this->Conn = new DBConnection();
    }

    /**
     * @param $description
     * @param $timestamps
     * @param $payed
     * @param $customerId
     * @return int
     */
    public function insertAttendance($description, $timestamps, $payed, $customerId)
    {
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (descricao, data_hora, pago, cod_cliente) VALUES (:description, :timestamps, :customerId)';
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
     * @param $description
     * @param $timestamps
     * @param $payed
     * @param $customerId
     * @param $data
     * @return int
     */
    public function updateAttendance($id, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET descricao = :description, data_hora = :timestamps, pago = :payed, cod_cliente = :customerId WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':timestamps', $data['timestamps']);
        $stmt->bindValue(':payed', $data['payed']);
        $stmt->bindValue(':customerId', $data['customerId']);
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
