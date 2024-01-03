<?php

require_once("../check_session.php");
require_once("connect_admin.php"); 
require_once("check_admin.php");

check_privilege($_SESSION["role_id"], 0);

if (!($_SERVER["REQUEST_METHOD"] === "POST") && isset($_POST['add']) && $_POST["artist_name"] !== "") {
    header("location: add_artist.php");
    exit(0);
}

$artist_name = $_POST['artist_name'];
$artist_about = $_POST['artist_about'];
$q_artist = "INSERT INTO artist (name, about) VALUES (?, ?);";

if (isset($_FILES["artist_img"]) && $_FILES["artist_img"]["name"] !== "") {
  $img_name = $_FILES['artist_img']['name'];
  $img_size = $_FILES['artist_img']['size'];
  $tmp_name = $_FILES['artist_img']['tmp_name'];
  $error = $_FILES['artist_img']['error'];

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
        // Insert into Database
        $q_artist = "INSERT INTO artist (name,about,image_path) VALUES (?, ?,'$new_img_name');";
      } else {
        $em = "You can't upload files of this type";
        header("Location: index.php?error=$em");
      }
    }
  } else {
      $em = "unknown_error_occurred!";
      header("Location: index.php?error=$em+$error");
  }
}

$stmt = $mysqli->prepare($q_artist);
$stmt->bind_param("ss", $artist_name, $artist_about);
if(!$stmt->execute()) {
  exit("insert failed");
}
header("location: add_artist.php");

// $artist_name = $_POST['artist_name'];
// $artist_about = $_POST['artist_about'];

// if ($error === 0) {
//   if ($img_size > 1250000000) {
//     $em = "Sorry, your file is too large.";
//       header("Location: index.php?error=$em");
//   } else {
    
//     $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
//     $img_ex_lc = strtolower($img_ex);
//     $allowed_exs = array("jpg", "jpeg", "png"); 

//     if (in_array($img_ex_lc, $allowed_exs)) {
//       $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
//       $img_upload_path = '../profilepicture/'.$new_img_name;
//       move_uploaded_file($tmp_name, $img_upload_path);
//       // Insert into Database
//       $q3 = "INSERT INTO artist (name,about,image_path) VALUES ('$artist_name ','$artist_about','$new_img_name');";
//       $result = $mysqli->query($q3);
//       if (!$result) {
//         echo "insert failed. Error: " . $mysqli->error;
//         exit();
//       }
      
//       header("Location: add_artist.php");

//     } else {
//       $em = "You can't upload files of this type";
//           header("Location: index.php?error=$em");
//     }
//   }
// } else {
//   $em = "unknown_error_occurred!";
//       header("Location: index.php?error=$em+$error");
//     }

?>