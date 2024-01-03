<?php
require_once("check_session.php");
require_once("library.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"])) {

    require_once('connect.php');

    $uid = $_SESSION["uid"];

    if (isset($_POST["user_name"])) {
        $username = $_POST["user_name"];

        $q_username = "UPDATE user SET name = ? WHERE uid = ?;";

        $stmt = $mysqli->prepare($q_username);
        $stmt->bind_param("si", $username, $uid);

        if (!$stmt->execute()) {
            echo "failed";
            exit();
        }

        header("location: user.php");
        exit();
    }

    if (isset($_POST["playlist_name"])) {
        $playlist_id = $_POST["playlist_id"];

        if (!is_numeric($playlist_id)) {
            header("location: index.php");
            exit("invalid ID");
        }
    
        $q_playlist = "SELECT u_id FROM playlist WHERE playlist_id = ?";
        $stmt = $mysqli->prepare($q_playlist);
        $stmt->bind_param("i", $playlist_id);
        $stmt->execute();
        $r_uid = $stmt->get_result();
        
        if ($r_uid->fetch_assoc()["u_id"] !== $uid) {
            exit("insufficient owner");
        }

        $playlist_name = $_POST["playlist_name"];
        $playlist_description = $_POST["playlist_description"];
        $ispublic = ($_POST['isPrivate'] ?? 0);

        $changeplaylist = "update playlist set 
                            playlist_name = ?, 
                            description = ?,
                            ispublic = ?
                            where playlist_id = ?;";

        if (isset($_FILES["playlist_img"]) && $_FILES['playlist_img']['name'] !== "") {
            $img_name = $_FILES['playlist_img']['name'];
            $img_size = $_FILES['playlist_img']['size'];
            $tmp_name = $_FILES['playlist_img']['tmp_name'];
            $error = $_FILES['playlist_img']['error'];
    
            if ($error != 0) {
                echo "error";
                exit();
            }
            
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exs = array("jpg", "jpeg", "png");
    
            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                $img_upload_path = 'profilepicture/'.$new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
            }

            $changeplaylist = "update playlist set 
                            playlist_name = ?, 
                            description = ?,
                            ispublic = ?, 
                            image_path = '$new_img_name' 
                            where playlist_id = ?;";
        }

        $stmt = $mysqli->prepare($changeplaylist);
        $stmt->bind_param("ssii", $playlist_name, $playlist_description, $ispublic, $playlist_id);

        if (!$stmt->execute()) {
            echo "failed";
            exit();
        }

        header("location: playlist.php?playlist_id=" . $playlist_id);
        exit();
    }

} else if (
    $_SERVER["REQUEST_METHOD"] === "GET"
    && isset($_GET["m"])
    && isset($_GET["id"])
    && $_GET["m"] === "d"
    && $_GET["id"] !== ""
) {

    require_once('connect.php');

    $playlist_id = $_GET["id"];
    $uid = $_SESSION["uid"];

    check_playlist_owner($playlist_id, $uid);

    $stmt = $mysqli->prepare("DELETE FROM playlist WHERE playlist_id = ?;");
    $stmt->bind_param("i", $playlist_id);

    if (!$stmt->execute()) {
        echo "failed";
        exit();
    }

    header("location: index.php");
    exit(0);

} else {
    header("location: index.php");
    exit("invalid request");
}
