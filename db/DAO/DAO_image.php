<?php

include "/../environment.php";

class DAO_image{

  function DAO_add_image($idUser, $title, $description, $img, $hashTagList) {
    $con = connect();
    $sql = "INSERT INTO image (title, description, resource) VALUES ('$title','$description','$img')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $result = true;
      $idImage = mysql_insert_id();
      foreach ($hashTagList as $hashTag) {
        $idHashTag = DAO_image::DAO_add_hashTag($hashTag);
        if ($idHashTag == -1) {
          disconnect($con);
          return false;
        }
        if (!DAO_image::DAO_add_image_hashTag($idImage,$idHashTag)) {
          disconnect($con);
          return false;
        }
      }
      $result &= DAO_image::DAO_add_user_image($idUser,$idImage);
    }
    else $result = false;     
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
    //else $result = false;    
    disconnect($con);
    return $result;
  }
}
?>