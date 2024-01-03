<?php

require_once("check_session.php");

if (!($_SERVER["REQUEST_METHOD"] === "GET" && !empty($_GET["d"]) && is_numeric($_GET["m_id"]))) {
    header("location: index.php");
    exit();
}

require_once("connect.php");

$uid = $_SESSION["uid"];
$music_id = $_GET["m_id"];

$stmt = $mysqli->prepare("INSERT INTO liked VALUES (?, $uid, CURDATE());");
$stmt->bind_param("i", $music_id);

if (!$stmt->execute() || $stmt->errno == 1062) {
    $q_delete_liked = "DELETE FROM liked WHERE uid = $uid AND music_id = ?";
    $stmt = $mysqli->prepare($q_delete_liked);
    $stmt->bind_param("i", $music_id);

    if(!$stmt->execute()) {
        exit("delete failed: liked");
    }
}

header("location: " . $_GET["d"]);

?>