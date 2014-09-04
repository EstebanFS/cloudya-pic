<?php

include "/../environment.php";

class DAO_image{

  /*
  Returns: $idImage - Image id in database
           if couldn't create image returns -1
  */

  function DAO_is_a_new_hashtag($description){
    $con = connect();
    $sql = "SELECT id FROM hashTag WHERE description = '$description'";
    $arr_res = mysql_query($sql); // or die(mysql_error());
    $result = -1;
    if (mysql_num_rows($arr_res) == 1) {
      $result = mysql_fetch_array($arr_res);
      $result = $result["id"];     
    }
    disconnect($con);
    return $result;
  }

  function DAO_add_image($idUser, $title, $description, $hashTagList, $extension) {
    $con = connect();
    $sql = "INSERT INTO image (title, description, extension) VALUES ('$title','$description', '$extension')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $idImage = mysql_insert_id();
      $result = $idImage;
      foreach ($hashTagList as $hashTag) {
        $idHashTag = DAO_image::DAO_is_a_new_hashtag($hashTag);
        if($idHashTag==-1) $idHashTag = DAO_image::DAO_add_hashTag($hashTag);
        if ($idHashTag == -1) {
          disconnect($con);
          return -1;
        }
        if (!DAO_image::DAO_add_image_hashTag($idImage, $idHashTag)) {
          disconnect($con);
          return -1;
        }
      }
      if (!DAO_image::DAO_add_user_image($idUser, $idImage)) $result = -1;
    }
    else $result = -1;     
    disconnect($con);
    return $result;
  }

  function DAO_add_hashTag($description){
    $con = connect();
    $sql = "INSERT INTO hashtag (description) VALUES ('$description')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $result = mysql_insert_id();
    }
    else $result = -1;    
    disconnect($con);
    return $result;
  }

  function DAO_add_image_hashTag($idImage,$idHashTag){
    $con = connect();
    $sql = "INSERT INTO image_hashtag (image_id, hashtag_id) VALUES ('$idImage','$idHashTag')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $result = true;
    }
    else $result = false;    
    disconnect($con);
    return $result;
  }

  function DAO_add_user_image($idUser,$idImage){
    $con = connect();
    $sql = "INSERT INTO user_image (image_id, user_id) VALUES ('$idImage','$idUser')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $result = true;
    }
    else $result = false;    
    disconnect($con);
    return $result;
  }

  function DAO_set_image_resource($img_id, $resource) {
    $con = connect();
    $sql = "UPDATE image SET resource = '$resource' WHERE id='$img_id'";
    echo $sql."<br>";
    $result = mysql_query($sql);
    disconnect($con);
    return $result;
  }

  function DAO_fetch_latest_images($limit) {
    $con = connect();
    $sql = "SELECT image.id AS id, image.title AS title, image.description AS description,
                   image.resource AS resource, image.extension AS extension,
                   user.username AS username FROM user, user_image, image
            WHERE  user.id = user_image.user_id AND
                   image.id = user_image.image_id
            ORDER BY image.id DESC LIMIT $limit";
    $arr_res = mysql_query($sql);
    $error = mysql_error();
    if ($error != "") $result = -1;
    else {
      $result = array();
      $i = 0;
      while ($image = mysql_fetch_array($arr_res, MYSQL_BOTH)) {
        $result[$i]["id"]          = $image["id"];
        $result[$i]["title"]       = $image["title"];
        $result[$i]["description"] = $image["description"];
        $result[$i]["resource"]    = $image["resource"];
        $result[$i]["extension"]   = $image["extension"];
        $result[$i]["username"]    = $image["username"];
        $i++;
      }
    }
    disconnect($con);
    return $result;
  }

  function DAO_delete_image($user_id, $image_id) {
    $con = connect();
    $sql = "DELETE FROM user_image WHERE image_id = '$image_id' AND user_id = '$user_id'";
    $result = mysql_query($sql);
    disconnect($con);
    return $result;
  }

    function DAO_select_hashtag_id($hashtag){
    $con = connect();
    $sql = "SELECT id FROM hashtag WHERE description like '%$hashtag%'";
    $arr_res = mysql_query($sql); // or die(mysql_error());
    $result = -1;
    if (mysql_num_rows($arr_res) == 1) {
      $result = mysql_fetch_array($arr_res);
      $result = $result["id"];     
    }
    disconnect($con);
    return $result;
  }

  function DAO_filter_image_by_hashTag($hashtag) {
    $hashtag_id = DAO_image::DAO_select_hashtag_id($hashtag);
    $con = connect();
    $sql = "SELECT image.id, image.title, image.description, image.resource, image.extension, inter.username\n"
    . "FROM image\n"
    . "JOIN (\n"
    . "\n"
    . "SELECT id, username, email, image_id, hashtag_id\n"
    . "FROM user\n"
    . "JOIN (\n"
    . "\n"
    . "SELECT image_hashtag.image_id, user_id, hashtag_id\n"
    . "FROM image_hashtag\n"
    . "JOIN user_image ON user_image.image_id = image_hashtag.image_id\n"
    . ") AS ihu ON user.id = ihu.user_id\n"
    . "AND hashtag_id ='$hashtag_id'\n"
    . ") AS inter ON inter.image_id = image.id LIMIT 0, 30 ";
    $arr_res = mysql_query($sql);
    $error = mysql_error();
    if ($error != "") $result = -1;
    else {
      $result = array();
      $i = 0;
      while ($image = mysql_fetch_array($arr_res, MYSQL_BOTH)) {
        $result[$i]["id"]          = $image["id"];
        $result[$i]["title"]       = $image["title"];
        $result[$i]["description"] = $image["description"];
        $result[$i]["resource"]    = $image["resource"];
        $result[$i]["extension"]   = $image["extension"];
        $result[$i]["username"]    = $image["username"];
        $i++;
      }
    }
    disconnect($con);
    return $result;
  }

  function DAO_select_image_id_by_title($title){
    $con = connect();
    $sql = "SELECT id AS id FROM image WHERE title LIKE '%$title%'";
    $arr_res = mysql_query($sql);
    $error = mysql_error();
    if ($error != "") $result = -1;
    else {
      $result = array();
      $i = 0;
      while ($image = mysql_fetch_array($arr_res, MYSQL_BOTH)) {
        $result[$i]["id"] = $image["id"];
        $i++;
      }
    }
    disconnect($con);
    return $result;
  }

  function DAO_filter_image_by_title($title) {
    $array_image_id = DAO_image::DAO_select_image_id_by_title($title);
    $con = connect();
    $size = count($array_image_id);
    $result = array();
    $i = 0;
    for($x = 0; $x < $size; $x++){
      $id_image_by_title = $array_image_id[$x]["id"];
      $sql = "SELECT image.id AS id, image.title AS title, image.description AS description,
              image.resource AS resource, image.extension AS extension, 
              user.username AS username, user_image.user_id FROM image, user_image, user
              WHERE image.id = '$id_image_by_title' AND user_image.image_id = image.id AND
              user.id = user_image.user_id";
      $arr_res = mysql_query($sql);
      $error = mysql_error();
      if ($error != "") $result = -1;
      else {
        while ($image = mysql_fetch_array($arr_res, MYSQL_BOTH)) {
          $result[$i]["id"]          = $image["id"];
          $result[$i]["title"]       = $image["title"];
          $result[$i]["description"] = $image["description"];
          $result[$i]["resource"]    = $image["resource"];
          $result[$i]["extension"]   = $image["extension"];
          $result[$i]["username"]    = $image["username"];
          $i++;
        }
      }
    } 
    disconnect($con);
    return $result;
  }

  function DAO_filter_image_by_title($text){
    $array_image_title = DAO_image::DAO_filter_image_by_title($text);
    $array_image_hashtag = DAO_image::DAO_filter_image_by_hashTag($text);
    if(!is_array($array_image_title))return -1;
    if(!is_array($array_image_hashtag))return -1;
    $size = count($array_image_title);
    $result = array();
    for($i=0; $i< $size; $i++){
      $index = $array_image_title[$i]["id"];
      $result[$index]["id"]          = $array_image_title[$i]["id"];
      $result[$index]["title"]       = $array_image_title[$i]["title"];
      $result[$index]["description"] = $array_image_title[$i]["description"];
      $result[$index]["resource"]    = $array_image_title[$i]["resource"];
      $result[$index]["extension"]   = $array_image_title[$i]["extension"];
      $result[$index]["username"]    = $array_image_title[$i]["username"];
    }
    $size = count($array_image_hashtag);
    for($i=0; $i< $size; $i++){
      $index = $array_image_hashtag[$i]["id"];
      $result[$index]["id"]          = $array_image_hashtag[$i]["id"];
      $result[$index]["title"]       = $array_image_hashtag[$i]["title"];
      $result[$index]["description"] = $array_image_hashtag[$i]["description"];
      $result[$index]["resource"]    = $array_image_hashtag[$i]["resource"];
      $result[$index]["extension"]   = $array_image_hashtag[$i]["extension"];
      $result[$index]["username"]    = $array_image_hashtag[$i]["username"];
    }
    return $result;
  }
}
?>