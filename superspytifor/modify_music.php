<?php
require_once("../check_session.php");
require_once("connect_admin.php"); 
require_once("check_admin.php"); 

check_privilege($_SESSION["role_id"], 0);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"]) && is_numeric($_POST["music_id"]) && is_numeric($_POST["music_artist_id"])) {
    # update the music with the corresponding id
    $music_id = $_POST["music_id"];
    $name = $_POST["music_name"];
    $credit = $_POST["music_name"];
    $duration = $_POST['music_duration'];
    $genre = $_POST['music_genre'];
    $artist_id = $_POST['music_artist_id'];

    $stmt = $mysqli->prepare("UPDATE music
                                SET music_name = ?,
                                    credit = ?,
                                    duration = ?,
                                    genre_name = ?,
                                    artist_id = ?
                                WHERE music_id = ? ;");
    $stmt->bind_param("ssssii", $name,$credit,$duration,$genre,$artist_id, $music_id);
    if (!$stmt->execute()) {
        echo "update failed 1. Error: ";
        exit;
    }

    header("Location: index.php");
    exit(0);
} 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    if (!(isset($_GET["m"]) && isset($_GET["id"]))) {
        header("Location: index.php");
        exit("get failed");
    }

    if ($_GET["m"] === "d" && $_GET["id"] !== "" && is_numeric($_GET["id"])) {
        # delete the music with the corresponding id
        $m_id = $_GET["id"];
        $q_delete_music = "DELETE FROM music WHERE music_id = ?;";
        $stmt = $mysqli->prepare($q_delete_music);
        $stmt->bind_param("i", $m_id);
        if (!$stmt->execute()) {
            exit("select failed");
        } 

        header("Location: index.php");
        exit("success");
        
    } else if ($_GET["m"] != "e") {
        header("Location: index.php");
        exit("get failed");
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
        <h3 class="topic">Modify the music</h3>

        <form class="info-container" action="<?php $_SERVER["SCRIPT_NAME"] ?>" method="post">
            <input type="hidden" name="music_id" value="<?php echo $_GET["id"] ?>">
            <div class="info-edit" id="music-add">
            <!-- query the info of music with the id via GET and put into the value attributes -->
                <?php
                $q_music = "SELECT music_name, credit, duration, genre_name, artist_id
                            FROM music
                            WHERE music_id =" . $_GET["id"] . ";";
                if (!$r_music = $mysqli->query($q_music)) {
                    echo "failed";
                    exit();
                }

                $music = $r_music->fetch_assoc();
                ?>

                <input class="info-name" type="text" name="music_name" value="<?php echo $music["music_name"]; ?>" placeholder="Name">
                <textarea class="info-description" name="music_credit" value="<?php echo $music["credit"]; ?>" placeholder="Credit"><?php echo $music["credit"]; ?></textarea>
                <input class="info-name" type="text" name="music_duration" value="<?php echo $music["duration"]; ?>" placeholder="Duration">
                <select class="select-filter" name="music_genre">
                <!-- query all genre and display here -->
                    <?php  
                    $q_genre = "select * from genre;";
                    if (!$result_genre = $mysqli->query($q_genre)) {
                        echo "failed " . $mysqli->error;
                    }
                    while ($rows = $result_genre->fetch_array()) {
                    ?>

                    <option value="<?php echo $rows['genre_name']; ?>" <?php echo ($music["genre_name"] === $rows["genre_name"] ? "selected" : ""); ?>>
                        <?php echo $rows['genre_name']; ?>
                    </option>
                    <?php } ?>
                </select>
                <input class="info-name" type="text" name="music_artist_id" placeholder="Artist ID" value="<?php echo $music["artist_id"]; ?>">
            </div>
            <input class="info-submit" type="submit" name="save" value="Save">
        </form>
    </main>
    
    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>
</body>

</html>