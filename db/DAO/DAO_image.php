<?php

include "/../environment.php";

class DAO_image{

  function DAO_add_image($idUser,$title,$description,$img,$hashTagList) {
    $con = connect();
    $sql = "INSERT INTO image (title, description, resource) VALUES ('$title','$description','$img')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $result = true;
      $idImage = mysql_insert_id();
      foreach ($hashTagList as $hashTag) {
        $idHashTag = DAO_add_hashTag($hashTag);
        DAO_add_image_hashTag($idImage,$idHashTag);     
      }
      DAO_add_user_image($idUser,$idImage);
    }
    else $result = false;     
    disconnect($con);
    return $result;
  }

  function DAO_add_hashTag($description, $idUser){
    $con = connect();
    $sql = "INSERT INTO hashtag (description) VALUES ('$description')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $result = mysql_insert_id(); ;
    }
    else $result = -1;    
    disconnect($con);
    return $result;
  }

  function DAO_add_image_hashTag($idImage,$idHashTag){
    $con = connect();
    $sql = "INSERT INTO image_hashtag (image_id, hashtag_id) VALUES ('$idImage','idHashTag')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $result = true;
    }
    else $result = false;    
    disconnect($con);
    return $result;
  }

  function DAO_add_user_image($idUser,$idImage){
    $con = connect();
    $sql = "INSERT INTO image_hashtag (image_id, user_id) VALUES ('$idImage','idUser')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $result = true;
    }
    else $result = false;    
    disconnect($con);
    return $result;
  }
}
?>