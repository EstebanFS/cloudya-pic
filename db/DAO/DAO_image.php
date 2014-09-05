<?php

include_once("/../environment.php");

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
    $sql = "SELECT inter3.id, inter3.title, inter3.description, inter3.resource, inter3.extension, inter3.tag, username
            FROM user
            JOIN (SELECT inter2.id, inter2.title, inter2.description, inter2.resource, inter2.extension, inter2.tag, user_id
                  FROM user_image
                  JOIN (SELECT inter.id, title, inter.description, resource, extension, hashtag.description AS tag
                        FROM hashtag
                        JOIN (SELECT id, title, description, resource, extension, hashtag_id
                              FROM image
                              JOIN image_hashtag ON image_id = id
                        ) AS inter ON inter.hashtag_id = hashtag.id
                  ) AS inter2 ON image_id = inter2.id
            ) AS inter3 ON user.id = inter3.user_id
            ORDER BY inter3.id DESC 
            LIMIT $limit";
    $arr_res = mysql_query($sql);
    $error = mysql_error();
    if ($error != "") $result = -1;
    else {
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

  function DAO_delete_image($user_id, $image_id) {
    $con = connect();
    $sql = "DELETE FROM user_image WHERE image_id = '$image_id' AND user_id = '$user_id'";
    $result = mysql_query($sql);
    disconnect($con);
    return $result;
  }

  function DAO_filter_image_by_hashTag($hashtag) {
    $con = connect();
    $sql = "SELECT inter5.id, inter5.title, inter5.description, inter5.resource, inter5.extension, inter5.tag, user.username
            FROM user
            JOIN (SELECT inter4.id, inter4.title, inter4.description, inter4.resource, inter4.extension, inter4.tag, user_image.user_id
                  FROM user_image
                  JOIN (SELECT inter3.id, inter3.title, inter3.description, inter3.resource, inter3.extension, hashtag.description AS tag
                        FROM hashtag
                        JOIN (SELECT inter2.id, inter2.title, inter2.description, inter2.resource, inter2.extension, image_hashtag.hashtag_id
                              FROM image_hashtag
                              JOIN (SELECT id, title, image.description, resource, extension, hashtag_id
                                    FROM image
                                    JOIN (SELECT image_id, hashtag_id, hashtag.description
                                          FROM image_hashtag
                                          JOIN hashtag ON hashtag.id = image_hashtag.hashtag_id
                                                      AND description LIKE  '%$hashtag%'
                                    ) AS inter ON id = inter.image_id
                              ) AS inter2 ON image_hashtag.image_id = inter2.id
                        ) AS inter3 ON inter3.hashtag_id = hashtag.id
                  ) AS inter4 ON inter4.id = user_image.image_id
            ) AS inter5 ON inter5.user_id = user.id";
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

  function DAO_filter_image_by_title($title) {
    $con = connect();
    $result = array();
    $sql = "SELECT inter4.id, inter4.title, inter4.description, inter4.resource, inter4.extension, inter4.tag, user.username
            FROM user
            JOIN (SELECT inter3.id, inter3.title, inter3.description, inter3.resource, inter3.extension, inter3.tag, user_image.user_id
                  FROM user_image
                  JOIN (SELECT inter2.id AS id, title, inter2.description, resource, extension, hashtag.description AS tag
                        FROM hashtag
                        JOIN (SELECT id, title, description, resource, extension, hashtag_id
                              FROM image_hashtag
                              JOIN (SELECT * 
                                    FROM image
                                    WHERE title LIKE  '%$title%'
                              ) AS inter ON id = image_id
                        ) AS inter2 ON hashtag.id = hashtag_id
                  ) AS inter3 ON inter3.id = user_image.image_id
            ) AS inter4 ON inter4.user_id = user.id";
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

  function DAO_filter_image_by_all($text){
    $array_image_title = DAO_image::DAO_filter_image_by_title($text);
    $array_image_hashtag = DAO_image::DAO_filter_image_by_hashTag($text);
    if(!is_array($array_image_title))return -1;
    if(!is_array($array_image_hashtag))return -1;
    $result = array();
    foreach ($array_image_title as $image) {
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
      $result[$index]["tags"]        = $image["tags"];
    }
    foreach($array_image_hashtag as $image) {
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
      $result[$index]["tags"]        = $image["tags"];
    }
    return $result;
  }
}
?>