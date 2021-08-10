<?php

namespace Model;

use database\DBConnection;

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
        var_dump($stmt);exit;
        return $stmt;
    }

    /**
     * @param $id
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
        $sqlUpdate = 'UPDATE ' . self::TABLE . ' SET nome = :name, dt_nascimento = :bornDate, telefone = :telephone, email = :email, logradouro = :address, sexo = :sex, cpf = :cpf, cod_cidade = :cityId WHERE codigo = :id';
        $this->Conn->getDb()->beginTransaction();
        $stmt = $this->Conn->getDb()->prepare($sqlUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':bornDate', $data['bornDate']);
        $stmt->bindValue(':telephone', $data['telephone']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':address', $data['address']);
        $stmt->bindValue(':sex', $data['sex']);
        $stmt->bindValue(':cpf', $data['cpf']);
        $stmt->bindValue(':cityId', $data['cityId']);
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
