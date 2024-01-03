<?php

function check_playlist_owner($p_id, $u_id) {
    if (!(is_numeric($p_id) && is_numeric($u_id))) {
        header("location: index.php");
        exit("invalid IDs");
    }

    global $mysqli;

    $q_playlist = "SELECT u_id 
                   FROM playlist
                   WHERE playlist_id = ?;";

    $stmt = $mysqli->prepare($q_playlist);
    $stmt->bind_param("i", $p_id);

    if (!$stmt->execute()) {
        echo "failed";
        exit("statement execution failed");
    }

    $r_li = $stmt->get_result();

    $playlist_uid = $r_li->fetch_assoc()["u_id"];
    if ($playlist_uid !== $u_id) {
        header("location: index.php");
        exit("not owner");
    }
}

?>