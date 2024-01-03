<?php

require_once("../check_session.php");
require_once("connect_admin.php"); 
require_once("check_admin.php"); 
check_privilege($_SESSION["role_id"], 0);

if (!($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add']))) {
    header("location: add_genre.php");
    exit();
}
  
$genrename = $_POST['genre_name'];
$colorcode = $_POST['genre_color'];



$q3 = "INSERT INTO genre (genre_name,color) VALUES ('$genrename','$colorcode');";
$result = $mysqli->query($q3);
if (!$result) {
  echo "insert failed. Error: " . $mysqli->error;
  exit();
}

header("Location: add_genre.php");
exit(0);

?>