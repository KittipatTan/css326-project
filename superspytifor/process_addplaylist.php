<?php

require_once("../check_session.php");
require_once("connect_admin.php"); 
require_once("check_admin.php"); 

check_privilege($_SESSION["role_id"], 0);

if (!($_SERVER["REQUEST_METHOD"]) === "POST" && isset($_POST['add']) && isset($_FILES['playlist_img'] )) {
    header("location: add_playlist.php");
    exit(0);
}

$img_name = $_FILES['playlist_img']['name'];
$img_size = $_FILES['playlist_img']['size'];
$tmp_name = $_FILES['playlist_img']['tmp_name'];
$error = $_FILES['playlist_img']['error'];

$playlist_name = $_POST['playlist_name'];
$playlist_description = $_POST['playlist_description'];
$is_private = $_POST["isPrivate"] ?? 0;

if ($error === 0) {
  if ($img_size > 1250000000) {
    $em = "Sorry, your file is too large.";
      header("Location: index.php?error=$em");
  } else {
    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
    $img_ex_lc = strtolower($img_ex);
    $allowed_exs = array("jpg", "jpeg", "png");
    if (in_array($img_ex_lc, $allowed_exs)) {
      $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
      $img_upload_path = '../profilepicture/'.$new_img_name;
      move_uploaded_file($tmp_name, $img_upload_path);

  $q3 = "INSERT INTO playlist (playlist_name, description, isPublic, u_id, created_date,image_path) 
        VALUES ('$playlist_name','$playlist_description',$is_private, NULL, curdate(),'$new_img_name');";
  $result = $mysqli->query($q3);
  
  if (!$result) {
    echo "insert failed. Error: " . $mysqli->error;
    exit(0);
  }
  
  header("Location: add_playlist.php");
} else {
  $em = "You can't upload files of this type";
      header("Location: index.php?error=$em");
}
}
} else {
$em = "unknown_error_occurred!";
  header("Location: index.php?error=$em+$error");
}


?>