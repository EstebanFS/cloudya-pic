<?php

include "/../environment.php";
require_once('/../../config/globals.php');

class DAO_user{

  function DAO_user_exists($username) {
    $con = connect();
    $sql = "SELECT id FROM user WHERE username='$username'";
    $arr_res = mysql_query($sql); // or die(mysql_error());
    $result = false;
    if (mysql_num_rows($arr_res) == 1) $result = true;    
    disconnect($con);
    return $result;
  }

  function DAO_create_new_user($username,$email,$pwd){
    $con = connect();
    $sql = "INSERT INTO user (username, email, password) VALUES ('$username','$email','$pwd')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $result = mysql_insert_id();
    }
    else $result = -1;    
    disconnect($con);
    return $result;
  }

 function DAO_login($username, $pwd){
    $con = connect();
    $sql = "SELECT id FROM user WHERE username='$username' AND password='$pwd'";
    $arr_res = mysql_query($sql); // or die(mysql_error());
    $result = -1;
    if (mysql_num_rows($arr_res) == 1) {
      $result = mysql_fetch_array($arr_res);
      $result = $result["id"];     
    }
    disconnect($con);
    return $result;
  }

  function DAO_fetch_user_images($user_id) {
    $con = connect();
    $sql = "SELECT image.title AS title, image.description AS description,
            image.resource AS resource, image.extension AS extension, 
            user.username AS username FROM user, user_image, image
            WHERE user.id = '$user_id' AND user.id = user_image.user_id
            AND image.id = user_image.image_id";
    $arr_res = mysql_query($sql);
    $error = mysql_error();
    if ($error != "") $result = -1;
    else {
      $result = array();
      $i = 0;
      while ($image = mysql_fetch_array($arr_res, MYSQL_BOTH)) {
        $result[$i]["title"] = $image["title"];
        $result[$i]["description"] = $image["description"];
        $result[$i]["resource"] = $image["resource"];
        $result[$i]["extension"] = $image["extension"];
        $result[$i]["username"] = $image["username"];
        $i++;
      }
    }
    disconnect($con);
    return $result;
  }

}
?>