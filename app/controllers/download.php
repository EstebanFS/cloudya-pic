<?php 
  if (isset($_GET["route"]) && isset($_GET["filename"])) {
    $file = $_GET["route"];
    $name = strtolower(str_replace(' ', '_', $_GET["filename"]));
    $name = preg_replace("/[^a-zA-Z0-9_]+/", "", $name);
    if (file_exists($file)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.$name.'.png');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      //header('Content-Length: ' . filesize($file));
      readfile($file);
      exit;
    }
  }
?>