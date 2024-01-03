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
                    <a id="add-playlist-bt" class="nav-button" href="add_user_playlist.php?d=<?php echo $_SERVER["SCRIPT_NAME"] ?>?artist_id=<?php echo $_GET["artist_id"] ?>">
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
                <!-- query user's playlist from database -->
                <?php
                $userid =  $_SESSION["uid"];
                $q2 = "select playlist_name, playlist_id,image_path from playlist inner join user on playlist.u_id = user.uid where u_id = $userid";
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
    <main>
        <section class="section-info">
        <?php
            $artistid = $_GET['artist_id'];
            $artistsong = "select * from artist where artist_id = $artistid;";
            $getartistsong = $mysqli->query($artistsong);
            if (!$getartistsong) {
                echo "failed " . $mysqli->error;
            }
            while ($rows = $getartistsong->fetch_array()) {
            ?>
                <img src="profilepicture/<?php echo $rows["image_path"] ?>" alt="" class="profile-img">
                <div class="section-name">
                    <p>Artist</p>
                    <h1><?php echo $rows['name'] ?></h1>
                </div>
            <?php } ?>
        </section>
            
        </section>
        <section class="artist-song">
        <!-- query all songs of this artist and display here -->
            <div class="song-container">
                <table class="song-table">
                    <tr class="tr-header">
                        <th class="th-song-no">#</th>
                        <th class="th-song-title">Title</th>
                        <th class="th-song-like"></th>
                        <th class="th-song-duration">Duration</th>
                    </tr>

                    <?php
                    $artistid = $_GET['artist_id'];

                    $artistsong = "select *, 
                                          music.image_path as m_image_path,
                                          music.music_id as m_id 
                                          from artist 
                                          inner join music 
                                          on artist.artist_id = music.artist_id 
                                          where artist.artist_id = $artistid;";
                    $getartistsong = $mysqli->query($artistsong);

                    if (!$getartistsong) {
                        echo "failed " . $mysqli->error;
                    }
                    $no = 1;
                    while ($rows = $getartistsong->fetch_array()) {
                    ?>

                    <tr>
                        <td class="td-song-no"><?php echo $no; ?></td>
                        <td class="td-song-title">
                            <img class="song-img" src="profilepicture/<?php echo $rows["m_image_path"] ?>" alt="">
                            <div class="song-content">
                                <a class="song-title" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?artist_id=<?php echo $artistid ?>&m_id=<?php echo $rows['m_id'] ?>"><?php echo $rows["music_name"]?></a>
                                <a class="artist" href="artist.php?artist_id=<?php echo $artistid ?>"><?php echo $rows['name'] ?></a>
                            </div>
                        </td>
                        <td class="td-song-like">
                            <a class="like-bt" href="process_like.php?m_id=<?php echo $rows["m_id"] ?>&d=<?php echo $_SERVER["PHP_SELF"] ?>?artist_id=<?php echo $artistid ?>">Like</a>
                        </td>
                        <td class="td-song-duration"><?php echo $rows['duration'] ?></td>
                    </tr>

                    <?php  $no++;} ?>
                </table>
            </div>
        

    </section>
    <section class="artist-about">
            <?php
            $q_artist = "select about from artist where artist.artist_id = $artistid;";
            
            if (!$r_artist = $mysqli->query($q_artist)) {
                echo "failed";
                exit();
            }

            if (!($r_artist->num_rows > 0 && $artist_array = $r_artist->fetch_assoc())) {
                echo "no artist";
                exit();
            }
            ?>

            <h3 class="topic">About</h3>
            <p class="description"><?php echo $artist_array["about"] ?></p>
    </section>
    </main>

    <?php require_once('playsong.php'); ?>
    
    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>
</body>

</html>