<?php 

if (!($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['signup']) && $_POST['pass'] === $_POST['conpass'])) {
  header("location: register.html");
  exit();
}

require_once('connect.php');

$stmt = $mysqli->prepare("INSERT INTO user (name, email, user_password, role_id) VALUES (?, ?, ?, 3);");
$stmt->bind_param("sss", $username, $email, $hashedpass);

$email = $_POST['email'];
$username = $_POST['username'];
$pass = $_POST["pass"];
$hashedpass = password_hash($pass, PASSWORD_ARGON2ID);

if (!$stmt->execute()) {
  echo "insert failed.";
  exit();
}

header("Location: login.php");
exit(0);

?>

