<?php
  require_once './DBConnection.class.php';
  $db = DBConnection::conn();
  $sql = "SELECT * FROM users";
  foreach($db->query($sql) as $row) {
    print "<br/>";
    print $row['codigo'].' | '.$row['nome'].' | '.$row['nome_de_usuario'].'<br/>';
  }
?>