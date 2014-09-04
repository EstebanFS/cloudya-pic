<?php
session_start();

include "/../controllers/image_controller.php";
require_once('/../../config/globals.php');

if (isset($_POST["search"]) && isset($_POST["filter"])) {
    $filter = $_POST["filter"];
    $text = $_POST["search"];
    $filtered = image_controller::filter_images($filter, $text);
}

?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Latest images</title>

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="../assets/stylesheets/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/stylesheets/freelancer.css" rel="stylesheet">

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
                <a class="navbar-brand" href="../../index.php">CloudYa-<span style="color:#18bc9c">Pic</span></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="page-scroll">
                        <a href="../../index.php">Home</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#page-top">Navigate</a>
                    </li>
                    <?php
                        if (isset($_SESSION["username"])) {
                            echo "<li class=\"page-scroll\">";
                            echo "  <a href=\"gallery.php\">My gallery</a>";
                            echo "</li>";
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
                    <h2>Search an image</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <form action="navigate.php" name="searchform" method="POST" novalidate>
                    <div class="col-lg-2 text-center">
                        <p>Search by</p>
                        <select name="filter" class="form-control">
                            <option value="all">All</option>
                            <option value="tag">Tag</option>
                            <option value="title">Title</option>
                        </select>
                    </div>
                    <div class="col-lg-8 text-center">
                        <p>&nbsp;</p>
                        <input name="search" type="search" class="form-control" placeholder="Search images" required data-validation-required-message="Please enter a text to search.">
                        <p class="help-block text-danger"></p>
                    </div>
                    <div class="col-lg-2 text-center">
                        <p><font size="2">&nbsp;</font></p>
                        <form>
                            <button type="submit" class="btn btn-success btn-lg">Search</button>
                        </form>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Latest images</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <?php
                $images = image_controller::retrieve_latest_images();
                if (!is_array($images)) {
                    echo "<div class=\"alert alert-danger\">\n";
                    echo "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n";
                    echo "  <strong>A problem was encountered while trying to fetch the latest images</strong>\n";
                    echo "</div>\n";
                }
                else if (sizeof($images) == 0) {
                    echo "<div class=\"alert alert-info\">\n";
                    echo "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n";
                    echo "  <strong>No one has uploaded an image yet!</strong>\n";
                    echo "</div>\n";
                }
                else {
                    for ($i = 0; $i < sizeof($images); $i++) {
                        ?>
                        <div class="col-sm-2 portfolio-item">
                            <?php
                            echo "<a href=\"#portfolioModal".($i + 1)."\" class=\"portfolio-link\" data-toggle=\"modal\">";
                            ?>
                            <div class="caption">
                                <div class="caption-content">
                                    <i class="fa fa-search-plus fa-3x"></i>
                                </div>
                            </div>
                            <?php
                            echo "<img src=\"../../filesystem/resizeduserimages/".$images[$i]["resource"]."_resized.png\" class=\"img-responsive\" alt=\"\">";
                            ?>
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>


    <!-- Portfolio Modals -->
    <?php
        for ($i = 0; $i < sizeof($images); $i++) {
            echo "<div class=\"portfolio-modal modal fade\" id=\"portfolioModal".($i + 1)."\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">";
                ?>
                <div class="modal-content">
                    <div class="close-modal" data-dismiss="modal">
                        <div class="lr">
                            <div class="rl">
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="modal-body">
                                    <?php
                                    echo "<h2>".$images[$i]["title"]."</h2>";
                                    ?>
                                    <hr class="star-primary">
                                    <?php
                                    echo "<img src=\"../../filesystem/userimages/".$images[$i]["resource"].".".$images[$i]["extension"]."\" class=\"img-responsive img-centered\" alt=\"\">";
                                    echo "<p>".$images[$i]["description"]."</p>";
                                    ?>
                                    <ul class="list-inline item-details">
                                        <li>By:&nbsp;
                                            <?php
                                            echo "<strong>".$images[$i]["username"];
                                            ?>
                                            </strong>
                                        </li>
                                    </ul>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                    &nbsp;&nbsp;&nbsp;
                                    <iframe id="downloadframe" style="display:none"></iframe>
                                    <?php
                                    $image_route = "../../filesystem/userimages/".$images[$i]["resource"].".".$images[$i]["extension"];
                                    echo "<a type=\"button\" class=\"btn btn-info\" onclick=\"downloadImage('".$image_route."', '".$images[$i]["title"]."')\"><i class=\"fa fa-download\"></i> Download</a>\n";
                                    if (isset($_SESSION["username"]) && $_SESSION["username"] == $images[$i]["username"]) {
                                        $image_id = $images[$i]["id"];
                                        $user_id = $_SESSION["user_id"];
                                        echo "&nbsp;&nbsp;&nbsp;";
                                        echo "<a type=\"button\" class=\"btn btn-danger\" onclick=\"deleteImage('$user_id', '$image_id')\"><i class=\"fa fa-times\"></i> Delete</a>\n";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
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
</body>

</html>