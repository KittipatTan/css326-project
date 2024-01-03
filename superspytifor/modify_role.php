<?php

require_once("../check_session.php");
require_once("connect_admin.php");
require_once("check_admin.php"); 

check_privilege($_SESSION["role_id"], 1); 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"]) && is_numeric($_POST["uid"]) && is_numeric($_POST["role_id"]) && $_POST["role_id"] != 1) {
    # update the music with the corresponding id
    $userid = $_POST["uid"];
    $role = $_POST['role_id']; 

    $stmt = $mysqli->prepare("UPDATE user 
                                SET   role_id = ? 
                                WHERE uid = ? ;");
    $stmt->bind_param("ii", $role,$userid);

    if (!$stmt->execute()) {
        echo "update failed. Error: " . $mysqli->error;
        exit();
    }

    header("Location: manage_role.php");
    exit(0);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!(isset($_GET["m"]) && is_numeric($_GET["id"]))) {
        header("Location: index.php");
        exit();
    }

    // check for valid user id
    $userid = $_GET["uid"];

    if ($_GET["m"] == "d") {
        # delete the music with the corresponding id
        $q_delete_user = "DELETE FROM user
                          WHERE uid = '$userid';";

        if (!$mysqli->query($q_delete_user)) {
            echo "delete failed. Error: " . $mysqli->error;
            exit();
        }

        header("Location: manage_role.php");
        exit(0);

    } else if ($_GET["m"] != "e") {
        header("Location: manage_role.php");
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
        <h3 class="topic">Modify the role</h3>

        <form class="info-container" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
            <input type="hidden" name="uid" value="<?php echo $_GET["id"] ?>">
            <div class="info-edit" id="genre-add">
                <?php 
                if (!is_numeric($_GET["id"])) {
                    exit("invalid id");
                }
                $userid = $_GET["id"];
                $q = "select role_id from user where uid = ?;";
                $stmt = $mysqli->prepare($q);
                $stmt->bind_param("i", $userid);
                
                if (!$stmt->execute()) {
                    exit("failed");
                }

                $getq = $stmt->get_result();

                while ($rows = $getq->fetch_array()) {
                    ?>
                <input class="info-name" type="text" name="role_id" value="<?php echo $rows["role_id"] ?>">
              <?php  }
                ?>
            </div>
            <input class="info-submit" type="submit" name="save" value="Save">
        </form>
    </main>

    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>
</body>

</html>