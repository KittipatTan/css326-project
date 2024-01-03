<?php 
require_once("../check_session.php");
require_once("connect_admin.php");
require_once("check_admin.php"); 

check_privilege($_SESSION["role_id"], 0);

if (isset($_POST['add']) && isset($_FILES['music_song'] ) && isset($_FILES['music_img']) && !empty($_POST["music_name"]) && is_numeric($_POST["music_artist_id"])) {
    $audio_name = $_FILES['music_song']['name'];
    $audio_tmp_name = $_FILES['music_song']['tmp_name'];
    $error = $_FILES['music_song']['error'];

    $credit = $_POST['music_credit'];
    $duration = $_POST['music_duration'];
    $music_name = $_POST['music_name'];
    $music_genre = $_POST['music_genre'];
    $artist_id = $_POST['music_artist_id'];

	$img_name = $_FILES['music_img']['name'];
	$img_size = $_FILES['music_img']['size'];
	$img_tmp_name = $_FILES['music_img']['tmp_name'];
	$error = $_FILES['music_img']['error'];

	if ($error === 0) {
		if ($img_size > 25000000) {
			$em = "Sorry, your file is too large.";
		    header("Location: index.php?error=$em");
			exit();
		}else {
			$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
			$img_ex_lc = strtolower($img_ex);

            $audio_ext = pathinfo($audio_name, PATHINFO_EXTENSION);
            $audio_ext_lc = strtolower($audio_ext);
    
            $allowed_exts = ["mp3", "wav"];
			$allowed_exs = ["jpg", "jpeg", "png"]; 

            if (in_array($audio_ext_lc, $allowed_exts)) {
                $new_audio_name = uniqid("audio-", true). '.'.$audio_ext_lc;
                $audio_upload_path = "../uploadsong/".$new_audio_name;
                move_uploaded_file($audio_tmp_name, $audio_upload_path);
            }

			if (in_array($img_ex_lc, $allowed_exs)) {
				$new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
				$img_upload_path = '../profilepicture/'.$new_img_name;
				move_uploaded_file($img_tmp_name, $img_upload_path);

                $sql = "INSERT INTO music (credit,duration,genre_name,artist_id,music_name, path, image_path) 
                VALUES('$credit','$duration','$music_genre',$artist_id,' $music_name','$new_audio_name','$new_img_name');";
                $result = $mysqli->query($sql);
				header("Location: index.php");
			} else {
				$em = "You can't upload files of this type";
		        header("Location: index.php?error=$em");
			}
		}
	}

}else {
	header("Location: index.php");
}
?>
