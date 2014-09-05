<?php
session_start();

include "/../controllers/user_controller.php";
include "/../controllers/image_controller.php";
require_once('/../../config/globals.php');

//If session is not opened, redirect to index
if (!isset($_SESSION["username"])) {
    loadPage("../../index.php");
}

?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>My gallery</title>

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="../assets/stylesheets/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/stylesheets/freelancer.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../assets/fonts/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="../assets/stylesheets/tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

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
                <a class="navbar-brand" href="../../index.php">CloudYa-<span style="color: #18BC9C;">Pic</span></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="page-scroll">
                        <a href="../../index.php">Home</a>
                    </li>
                    <li class="page-scroll">
                        <a href="navigate.php">Navigate</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#page-top">My gallery</a>
                    </li>
                    <?php
                        if (isset($_SESSION["username"])) {
                            echo "<li class=\"page-scroll\">";
                            echo "  <a href=\"upload.php\">Upload</a>";
                            echo "</li>";
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
    <!-- Portfolio Grid Section -->
    <section id="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Your uploaded images</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <?php
                    $images = user_controller::get_user_images($_SESSION["user_id"]);
                    if (!is_array($images)) {
                        echo "<div class=\"alert alert-danger\">\n";
                        echo "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n";
                        echo "  <strong>A problem was encountered while trying to fetch your images</strong>\n";
                        echo "</div>\n";
                    }
                    else if (sizeof($images) == 0) {
                        echo "<div class=\"alert alert-info\">\n";
                        echo "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n";
                        echo "  <strong>You haven't uploaded any image yet.</strong>\n";
                        echo "</div>\n";
                    }
                    else {
                        //$tags = array();
                        for ($i = 0; $i < sizeof($images); $i++) {
                            //$tags[$i] = image_controller::get_image_tags($images[$i]["id"]);
                            echo "<div class=\"col-sm-4 portfolio-item\">\n";
                            echo "  <a href=\"#portfolioModal".($i + 1)."\" class=\"portfolio-link\" data-toggle=\"modal\">\n";
                            echo "  <div class=\"caption\">\n";
                            echo "      <div class=\"caption-content\">\n";
                            echo "          <i class=\"fa fa-search-plus fa-3x\"></i>\n";
                            echo "      </div>\n";
                            echo "  </div>\n";
                            echo "  <img src=\"../../filesystem/resizeduserimages/".$images[$i]["resource"]."_resized.png\" class=\"img-responsive\" alt=\"\">\n";
                            echo "  </a>\n";
                            echo "</div>\n";
                        }
                    }
                ?>
            </div>
        </div>
    </section>

    <!-- Portfolio Modals -->
    <?php
        for ($i = 0; $i < sizeof($images); $i++) {
            echo "<div class=\"portfolio-modal modal fade\" id=\"portfolioModal".($i + 1)."\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">\n";
            echo "  <div class=\"modal-content\">\n";
            echo "      <div class=\"close-modal\" data-dismiss=\"modal\">\n";
            echo "          <div class=\"lr\">\n";
            echo "              <div class=\"rl\">\n";
            echo "               </div>\n";
            echo "          </div>\n";
            echo "      </div>\n";
            echo "      <div class=\"container\">\n";
            echo "          <div class=\"row\">\n";
            echo "              <div class=\"col-lg-8 col-lg-offset-2\">\n";
            echo "                  <div class=\"modal-body\">\n";
            echo "                      <h2>".$images[$i]["title"]."</h2>\n";
            echo "                      <hr class=\"star-primary\">\n";
            echo "                      <img src=\"../../filesystem/userimages/".$images[$i]["resource"].".".$images[$i]["extension"]."\" class=\"img-responsive img-centered\" alt=\"\">\n";
            echo "                      <p>".$images[$i]["description"]."</p>\n";
                                        if (is_array($images[$i]["tags"])) {
                                            if (sizeof($images[$i]["tags"]) > 0) {
                                                echo "<div class=\"bootstrap-tagsinput\">";
                                                for ($j = 0; $j < sizeof($images[$i]["tags"]); $j++) {
                                                    $tagName = $images[$i]["tags"][$j];
                                                    echo "<span class=\"tag label label-info\">$tagName</span>";
                                                }
                                                echo "</div>";
                                            }
                                            else {
                                                echo "<p>This image doesn't contain any tag</p>";
                                            }
                                        }
            echo "                      <ul class=\"list-inline item-details\">\n";
            echo "                          <li>By: \n";
            echo "                              <strong>".$images[$i]["username"]."\n";
            echo "                              </strong>\n";
            echo "                          </li>\n";
            echo "                      </ul>\n";
            echo "                      <button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\"> Close</button>\n";
            echo "                      &nbsp;&nbsp;&nbsp;\n";
            $image_route = "../../filesystem/userimages/".$images[$i]["resource"].".".$images[$i]["extension"];
            $image_name = $images[$i]["title"];
            echo "                      <iframe id=\"downloadframe\" style=\"display:none\"></iframe>";
            echo "                      <a type=\"button\" class=\"btn btn-info\" onclick=\"downloadImage('$image_route', '$image_name')\"><i class=\"fa fa-download\"></i> Download</a>\n";
            echo "                      &nbsp;&nbsp;&nbsp;\n";
            $image_id = $images[$i]["id"];
            $user_id = $_SESSION["user_id"];
            echo "                      <a type=\"button\" class=\"btn btn-danger\" onclick=\"deleteImage('$user_id', '$image_id')\"><i class=\"fa fa-times\"></i> Delete</a>\n";
            echo "                  </div>\n";
            echo "              </div>\n";
            echo "          </div>\n";
            echo "      </div>\n";
            echo "  </div>\n";
            echo "</div>\n";
        }
    ?>

    <script type="text/javascript">
    function downloadImage(route, name) {
        document.location = "../controllers/download.php?route="+route+"&filename="+name;
    }
    </script>

    <script type="text/javascript">
    function deleteImage(user_id, image_id) {
        if (confirm("Are you sure you want to delete this image?")) {
            $.ajax ({
                url: "../controllers/image_controller.php",
                type: "POST",
                data: {action: "delete_image", args: [user_id, image_id]},
                success: function(result) {
                    if (result == 1) location.reload();
                    else alert("An error ocurred, please try later");
                }
            });
        }
    }
    </script>

    <!-- jQuery Version 1.11.0 -->
    <script src="../assets/js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="../assets/js/classie.js"></script>
    <script src="../assets/js/cbpAnimatedHeader.js"></script>

    <!-- Contact Form JavaScript -->
    <!--<script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>-->

    <!-- Custom Theme JavaScript -->
    <script src="../assets/js/freelancer.js"></script>
    <script src="../assets/stylesheets/tagsinput/bootstrap-tagsinput.js"></script>
</body>

</html>