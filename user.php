<?php require_once('connect.php'); ?>
<?php require_once('check_session.php'); ?>

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
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <svg class="nav-logo">
                            <use href="img/logo.svg#logo-home"/>
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
                    $q2 = "select playlist_id, 
                                  playlist_name,
                                  image_path  
                            from playlist
                            where u_id = $userid;";
                    $getplaylist = $mysqli->query($q2);
                    if (!$getplaylist) {
                        echo "failed" . $mysqli->error;
                        exit();
                    }
                    while ($rows = $getplaylist->fetch_array()) {
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

    <div id="user-edit-modal" class="modal">
        <div class="modal-content">
            <h2 class="modal-topic">Profile details</h2>
            <button class="modal-close-bt" onclick="closeModal()">Close</button>
            <form class="info-container" action="process_edit.php" method="post" enctype="multipart/form-data">
                <input class="img-upload" type="file" name="user_img" accept="image/png, image/jpeg">
                <div class="info-edit">
                    <input class="info-name" type="text" name="user_name" placeholder="User name" value="<?php ?>" >
                </div>
                <input class="info-submit" type="submit" name="save" value="Save">
            </form>
        </div>
    </div>

    <main>
        <section class="section-info">
            <img src="./profilepicture/default_profile_pic.png" alt="" class="profile-img">
            <div class="section-name">
                <p>Profile</p>

                <?php
                $getusername = "select name from user where uid = $userid;";
                $get_username = $mysqli->query($getusername);
                if (!$get_username) {
                    echo "failed " . $mysqli->error;
                }
                while ($rows = $get_username->fetch_array()) {
                ?>

                    <h1 id="user-name" onclick="showModal()"><?php echo ($rows["name"] == "" ? "Username" : $rows["name"]) ?></h1>

                <?php } ?>

                <?php
                $count = "select count(playlist_id) as amount from playlist where u_id = $userid;";
                $getplaylistcount = $mysqli->query($count);
                if (!$getplaylistcount ) {
                    echo "failed " . $mysqli->error;
                }
                while ($rows = $getplaylistcount ->fetch_array()) {
                ?>
                    <p class="count"><?php echo $rows['amount'] ?> Playlist(s)</p>

                <?php } ?>
            </div>
        </section>
        <section class="user-playlist">
            <h3 class="topic">User Playlists</h3>
            <div class="card-container">

            <?php
            $q = 'select playlist_id, playlist_name,image_path, description from playlist WHERE u_id =' . $userid ;

            if($result=$mysqli->query($q)){
             

            } else {
                echo 'Query error: '.$mysqli->error;
                exit;
            }
        
            while($row=$result->fetch_assoc()) {
            ?>
                    <a class="card" href="playlist.php?playlist_id=<?php echo $row['playlist_id'] ?>">
                        <img class="card-img" src="profilepicture/<?php echo $row['image_path']?>" alt="">
                        <div class="card-text">
                            <p class="card-title"><?php echo $row["playlist_name"] ?></p>
                            <p class="card-description"><?php echo $row["description"] ?></p>
                        </div>
                    </a>
            <?php }?>

            </div>
        </section>
    </main>

   
    <?php require_once('playsong.php'); ?>
    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>

    <script>
        let modal = document.getElementById("user-edit-modal");
    </script>
    <script src="js/modal.js"></script>
    <script src="js/popup.js"></script>
</body>

</html>