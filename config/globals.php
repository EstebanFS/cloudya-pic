<?php

function showMessage($msg) {
  $msg = str_replace("\n", " ", $msg);
  echo "<script language=\"JavaScript\">\n";
  echo "alert('". $msg . "');\n";
  echo "</script>\n";
}

function loadPage($where) {
  echo "<script language=\"JavaScript\">\n";
  echo "document.location='" . $where . "';\n";
  echo "</script></html>\n";
  exit;
}

?>