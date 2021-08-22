<?php

namespace Model;

use database\DBConnection;
use PDO;
use InvalidArgumentException;
use Infra\GenericConsts;

class Customer
{
    private object $Conn;
    const TABLE = 'cliente';

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
    public function getAllCustomers($table)
    {
        if ($table) {
            $sql = "SELECT cl.codigo, cl.nome, cl.dt_nascimento, cl.telefone, cl.email, cl.logradouro, cl.sexo, cl.cpf, ci.nome AS nome_cidade, ci.uf FROM cliente cl INNER JOIN cidade ci ON cl.cod_cidade = ci.codigo";
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
    public function getCustomerByParams($param)
    {
        if($param[0] == 'id'){
            $sql = "SELECT * FROM " . self::TABLE . " WHERE codigo = ". $param[1]."";
        } else if($param[0] == 'name'){
            $sql = "SELECT * FROM " . self::TABLE . " WHERE nome LIKE '%".$param[1]."%'";
        } else if($param[0] == 'cpf'){
            $sql = "SELECT * FROM " . self::TABLE . " WHERE cpf LIKE '%".$param[1]."%'";
        } else if($param[0] == 'email'){
            $sql = "SELECT * FROM " . self::TABLE . " WHERE email LIKE '%".$param[1]."%'";
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
     * @param $bornDate
     * @param $telephone
     * @param $email
     * @param $address
     * @param $sex
     * @param $cpf
     * @param $cityId
     * @return int
     */
    public function insertCustomer(
      $name,
      $bornDate,
      $telephone,
      $email,
      $address,
      $sex,
      $cpf,
      $cityId
    ){
        $sqlInsert = 'INSERT INTO ' . self::TABLE . ' (nome, dt_nascimento, telefone, email, logradouro, sexo, cpf, cod_cidade) VALUES (:name, :bornDate, :telephone, :email, :address, :sex, :cpf, :cityId)';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlInsert);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':bornDate', $bornDate);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':sex', $sex);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':cityId', $cityId);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $id
     * @param $name
     * @param $bornDate
     * @param $telephone
     * @param $email
     * @param $address
     * @param $sex
     * @param $cpf
     * @param $cityId
     * @return int
     */
    public function updateCustomer($id, $data)
    {
        $id = (int)$id;
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET nome = :name, dt_nascimento = :bornDate, telefone = :telephone, email = :email, logradouro = :address, sexo = :sex, cpf = :cpf, cod_cidade = :cityId WHERE codigo = '.$id;
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':bornDate', $data['bornDate']);
        $stmt->bindValue(':telephone', $data['telephone']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':address', $data['address']);
        $stmt->bindValue(':sex', $data['sex']);
        $stmt->bindValue(':cpf', $data['cpf']);
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
