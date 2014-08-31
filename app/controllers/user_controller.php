<?php
include "/../../db/DAO/DAO_user.php";

class user_controller {

  /* Returns: -1) User already exists
              -2) Error creating user
              user_id) User created successfully */
  function create_user($username, $email, $password) {
    $user_exists = DAO_user::DAO_user_exists($username);
    if ($user_exists) return -1;
    $password = md5($password);
    $user_id = DAO_user::DAO_create_new_user($username, $email, $password);
    if ($user_id != -1) return $user_id;
    else return -2;
  }

  /* Returns: -1) User doesn't exist
              -2) Password is incorrect
              user_id) Logged successfully */
  function login($username, $password) {
    $user_exists = DAO_user::DAO_user_exists($username);
    if (!$user_exists) return -1;
    $password = md5($password);
    $logged = DAO_user::DAO_login($username, $password);
    if ($logged != -1) return $logged;
    else return -2;
  }

  /* Returns: -1) An error was encountered
               $images) An array with hashed names of the images
  */
  function get_user_images($user_id) {
    $images = DAO_user::DAO_fetch_user_images($user_id);
    if (!is_array($images)) return -1;
    return $images;
  }
}
?>