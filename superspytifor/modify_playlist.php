<?php

require_once("../check_session.php");
require_once("connect_admin.php"); 
require_once("check_admin.php"); 
check_privilege($_SESSION["role_id"], 0);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    # update the music with the corresponding id
    $playlist_id = $_POST["playlist_id"];
    $playlist_name = $_POST["playlist_name"];
    $play_isprivate = $_POST["isPrivate"] ?? 0;
    $playlist_description = $_POST["playlist_description"];

$stmt = $mysqli->prepare("UPDATE playlist
SET   description = ?,
      playlist_name = ?,
      ispublic = ?
WHERE playlist_id = ?;");
$stmt->bind_param("ssii", $playlist_description,$playlist_name,$play_isprivate,$playlist_id);
$stmt->execute();
$q_update_playlist = $stmt->get_result();

    if (!$stmt->execute()) {
        echo "update failed. Error: " . $mysqli->error;
        exit;
    }

    header("Location: modify_playlist.php");
    exit;
} 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    if (!(isset($_GET["m"]) && isset($_GET["id"]))) {
        header("Location: index.php");
        exit;
    }

    if ($_GET["m"] == "d") {
        # delete the music with the corresponding id
        $play_id = $_GET["id"];
        $q_delete_playlist = "DELETE FROM playlist
                              WHERE playlist_id = $play_id;";
        if (!$mysqli->query($q_delete_playlist)) {
            echo "delete failed. Error: " . $mysqli->error;
        exit;
        }
        header("Location: index.php");
        exit;
    } else if ($_GET["m"] != "e") {
        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>Spytifor - Music Streaming Platform</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <header>
        <button class="popup-bt">
            <svg class="logo-user">
                <use href="../img/logo.svg#logo-user" />
            </svg>
        </button>
    </header>
    <nav>
        <ul class="nav-bar">
            <div id="nav-main-box" class="nav-box">
                <li id="logo-home" class="nav-item">
                    <svg class="logo-spytifor-home">
                        <use href="../img/logo.svg#logo-spytifor-white-text" />
                    </svg>
                </li>
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        Add music
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add_artist.php" class="nav-link">
                        Add artist
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add_playlist.php" class="nav-link">
                        Add playlist
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add_genre.php" class="nav-link">
                        Add genre
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_role.php" class="nav-link">
                        Manage role
                    </a>
                </li>
            </div>
        </ul>
    </nav>
    <main>
        <h3 class="topic">Modify the playlist</h3>

        <form class="info-container" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
            <input type="hidden" name="playlist_id" value="<?php echo $_GET["id"] ?>">
            <div class="info-edit" id="playlist-add">
                <input class="info-name" type="text" name="playlist_name" <?php ?> placeholder="Name">
                <textarea class="info-description" name="playlist_description" value=<?php ?> placeholder="Playlist Description"></textarea>
                <input class="info-checkbox" id="checkbox-isprivate" type="checkbox" name="isPrivate" value=<?php  ?>>
                <label for="checkbox-isprivate">Private playlist</label>
            </div>
            <input class="info-submit" type="submit" name="save" value="Save">
        </form>
    </main>
    
    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>
</body>

</html>