<?php

include_once("/../environment.php");
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
    $sql = "SELECT inter3.id, inter3.title, inter3.description, inter3.resource, inter3.extension, inter3.username, hashtag.description AS tag
            FROM hashtag
            JOIN (SELECT inter2.id, inter2.title, inter2.description, inter2.resource, inter2.extension, inter2.username, hashtag_id
                  FROM image_hashtag
                  JOIN (SELECT id, title, description, resource, extension, username
                        FROM image
                        JOIN (SELECT username, image_id
                              FROM user
                              JOIN user_image ON user_id = id
                                             AND id =  '$user_id'
                        ) AS inter ON id = inter.image_id
                  ) AS inter2 ON image_id = inter2.id
            ) AS inter3 ON inter3.hashtag_id = hashtag.id";
    $arr_res = mysql_query($sql);
    $error = mysql_error();
    if ($error != "") $result = -1;
    else {
      $result = array();
      while ($image = mysql_fetch_array($arr_res, MYSQL_BOTH)) {
        $index = $image["id"];
        if (!isset($result[$index])) {
          $result[$index] = array();
          $result[$index]["tags"] = array();
        }
        $result[$index]["id"]          = $image["id"];
        $result[$index]["title"]       = $image["title"];
        $result[$index]["description"] = $image["description"];
        $result[$index]["resource"]    = $image["resource"];
        $result[$index]["extension"]   = $image["extension"];
        $result[$index]["username"]    = $image["username"];
        array_push($result[$index]["tags"], $image["tag"]);
      }
    }
    disconnect($con);
    return $result;
  }
}
?>