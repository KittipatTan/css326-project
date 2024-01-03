<?php 
require_once('connect.php');
require_once('check_session.php'); 

if (!isset($_GET["playlist_id"]) || !is_numeric($_GET["playlist_id"]) || $_GET["playlist_id"] === "") {
    header("location: index.php");
    exit("invalid ID");
}

$playlist_id = $_GET['playlist_id'];
$playlistname = "SELECT 
                    playlist_name, 
                    playlist.image_path as p_image_path, 
                    u_id,
                    name
                FROM playlist 
                LEFT OUTER JOIN user
                ON playlist.u_id = user.uid
                WHERE playlist_id = ?;";

$stmt = $mysqli->prepare($playlistname);
$stmt->bind_param("i", $playlist_id);
if (!$stmt->execute()) {
    echo "failed " . $mysqli->error;
    exit();
}

$getplaylistname = $stmt->get_result();

if ($getplaylistname->num_rows <= 0) {
    header("location: index.php");
    exit("invalid ID");
}

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
                    <a id="add-playlist-bt" class="nav-button" href="add_user_playlist.php?d=<?php echo $_SERVER["SCRIPT_NAME"] ?>?playlist_id=<?php echo $_GET["playlist_id"] ?>">
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
                $userid = $_SESSION['uid'];
                $q2 = "select * from playlist where u_id = $userid";
                $getplaylist = $mysqli->query($q2);
                if (!$getplaylist) {
                    echo "failed " . $mysqli->error;
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
    <div id="playlist-edit-modal" class="modal">
        <div class="modal-content">
            <h2 class="modal-topic">Edit details</h2>
            <button class="modal-close-bt" onclick="closeModal()">Close</button>
            <form class="info-container" action="process_edit.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="playlist_id" value="<?php echo $playlist_id ?>">
                <input class="img-upload" type="file" name="playlist_img" accept="image/png, image/jpeg">

                <div class="info-edit">
                    <input class="info-name" type="text" name="playlist_name" placeholder="Playlist name" value="">
                    <textarea class="info-description" name="playlist_description" placeholder="Add an optional description" value=""></textarea>
                    <input class="info-checkbox" id="checkbox-isprivate" type="checkbox" name="isPrivate" value="1">
                    <label for="checkbox-isprivate">Private playlist</label>
                </div>
                
                <input class="info-submit" type="submit" name="save" value="Save">
            </form>
        </div>
    </div>
    <main>
        <section class="section-info">
            <?php
            

            while ($rows = $getplaylistname->fetch_assoc()) {
            ?>

            <img src="profilepicture/<?php echo $rows["p_image_path"] ?>" alt="" class="playlist-img">
            <div class="section-name">
                    <p>Playlist</p>
                    <h1><?php echo $rows['playlist_name'] ?></h1>

                    <?php if ($_SESSION["uid"] === $rows["u_id"]) {?>
                    <div class="playlist-owner">
                        <p id="playlist-edit" class="info-modify" onclick="showModal()">Edit</p>
                        <a id="playlist-delete" class="info-modify" href="process_edit.php?m=d&id=<?php echo $playlist_id; ?>">Delete</a>
                    </div>
                    <?php } ?>
                    
                    <p class="playlist-owner-name">By <?php echo ($rows["name"] ?? "Spytifor"); ?></p>
            </div>
            <?php } ?>
        </section>
        <section class="playlist-song">
            <!-- query all songs of this artist and display here -->
                <div class="song-container">
                    <table class="song-table">
                        <tr class="tr-header">
                            <th class="th-song-no">#</th>
                            <th class="th-song-title">Title</th>
                            <th class="th-song-date">Date added</th>
                            <th class="th-song-like"></th>
                            <th class="th-song-duration">Duration</th>
                            <th class="th-song-delete"></th>
                        </tr>

                        <?php
                        $playlistsong = "SELECT * , 
                                                music.image_path as m_image_path,
                                                music.music_id as m_id
                                        FROM playlist_music 
                                        inner join music 
                                        on playlist_music.music_id = music.music_id 
                                        inner join playlist 
                                        on playlist.playlist_id = playlist_music.playlist_id 
                                        inner join artist 
                                        on artist.artist_id = music.artist_id
                                        where playlist.playlist_id = ?;";
                        $stmt = $mysqli->prepare($playlistsong);
                        $stmt->bind_param("i", $playlist_id);
                        
                        if (!$stmt->execute()) {
                            echo "failed " . $mysqli->error;
                        }

                        $getplaylistsong = $stmt->get_result();

                        $no = 1;
                        while ($rows = $getplaylistsong->fetch_array()) {
                        ?>

                        <tr class="tr-data">
                            <td class="td-song-no"><?php echo $no ?></td>
                            <td class="td-song-title">
                                <img class="song-img" src="profilepicture/<?php echo $rows["m_image_path"] ?>" alt="">
                                <div class="song-content">
                                    <a class="song-title" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?playlist_id=<?php echo $playlist_id ?>&m_id=<?php echo $rows['m_id'] ?>"><?php echo $rows["music_name"]?></a>
                                    <a class="artist" href="artist.php?artist_id=<?php echo $rows['artist_id'] ?>"><?php echo $rows['name'] ?></a>
                                </div>
                            </td>
                            <td class="td-song-date"><?php echo $rows['added_date'] ?></td>
                            <td class="td-song-like">
                            <a class="like-bt" href="process_like.php?m_id=<?php echo $rows["music_id"] ?>&d=<?php echo $_SERVER["SCRIPT_NAME"] ?>?playlist_id=<?php echo $playlist_id ?>">Like</a>
                            </td>
                            <td class="td-song-duration"><?php echo $rows['duration'] ?></td>
                            <td class="td-song-delete">
                                <?php if ($_SESSION["uid"] === $rows["u_id"]) { ?>
                                <a class="td-song-link" href="process_playlistmusic.php?m=d&p_id=<?php echo $playlist_id ?>
                                                            &m_id=<?php echo $rows["music_id"] ?>">Delete</a>
                                <?php } ?>
                            </td>
                        </tr>

                        <?php $no++; } ?>
                        
                    </table>
                </div>
                 
        </section>
    </main>

    <?php require_once('playsong.php'); ?>
    
    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>

    <script>
        let modal = document.getElementById("playlist-edit-modal");
    </script>
    <script src="js/modal.js"></script>
    <script src="js/popup.js"></script>
</body>

</html>