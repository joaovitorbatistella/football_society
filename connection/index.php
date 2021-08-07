<?php

  $username = 'doadmin';
  $password = 'lr0exq0rsauvwhqe';
  $host = 'db-postgresql-football-society-do-user-7816146-0.b.db.ondigitalocean.com';
  $port = '25060';
  $database = 'defaultdb';
  $sslmode = 'require';
  $conn_string = "host=".$host." port=".$port." dbname=".$database." user=".$username." password=".$password."";
  $dbconn = pg_connect($conn_string);
  if ($dbconn) {
    echo 'Connection has been successfully.';
  } else {
    echo 'Connection attempt failed.';
  }
  echo "<h3>Connection Information</h3>";
  echo "DATABASE NAME:" . pg_dbname($dbconn) . "<br>";
  echo "HOSTNAME: " . pg_host($dbconn) . "<br>";
  echo "PORT: " . pg_port($dbconn) . "<br>";

  echo "<h3>Checking the query status</h3>";
  $query = "SELECT fname,lname FROM person";
  $result = pg_exec($db_handle, $query);

  if ($result) {
    echo "The query executed successfully.<br>";
    echo "<h3>Print First and last name:</h3>";
  for ($row = 0; $row < pg_numrows($result); $row++) {
    $firstname = pg_result($result, $row, 'fname');
    echo $firstname ." ";
    $lastname = pg_result($result, $row, 'lname');
    echo $lastname ."<br>";
  }
  } else {
    echo "The query failed with the following error:<br>";
    echo pg_errormessage($db_handle);
  }
  pg_close($db_handle);
?>