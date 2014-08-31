<?php
include "/../../db/DAO/DAO_image.php";
include "image_resizer_caller.php";

class image_controller {

  /* Returns: -1) Image size is exceeded
              1) Wrong image extension
              2) Error uploading image
              3) Image uploaded successfully */
  function upload_image($user_id, $title, $desc, $img, $tag_list) {
    //Get file extension
    if ($img["error"] == 1) return -1; //Image exceeded max file size
    $file_ext = end(explode('.', $img["name"]));
    $file_ext = strtolower($file_ext);
    //Check if extension is valid
    $extensions = array("jpeg", "jpg", "png", "gif");
    if (in_array($file_ext, $extensions) === false) return 1;

    //If tag_list doesn't have tags, clear first position
    //created by the library used for tags.
    if (sizeof($tag_list) == 1 && $tag_list[0] == "") {
      unset($tag_list);
      $tag_list = array();
    }
    $ret = DAO_image::DAO_add_image($user_id, $title, $desc, $tag_list, $file_ext);
    if ($ret != -1) { //Image and all was added successfully.
      //Get temporal url of the image
      $file_url = $img["tmp_name"];
      $image_name = md5($ret); //Ret is current image id.
      if (move_uploaded_file($file_url, 
                              "../../filesystem/userimages/".$image_name.".".$file_ext)) {
        //Set the md5 file name to image.
        if (DAO_image::DAO_set_image_resource($ret, $image_name)) {
          //Call resizer and save a copy resized.
          image_resizer_caller::resize_image($image_name, $file_ext);
          //ALL IS RIGHT, I can return that image was successfully uploaded
          return 3;
        }
      }
    }
    return 2; //There were problems while trying to add image.
  }
}
?>