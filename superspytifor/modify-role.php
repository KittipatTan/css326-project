r<?php

require_once("../check_session.php");
require_once("connect_admin.php");
require_once("check_admin.php"); 

check_privilege($_SESSION["role_id"], 0); 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"])) {
    # update the music with the corresponding id
    $genre_name = $_POST["genre_name"];
    $color = $_POST['genre_color'];
    $q_update_genre = "UPDATE genre 
                        SET   color = '$color' 
                        WHERE genre_name = '$genre_name';";

    if (!$mysqli->query($q_update_genre)) {
        echo "update failed. Error: " . $mysqli->error;
        exit(0);
    }

    header("Location: add_genre.php");
    exit(0);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!(isset($_GET["m"]) && isset($_GET["name"]))) {
        header("Location: index.php");
        exit(0);
    }

    if ($_GET["m"] == "d") {
        # delete the music with the corresponding id
        $genre_name = $_GET["name"];
        $q_delete_genre_name = "DELETE FROM genre 
                                WHERE genre_name = '$genre_name';";
        if (!$mysqli->query($q_delete_genre_name)) {
            echo "insert failed. Error: " . $mysqli->error;
            exit(0);
        }
        header("Location: add_genre.php");
        exit(0);
    } else if ($_GET["m"] != "e") {
        header("Location: add_genre.php");
        exit(0);
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
        <h3 class="topic">Modify the genre</h3>

        <form class="info-container" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
            <input type="hidden" name="genre_name" value="<?php echo $_GET["name"] ?>">
            <div class="info-edit" id="genre-add">
                <input class="info-name" type="text" value="<?php echo $_GET["name"] ?>" disabled>
                <input class="info-name" type="text" name="genre_color" placeholder="Color code" value="<?php ?>">
            </div>
            <input class="info-submit" type="submit" name="save" value="Save">
        </form>
    </main>

    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>
</body>

</html>