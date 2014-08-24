<?php
session_start();

include "/../controllers/user_controller.php";
require_once('/../../config/globals.php');

//If session is opened, redirect to index
if (!isset($_SESSION["username"])) {
    loadPage("../../index.php");
}

//Method to receive data of the image and send it to controller
if (isset($_POST["title"]) && isset($_POST["description"]) &&
    isset($_FILES["image"]) && isset($_POST["tags"])) {
  //$user = $_SESSION["user_id"]; user_id doesn't already exist in SESSION
  $title = $_POST["title"];
  $desc = $_POST["description"];

  $tags = $_POST["tags"];
  $tags = explode(",", $tags);
  
  $image = $_FILES["image"];
  // ...
  // ...
}
?>

<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Upload picture</title>

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="../assets/stylesheets/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/stylesheets/freelancer.css" rel="stylesheet">
    <link href="../assets/stylesheets/custom.css" rel="stylesheet">
    <link href="../assets/stylesheets/tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../assets/fonts/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body id="page-top" class="upload">
  <!--<div class="image-uploader">
    <input type="file" />
  </div>-->

  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="../../index.php">CloudYa-<span style="color:#18bc9c">Pic</span></a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
                  <li class="page-scroll">
                    <a href="../../index.php">Home</a>
                  </li>
                  <li class="page-scroll">
                    <a href="popular.php">Popular</a>
                  </li>
                  <li class="page-scroll">
                    <a href="#page-top">Upload</a>
                  </li>
                  <?php
                    if (isset($_SESSION["username"])) {
                      echo "<li class=\"page-scroll\">";
                      echo "  <a href=\"../../index.php?logout=true\">Log out<font color=\"white\" size=\"1\"> (".$_SESSION["username"].")</font></a>";
                      echo "</li>";
                    }
                    else {
                      echo "<li class=\"page-scroll\">";
                      echo "  <a href=\"login.php\">Sign in</a>";
                      echo "</li>";
                      echo "<li class=\"page-scroll\">";
                      echo "  <a href=\"register.php\">Sign up</a>";
                      echo "</li>";
                    }
                  ?>
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <br>
  <br>
  <br>
  <section id="contact">
      <div class="container">
          <div class="row">
              <div class="col-lg-12 text-center">
                  <h2>Upload image</h2>
                  <hr class="star-primary">
              </div>
          </div>
          <div class="row">
              <div class="col-lg-8 col-lg-offset-2">
                  <div id="success">
                  </div>
                  <form action="upload.php" method="post" name="upload" enctype="multipart/form-data" novalidate>
                      <div class="row control-group">
                          <div class="form-group col-xs-12 floating-label-form-group controls">
                              <label>Title</label>
                              <input name="title" type="text" class="form-control" placeholder="Title" id="title" required data-validation-required-message="Please enter an image title.">
                              <p class="help-block text-danger"></p>
                          </div>
                      </div>
                      <div class="row control-group">
                          <div class="form-group col-xs-12 floating-label-form-group controls">
                              <label>Description</label>
                              <textarea name="description" rows="5" cols="50" class="form-control" placeholder="Description" id="description" required data-validation-required-message="Please enter a description."></textarea>
                              <p class="help-block text-danger"></p>
                          </div>
                      </div>
                      <div class "row control-group">
                        <label>Image</label>
                      </div>
                      <div class="row control-group">
                          <div class="form-group col-xs-12 floating-label-form-group controls">
                              <label>Image</label>
                              <input name="image" type="file" class="form-control" id="image" required data-validation-required-message="Please select your image." accept="image/*">
                              <p class="help-block text-danger"></p>
                          </div>
                      </div>
                      <div class "row control-group">
                        <label>Tags</label>
                      </div>
                      <input name="tags" id="tags" type="text" data-role="tagsinput"/>
                      <br>
                      <div id="success"></div>
                      <br>
                      <div class="row">
                          <div align="center" class="form-group col-xs-12">
                              <button type="submit" class="btn btn-success btn-lg">Upload</button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </section>

  <!-- jQuery Version 1.11.0 -->
  <script src="../assets/js/jquery-1.11.0.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="../assets/js/bootstrap.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
  <script src="../assets/js/classie.js"></script>
  <script src="../assets/js/cbpAnimatedHeader.js"></script>

  <!-- Contact Form JavaScript -->
  <script src="../assets/js/jqBootstrapValidation.js"></script>
  <script src="../assets/js/contact_me.js"></script>

  <!-- Custom Theme JavaScript -->
  <script src="../assets/js/freelancer.js"></script>
  <script src="../assets/stylesheets/tagsinput/bootstrap-tagsinput.js"></script>
</body>
</html>
