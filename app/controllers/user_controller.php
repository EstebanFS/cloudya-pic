<?php
include "/../../db/DAO/DAO_user.php";

class user_controller {

  /* Returns: 1) User already exists
              2) Error creating user
              3) User created successfully */
  function create_user($username, $email, $password) {
    $user_exists = DAO_user::DAO_user_exists($username);
    if ($user_exists) return 1;
    $password = md5($password);
    $registered = DAO_user::DAO_create_new_user($username, $email, $password);
    if ($registered) return 3;
    else return 2;
  }

  /* Returns: 1) User doesn't exist
              2) Password is incorrect
              3) Logged successfully */
  function login($username, $password) {
    $user_exists = DAO_user::DAO_user_exists($username);
    if (!$user_exists) return 1;
    $password = md5($password);
    $logged = DAO_user::DAO_login($username, $password);
    if ($logged) return 3;
    else return 2;
  }
}
?>