<?php

include_once '../models/User.php';
require_once '../share/orm/DBConnection.class.php';

class UserStore {
    public function storeUser(User $user) {
        $conn = \DBConnection::conn();
        $sql = "INSERT INT users(nome, nome_de_usuario, password) VALUES (?,?,?)";
        $ps = $conn->prepare($sql);
        try {
            $ps->bindValue(1,$user->getName());
            $ps->bindValue(2,$user->getUsername());
            $ps->bindValue(3,$user->getPassword());
            $ps->execute();
            
        } catch (PDOException $ex) {
            echo 'Erro ao conectar no banco!'.$ex;
        }
        
    }
    
    public function showUser(){
        $userList = array();

        $db = DBConnection::conn();
        $sql = "SELECT * FROM users";
        foreach($db->query($sql) as $row) {
          $user = new User();
          $user->setId($row['codigo']);
          $user->setName($row['nome']);
          $user->setUsername($row['nome_de_usuario']);
          $user->setPassword($row['senha']);

          $userList[] = $user;
        }  
        return $userList;
    }
}