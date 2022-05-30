<?php

// Cleans the input data of a form to prevent code injection
function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


/// if log out
if(!empty($_GET['close'])) {

  session_destroy();
  $catalogPath = "http://" . $_SERVER['HTTP_HOST'] . "/2speedy/index.php";

  header("location: $catalogPath");
  exit;
}

?>