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
    $sql = "SELECT id FROM hashtag WHERE description like '$hashtag'";
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
    $hashtag_id = DAO_select_hashtag_id($hashtag);
    $con = connect();
    $sql = "SELECT image.id AS id, image.title AS title, image.description AS description,
            image.resource AS resource, image.extension AS extension, 
            user.username AS username FROM hashtag, image_hashtag, image, user_image, user
            WHERE hashtag.id = $hashtag_id AND image_hashtag.hashtag_id = hashtag.id AND
            image_hashtag.image_id = image.id AND user_image.image_id = image.id";
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
    $sql = "SELECT id FROM image WHERE title like '$title'";
    $arr_res = mysql_query($sql);
    $error = mysql_error();
    if ($error != "") $result = -1;
    else {
      $result = array();
      $i = 0;
      while ($image = mysql_fetch_array($arr_res, MYSQL_BOTH)) {
        $result[$i]["id"]          = $image["id"];
        $i++;
      }
    }
    disconnect($con);
    return $result;
  }

  function DAO_filter_image_by_title($title) {
    $array_image_id = DAO_select_image_id_by_title($title);
    $con = connect();
    $size = count($array_image_id);
    $result = array();
    $i = 0;
    for($x = 0; $x < $size; $x++){
      $id_image_by_title = $array_image_id[$x];
      $sql = "SELECT image.id AS id, image.title AS title, image.description AS description,
              image.resource AS resource, image.extension AS extension, 
              user.username AS username, user_image.user_id FROM image, user_image, user
              WHERE image.id = $id_image_by_title AND user_image.image_id = image.id AND user.id = user_image.user_id";
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
}
?>