<?php
include "/../../db/DAO/DAO_image.php";

class image_controller {

  function upload_image($user_id, $title, $desc, $img, $tag_list) {
    $name = $img["tmp_name"];
    //Opens a file by it's filename
    $fp = fopen($name, "r");
    //Reads the file in binary mode
    $blob = fread($fp, filesize($name));
    //Puts \ where needed
    $blob = addslashes($blob);
    //Closes the opened file
    fclose($fp);
    //If tag_list doesn't have tages, clear first position
    //created by the library used for tags.
    if (sizeof($tag_list) == 1 && $tag_list[0] == "") {
      unset($tag_list);
      $tag_list = array();
    }
    $ret = DAO_image::DAO_add_image($user_id, $title, $desc, $blob, $tag_list);
    if ($ret) return true; //Image and all was added successfully.
    else return false; //There were problems while trying to add image.
  }
}
?>