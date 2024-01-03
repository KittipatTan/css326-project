<?php
require_once("check_session.php");
require_once("connect.php");
require_once("library.php");

if (!($_SERVER["REQUEST_METHOD"] === "GET" && !empty($_GET["m"]) && is_numeric($_GET["p_id"]) && is_numeric($_GET["m_id"]))){
    header("location: index.php");
    exit();
}

$mode = $_GET["m"];
$p_id = $_GET["p_id"];
$m_id = $_GET["m_id"];

check_playlist_owner($p_id, $_SESSION["uid"]);

if ($mode === "a") {
    $stmt = $mysqli->prepare("INSERT INTO playlist_music (playlist_id, music_id, added_date) VALUES (?, ?, CURDATE());");
    $stmt->bind_param("ii", $p_id ,$m_id);
    
    if (!($stmt->execute() || $stmt->errno == 1062)) {
        header("location: playlist.php?playlist_id=$p_id");
        exit();
    }

} else if ($mode === "d") {
    // delete music from that playlist
    $stmt = $mysqli->prepare("DELETE FROM playlist_music
                                WHERE playlist_id = ?
                                AND music_id = ?;");
    $stmt->bind_param("ii", $p_id, $m_id);

    if (!$stmt->execute()) {
        exit("delete failed: playlistmusic");
    }

} else {
    header("location: index.php");
    exit();
}

header("location: playlist.php?playlist_id=$p_id");

?>