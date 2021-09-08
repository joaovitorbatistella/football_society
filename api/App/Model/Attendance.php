<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

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
        $this->DateTime = new DateTime();
    }

    /**
     * @param $table
     * @return Array
     */
    public function getAllAttendances($table)
    {
        if ($table) {
            $sql = "SELECT a.codigo, a.descricao, a.data_hora, a.pago, cl.nome AS nome_cliente, sum(pa.valor_total) as valor_total FROM atendimento a INNER JOIN cliente cl ON a.cod_cliente = cl.codigo INNER JOIN produto_atendimento pa ON a.codigo = pa.cod_atendimento group by a.codigo, cl.nome ORDER BY a.codigo desc;";
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
            $sql = "SELECT a.codigo, a.descricao, a.data_hora, a.pago, cl.nome AS nome_cliente, sum(pa.valor_total) as valor_total FROM atendimento a LEFT JOIN jogo j  on a.codigo = j.cod_atendimento LEFT JOIN cliente cl ON cl.codigo = a.cod_cliente INNER JOIN produto_atendimento pa ON a.codigo = pa.cod_atendimento WHERE a.codigo = ". $id." group by a.codigo, cl.nome ORDER BY a.codigo desc" ;
        } else if($param[0] == 'status'){
            $param[1] = (int)$param[1] == 1 ? 'Y' : 'N';
            $sql = "SELECT a.codigo, a.descricao, a.data_hora, a.pago, cl.nome AS nome_cliente, sum(pa.valor_total) as valor_total FROM atendimento a LEFT JOIN jogo j  on a.codigo = j.cod_atendimento LEFT JOIN cliente cl ON cl.codigo = a.cod_cliente INNER JOIN produto_atendimento pa ON a.codigo = pa.cod_atendimento WHERE a.pago = '".$param[1]."' group by a.codigo, cl.nome ORDER BY a.codigo desc";
        }
        else if($param[0][0] == 'startDate' && $param[1][0] == 'endDate'){           
            $sql = "SELECT a.codigo, a.descricao, a.data_hora, a.pago, cl.nome AS nome_cliente, sum(pa.valor_total) as valor_total FROM atendimento a LEFT JOIN jogo j  on a.codigo = j.cod_atendimento LEFT JOIN cliente cl ON cl.codigo = a.cod_cliente INNER JOIN produto_atendimento pa ON a.codigo = pa.cod_atendimento WHERE a.data_hora BETWEEN '".$param[0][1]."' AND '".$param[1][1]."' group by a.codigo, cl.nome ORDER BY a.codigo desc";
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
        $dateAndTime = $this->DateTime->getNow();
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
        $dateAndTime = $this->DateTime->getNow();
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET descricao = :description, data_hora = :dateAndTime, pago = :payed, cod_cliente = :customerId WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':dateAndTime', $dateAndTime);
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
