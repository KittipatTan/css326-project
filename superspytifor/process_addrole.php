<?php
require_once("../check_session.php");
require_once("connect_admin.php"); 
require_once("check_admin.php"); 

check_privilege($_SESSION["role_id"], 1);

if (!($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add']))) {
    header("location: modify_role.php");
    exit();
  }
  
$rolename = $_POST['role_name'];


$q3 = "INSERT INTO role (role_name) VALUE ('$rolename');";
$result = $mysqli->query($q3);
if (!$result) {
  echo "insert failed. Error: " . $mysqli->error;
  exit();
}

header("Location: modify_role.php");

?>