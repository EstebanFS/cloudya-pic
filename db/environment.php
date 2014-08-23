<?php
  /*1) Development
    2) Production
    3) Testing
  */
  $environment = 1;

  if ($environment == 1 ) include 'development.php';
  if ($environment == 2 ) include 'production.php';
  if ($environment == 3 ) include 'testing.php';

  function connect(){
    global $server, $username, $password, $db;
    $con = mysql_connect($server, $username, $password);
    mysql_select_db($db);
    return $con;
  }
  
  function disconnect($con){
    mysql_close($con);
  }
  
?>