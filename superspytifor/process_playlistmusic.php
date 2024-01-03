<?php
require_once("../check_session.php");
require_once("connect_admin.php");
require_once("check_admin.php");

check_privilege($_SESSION["role_id"], 0);

if (!($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["m"]) && is_numeric($_GET["p_id"]) && is_numeric($_GET["m_id"]))){
    header("location: index.php");
    exit();
}

$mode = $_GET["m"];
$p_id = $_GET["p_id"];
$m_id = $_GET["m_id"];

if ($mode === "a") {
    $stmt = $mysqli->prepare("INSERT INTO playlist_music(playlist_id, music_id, added_date) VALUES (?, ?, CURDATE());");
    $stmt->bind_param("ii", $p_id ,$m_id);
    
    if ((!$stmt->execute() && $stmt->errno == 1062)) {
        $stmt = $mysqli->prepare("DELETE FROM playlist_music
                                WHERE playlist_id = ?
                                AND music_id = ?");
        $stmt->bind_param("ii", $p_id, $m_id);

        if (!$stmt->execute()) {
            echo "failed";
            exit();
        }
        header("location: ../playlist.php?playlist_id=$p_id");
        exit();
    }

} else if ($mode === "d") {
    // delete music from that playlist
    $stmt = $mysqli->prepare("DELETE FROM playlist_music
                                WHERE playlist_id = ?
                                AND music_id = ?");
    $stmt->bind_param("ii", $p_id, $m_id);

    if (!$stmt->execute()) {
        echo "failed";
        exit();
    }

} else {
    header("location: index.php");
    exit();
}

header("location: ../playlist.php?playlist_id=$p_id");

?>