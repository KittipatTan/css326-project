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
                <li class="nav-item active">
                    <a href="liked_song.php" class="nav-link">
                        <svg class="nav-logo">
                            <use href="img/logo.svg#logo-likedsongs"/>
                        </svg>
                        Liked Songs
                    </a>
                </li>
                <?php
                $userid = $_SESSION['uid'];
                $q2 = "select * from playlist inner join user on playlist.u_id = user.uid where u_id = $userid";
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
    <main id="main-liked-song">
        <section class="section-info">
            <img src="img/liked_song_logo.svg" alt="" class="playlist-img liked-song-img">
            <div class="section-name">
                <p>Playlist</p>
                <h1>Liked Songs</h1>
                <?php
                $count = "select count(music_id) as amount from liked where uid = $userid;";
                $getsongcount = $mysqli->query($count);
                if (!$getsongcount) {
                    echo "failed " . $mysqli->error;
                }
                while ($rows = $getsongcount->fetch_array()) {
                ?>
                    <p><?php echo $rows['amount'] ?> liked songs</p>
                <?php } ?>
            </div>
        </section>
        <section class="artist-song">
            <div class="song-container">
                <table class="song-table">
                    <tr class="tr-header">
                        <th class="th-song-no">#</th>
                        <th class="th-song-title">Title</th>
                        <th class="th-song-date">Date added</th>
                        <th class="th-song-like"></th>
                        <th class="th-song-duration">Duration</th>
                    </tr>

                    <?php
                    $q5 = "select *, music.image_path as m_image_path,music.music_id as m_id  
                              from liked 
                              inner join music 
                              on music.music_id = liked.music_id 
                              inner join artist 
                              on artist.artist_id = music.artist_id 
                              where uid = ?
                              ORDER BY liked.liked_date;";
                              $stmt = $mysqli->prepare($q5);
                              $stmt->bind_param("i", $userid);

                              if (!$stmt->execute()) {
                                  echo "failed";
                                  exit("statement execution failed");
                              }
                          
                              $r_playlist = $stmt->get_result();
                     $no=1;     
                    while ($row = $r_playlist->fetch_array()) {
                    ?>

                    <tr>
                        <td class="td-song-no"><?php echo $no ?></td>
                        <td class="td-song-title">
                            <img class="song-img" src="profilepicture/<?php echo $row["m_image_path"] ?>" alt="">
                            <div class="song-content">
                            <a class="song-title" href= '<?php echo $_SERVER["SCRIPT_NAME"]?>?m_id=<?php echo $row['m_id'] ?>'><?php echo $row["music_name"]?></a>
                                <a class="artist" href="artist.php?artist_id=<?php echo $row['artist_id'] ?>"><?php echo $row['name'] ?></a>
                            </div>
                        </td>
                        <td class="td-song-date"><?php echo $row['liked_date'] ?></td>
                        <td class="td-song-like">
                            <a class="like-bt" href="process_like.php?m_id=<?php echo $row["m_id"] ?>&d=<?php echo $_SERVER["SCRIPT_NAME"] ?>">Like</a>
                        </td>
                        <td class="td-song-duration"><?php echo $row['duration'] ?></td>
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
    
    <script src="js/popup.js"></script>
</body>

</html>