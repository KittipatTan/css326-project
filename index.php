<?php 
require_once('connect.php');
require_once('check_session.php'); 
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Spytifor - Music Streaming Platform</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/musicplayer.css">
</head>

<body>
    <div class="popup"></div>
    <header>
        <button id="popup-bt-user" class="popup-bt">
            <svg class="logo-user">
                <use href="img/logo.svg#logo-user" />
            </svg>
        </button>
        <div id="popup-user-logo" class="popup-container">
            <a href="user.php" class="popup-item">Profile</a>
            <a href="changepass.php" class="popup-item">Change password</a>
            <hr>
            <a href="logout.php" class="popup-item">Log out</a>
        </div>
    </header>
    <nav>
        <ul class="nav-bar">
            <div id="nav-main-box" class="nav-box">
                <li id="logo-home" class="nav-item">
                    <svg class="logo-spytifor-home">
                        <use href="img/logo.svg#logo-spytifor-white-text" />
                    </svg>
                </li>
                <li class="nav-item active">
                    <a href="index.php" class="nav-link">
                        <svg class="nav-logo">
                            <use href="img/logo.svg#logo-home-active"/>
                        </svg>
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="search.php" class="nav-link">
                        <svg class="nav-logo">
                            <use href="img/logo.svg#logo-search"/>
                        </svg>
                        Search
                    </a>
                </li>
            </div>
            <div class="nav-box">
                <li id="your-library-bt" class="nav-item">
                    <button class="nav-button">
                        <svg class="nav-logo">
                            <use href="img/logo.svg#logo-library"/>
                        </svg>
                        Your Library
                    </button>
                    <a id="add-playlist-bt" class="nav-button" href="add_user_playlist.php?d=<?php echo $_SERVER["SCRIPT_NAME"] ?>">
                        <span class="add-bt"></span>
                        <span class="add-bt"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="liked_song.php" class="nav-link">
                        <svg class="nav-logo">
                            <use href="img/logo.svg#logo-likedsongs"/>
                        </svg>
                        Liked Songs
                    </a>
                </li>

                <?php
                $userid = $_SESSION["uid"];

                $stmt = $mysqli->prepare("SELECT * FROM playlist WHERE u_id = ?;");
                $stmt->bind_param("i", $userid);
                $stmt->execute();
                $getplaylist = $stmt->get_result();

                if (!$getplaylist) {
                    echo "failed ";
                }

                while ($rows = $getplaylist->fetch_assoc()) {
                    echo  '<li class="nav-item">
                                <a href="playlist.php?playlist_id=' . $rows['playlist_id'] . '" class="nav-link">
                                <img class="nav-logo" src="profilepicture/' . $rows["image_path"] . '">'
                                . $rows['playlist_name'] .
                                '</a>
                           </li>';
                }
                ?>
            </div>
        </ul>
    </nav>
    <main>
        <h3 class="topic">Spytifor Playlists</h3>
        <div class="card-container">
            <!--query all playlists from database-->
            <?php
            
            $q2 = "select playlist_name, 
                          name, 
                          playlist_id, 
                          playlist.image_path as p_image_path 
                  from playlist 
                  left outer join user 
                  on playlist.u_id = user.uid;";

            $stmt = $mysqli->prepare($q2);
        
            if (!$stmt->execute()) {
                echo "select failed";
                exit();
            } 

            $getplaylist = $stmt->get_result();
           
            while ($rows = $getplaylist->fetch_array()) {
            ?>
                <a class="card" href="playlist.php?playlist_id=<?php echo $rows['playlist_id'] ?>">
                    <img class="card-img" src="profilepicture/<?php echo $rows["p_image_path"] ?>" alt="">
                    <div class="card-text">
                        <p class="card-title"><?php echo $rows['playlist_name'] ?></p>
                        <p class="card-description"><?php echo $rows['name'] ?? "Spytifor" ?></p>
                    </div>
                </a>

            <?php } ?>
        </div>
    </main>

    <?php require_once('playsong.php'); ?>

    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>

    <script src="js/popup.js"></script>
</body>

</html>