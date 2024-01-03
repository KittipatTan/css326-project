<?php

require_once("../check_session.php");
require_once("connect_admin.php"); 
require_once("check_admin.php"); 

check_privilege($_SESSION["role_id"], 0);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"]) && is_numeric($_POST["artist_id"])) {
    # update the music with the corresponding id
    $artist_id = $_POST["artist_id"];
    $artist_name = $_POST["artist_name"];
    $artist_about = $_POST["artist_about"];

    $stmt = $mysqli->prepare("UPDATE artist 
                            SET name = ?, 
                                about = ? 
                                WHERE artist_id = ? ;");
                                
    $stmt->bind_param("ssi", $artist_name, $artist_about, $artist_id);

    if (!$stmt->execute()) {
        echo "update failed. Error: "; 
        exit("HELP");
    }

    header("Location: add_artist.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!(isset($_GET["m"]) && isset($_GET["id"]))) {
        header("Location: index.php");
        exit();
    }

    if ($_GET["m"] == "d") {
        # delete the music with the corresponding id
        $artist_id = $_GET["id"];

        $stmt = $mysqli->prepare("DELETE FROM artist WHERE artist_id = ?;");           
        $stmt->bind_param("i", $artist_id);
    
        if (!$stmt->execute()) {
            echo "delete failed. Error222: " ;
            exit();
        }
        header("Location: add_artist.php");
        exit();
    } else if ($_GET["m"] != "e") {
        header("Location: index.php");
        exit();
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
        <h3 class="topic">Modify the artist</h3>

        <form class="info-container" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
            <input type="hidden" name="artist_id" value=<?php echo $_GET["id"] ?>>
            <div class="info-edit" id="artist-add">
                <input class="info-name" type="text" name="artist_name" placeholder="Name">
                <textarea class="info-description" name="artist_about" placeholder="Artist about"></textarea>
            </div>
            <input class="info-submit" type="submit" name="save" value="Save">
        </form>
    </main>

    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>
</body>

</html>