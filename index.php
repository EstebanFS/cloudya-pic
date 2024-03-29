<?php

session_start();
require_once('config/globals.php');

//Checking if user requested to log out
if (isset($_GET["logout"])) {
    session_destroy();
    loadPage('index.php');
}

?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CloudYa-Pic</title>

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="app/assets/stylesheets/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="app/assets/stylesheets/freelancer.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="app/assets/fonts/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" class="index">

    <!-- Navigation -->
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
                <a class="navbar-brand" href="#page-top">CloudYa-<span style="color: #18BC9C;">Pic</span></a>
                <!--<img src="app/assets/images/logo.png" class="img-responsive" alt="logo">-->
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="page-scroll">
                        <a href="#page-top">Home</a>
                    </li>
                    <li class="page-scroll">
                        <a href="app/views/navigate.php">Navigate</a>
                    </li>
                    <?php
                        if (isset($_SESSION["username"])) {
                            echo "<li class=\"page-scroll\">";
                            echo "  <a href=\"app/views/gallery.php\">My gallery</a>";
                            echo "</li>";
                            echo "<li class=\"page-scroll\">";
                            echo "  <a href=\"app/views/upload.php\">Upload</a>";
                            echo "</li>";
                            echo "<li class=\"page-scroll\">";
                            echo "  <a href=\"index.php?logout=true\">Log out<font color=\"white\" size=\"1\"> (".$_SESSION["username"].")</font></a>";
                            echo "</li>";
                        }
                        else {
                            echo "<li class=\"page-scroll\">";
                            echo "  <a href=\"app/views/login.php\">Sign in</a>";
                            echo "</li>";
                            echo "<li class=\"page-scroll\">";
                            echo "  <a href=\"app/views/register.php\">Sign up</a>";
                            echo "</li>";
                        }
                    ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div id="success">
                        <?php
                            if (isset($_SESSION["registered"]) && $_SESSION["registered"] != -1 && $_SESSION["registered"] != -2) {
                                echo "<div class=\"alert alert-success\">";
                                echo "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                                echo "  <strong>".$_SESSION["message"]."</strong>";
                                echo "</div>";
                                unset($_SESSION["registered"]);
                                unset($_SESSION["message"]);
                            }
                        ?>
                    </div>
                    <img class="img-responsive" src="app/assets/images/logo.png" alt="">
                    <div class="intro-text">
                        <span class="name">Take your photos.<br>upload your life.</span>
                        <hr class="star-light">
                        <span class="skills" align="justify">CloudYa-Pic is a website designed for you, where you can upload and share your best photos with the world. Upload your best moments, thoughts, feelings, favourite foods or anything you can imagine! Remember that you can also download the most popular pictures for free!</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Footer -->
    <footer class="text-center">
        <!--<div class="footer-above">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-4">
                        <h3>Location</h3>
                        <p>3481 Melrose Place<br>Beverly Hills, CA 90210</p>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>Around the Web</h3>
                        <ul class="list-inline">
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-google-plus"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-dribbble"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>About Freelancer</h3>
                        <p>Freelance is a free to use, open source Bootstrap theme created by <a href="http://startbootstrap.com">Start Bootstrap</a>.</p>
                    </div>
                </div>
            </div>
        </div>-->
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; CloudYa-Pic - 2014
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top page-scroll visible-xs visble-sm">
        <a class="btn btn-primary" href="#page-top">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

    <!-- jQuery Version 1.11.0 -->
    <script src="app/assets/js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="app/assets/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="app/assets/js/classie.js"></script>
    <script src="app/assets/js/cbpAnimatedHeader.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="app/assets/js/jqBootstrapValidation.js"></script>
    <script src="app/assets/js/contact_me.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="app/assets/js/freelancer.js"></script>

</body>

</html>
