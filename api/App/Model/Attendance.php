<?php

namespace Model;

use database\DBConnection;
use PDO;

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
     * @param $table
     * @return Array
     */
    public function getAllAttendances($table)
    {
        if ($table) {
            $sql = "SELECT a.codigo, a.descricao, a.data_hora, a.pago, cl.nome AS nome_cliente FROM atendimento a INNER JOIN cliente cl ON a.cod_cliente = cl.codigo";
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
    public function getAttendanceByParams($param)
    {
        if($param[0] == 'id'){
            $id = (int)$param[1];
            $sql = "SELECT * FROM " . self::TABLE . " WHERE codigo = ". $id;
        }
        else if($param[0][0] == 'startDate' && $param[1][0] == 'endDate'){
            $sql = "SELECT * FROM " . self::TABLE . " WHERE data_hora BETWEEN '".$param[0][1]."' AND '".$param[1][1]."'";
        } 
        $stmt = $this->getConn()->getDb()->query($sql);

        if($stmt) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } header("HTTP/1.1 406 Not Acceptable");
        throw new InvalidArgumentException(GenericConsts::MSG_ERRO_WITHOUT_RETURN);        
    }
    

    /**
     * @param $description
     * @param $dateAndTime
     * @param $payed
     * @param $customerId
     * @return int
     */
    public function insertAttendance($description, $payed, $customerId)
    {
        $dateAndTime = date('Y-m-d H:i:s');
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (descricao, data_hora, pago, cod_cliente) VALUES (:description, :dateAndTime, :payed, :customerId)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':dateAndTime', $dateAndTime);
        $stmt->bindParam(':payed', $payed);
        $stmt->bindParam(':customerId', $customerId);
        $stmt->execute();
        return $stmt;
    }

    /**
     * @param $id
     * @param $description
     * @param $dateAndTime
     * @param $payed
     * @param $customerId
     * @param $data
     * @return int
     */
    public function updateAttendance($id, $data)
    {
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET descricao = :description, data_hora = :dateAndTime, pago = :payed, cod_cliente = :customerId WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':dateAndTime', $data['dateAndTime']);
        $stmt->bindValue(':payed', $data['payed']);
        $stmt->bindValue(':customerId', $data['customerId']);
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
