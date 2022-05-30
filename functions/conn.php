<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "2speedy";
$dbport = "3306";

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $dbport);


// Check connection
if ($conn->connect_error) {
  // Si hay error muere el programa y muestra el error
  die('Connect Error (' . $conn->connect_errno . ') '
          . $conn->connect_error);
} else {
  // Si establece conexión muestra el aviso que estableció conexión
  // echo("<h1>Conexión satisfactoria</h1>");
}

// Set Charset to UTF8 
if (!$conn->set_charset("utf8")) {
  printf("Error loading character set utf8: %s\n", $conn->error);
  exit();
}

/* Tell mysqli to throw an exception if an error occurs */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);



?>


