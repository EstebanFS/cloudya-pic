<?php

class DAO_user{

  function DAO_user_exists($username){
    $con = connect();
    $sql = "SELECT id FROM user WHERE username='$username'";
    $arr_res = mysql_query($sql) or die(mysql_error());
    $result = true;
    if( mysql_num_rows($arr_res) == 1 ) $result = false;    
    disconnect($con);
    return $result;
  }

  function DAO_create_new_user($username,$email,$pwd){
    $con = connect();
    $sql = "INSERT INTO user (username, email, password) VALUES ('$username','$email','$pwd')";
    $arr_res = mysql_query($sql) or die(mysql_error());
    $result = true;
    if( mysql_num_rows($arr_res) == 1 ) $result = false;    
    disconnect($con);
    return $result;
  }

}
?>