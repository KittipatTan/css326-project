<?php

require_once("check_session.php");

if (!($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["d"]))) {
    header("location: index.php");
    exit;
}

require_once("connect.php");

$u_id = $_SESSION["uid"];

$q_playlist = "SELECT COUNT(playlist_id) AS amount
               FROM playlist
               WHERE u_id = $u_id;";

if (!$r_playlist = $mysqli->query($q_playlist)) {
    echo "failed";
    exit();
}

if ($r_playlist->num_rows > 0) {
    $num = $r_playlist->fetch_assoc()["amount"] + 1;
} else {
    $num = 1;
}

$playlist_name = "My Playlist #$num";
$description = "";
$ispublic = 1;

$q_insert = "INSERT INTO playlist (playlist_name, description, ispublic, u_id, created_date)
             VALUES ('$playlist_name', '$description', $ispublic, $u_id, CURDATE());";

$stmt = $mysqli->prepare($q_insert);
        
            if (!$stmt->execute()) {
                echo "select failed";
                exit();
            } 

            $addplaylist = $stmt->get_result();
header("location: " . $_GET["d"]);

?>