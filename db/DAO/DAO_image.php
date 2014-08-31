<?php

include "/../environment.php";

class DAO_image{

  /*
  Returns: $idImage - Image id in database
           if couldn't create image returns -1
  */
  function DAO_add_image($idUser, $title, $description, $hashTagList, $extension) {
    $con = connect();
    $sql = "INSERT INTO image (title, description, extension) VALUES ('$title','$description', '$extension')";
    if (mysql_query($sql)) {  //or die(mysql_error());
      $idImage = mysql_insert_id();
      $result = $idImage;
      foreach ($hashTagList as $hashTag) {
        $idHashTag = DAO_image::DAO_add_hashTag($hashTag);
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
}
?>