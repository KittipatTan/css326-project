<?php 
require_once("../check_session.php");
require_once("connect_admin.php"); 
require_once("check_admin.php");

check_privilege($_SESSION["role_id"], 0);
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>Spytifor - Music Streaming Platform</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/logo.svg#logo-spytifor-white-text">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="popup"></div>
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
                <li class="nav-item active">
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
        <h3 class="topic">Add music</h3>

        <form class="info-container" action="uploadsong.php" method="post" enctype="multipart/form-data">
            <input class="img-upload" type="file" name="music_img" accept="image/png, image/jpeg">
            <div class="info-edit" id="music-add">
                <input class="info-name" type="text" name="music_name" placeholder="Name">
                <textarea class="info-description" name="music_credit" placeholder="Credit"></textarea>
                <input class="info-name" type="text" name="music_duration" placeholder="Duration">
                <select class="select-filter" name="music_genre">
                    <!-- query all genre and display here -->
                    <?php  
                    $q_genre = "select * from genre;";
                    if (!$result_genre = $mysqli->query($q_genre)) {
                        echo "failed " . $mysqli->error;
                        exit;
                    }
                    while ($rows = $result_genre->fetch_array()) {
                    ?>

                    <option value="<?php echo $rows['genre_name'] ?>"><?php echo $rows['genre_name'] ?></option>
                    <?php } ?>
                </select>
                

                <input class="info-name" type="text" name="music_artist_id" placeholder="Artist ID">
                <label for="music-upload-file" class="form-label">Music file:</label>
                <input class="song-upload" type="file" name="music_song">
            </div>
            <input class="info-submit" type="submit" name="add" value="Add">
        </form>

        <section class="music-search">
            <form class="search-bar-container" action="index.php" method="get">
                <div class="search-section">
                    <input type="search" class="search-bar-input" name="n" placeholder="Music name or artist name">
                    <button type="submit" class="search-bar-submit"></button>
                </div>
            </form>
        </section>

        <section class="music-display">
            <!-- query all songs of this artist and display here -->

            <div class="song-container">
                <table class="song-table">
                    <tr class="tr-header">
                        <th class="th-song-no">#</th>
                        <th class="th-song-title">Title</th>
                        <th class="th-song-duration">Duration</th>
                        <th class="th-song link"></th>
                        <th class="th-song link"></th>
                        <th class="th-song"></th>
                    </tr>

                    <?php
                    $qmusic = "SELECT m.image_path as m_image_path,
                                      music_name,
                                      a.name as a_name,
                                      duration,
                                      music_id
                                FROM music as m
                                INNER JOIN artist as a
                                ON a.artist_id = m.artist_id;";

                    $stmt = $mysqli->prepare($qmusic);
                    if (!$stmt->execute()) {
                        echo "select failed";
                        exit();
                    } 
        
                    $qmusic_result = $stmt->get_result();
                    $no = 1;
                    while ($rows = $qmusic_result->fetch_array()) {
                    ?>
                    <tr class="tr-data">
                        <td class="td-song-no"><?php echo $no; ?></td>
                        <td class="td-song-title">
                            <img class="song-img" src="../profilepicture/<?php echo $rows["m_image_path"] ?>" alt="">
                            <div class="song-content">
                                <p class="song-title"><?php echo $rows['music_name'] ?></p>
                                <a class="artist-name"><?php echo $rows['a_name'] ?></a>
                            </div>
                        </td>
                        <td class="td-song-duration"><?php echo $rows['duration'] ?></td>
                        <td class="td-song link">
                            <!-- add id of the music here -->
                            <a href="modify_music.php?m=e&id=<?php echo $rows['music_id'];?>" class="a-song">Edit</a>
                        </td>
                        <td class="td-song link">
                            <a href="modify_music.php?m=d&id=<?php echo $rows['music_id'];?>" class="a-song">Delete</a>
                        </td>
                        <td class="td-song-popup">
                            <div class="song-popup-bt-container" onclick="showMusicPopup(this)">
                                <span class="song-popup-bt-dot"></span>
                                <span class="song-popup-bt-dot"></span>
                                <span class="song-popup-bt-dot"></span>
                            </div>
                            <div class="popup-container song-popup">
                                <p class="popup-item"><span class="arrow-left"></span>Add to playlist</p>
                                <div class="popup-container song-sub-popup">
                                    <?php
                                    $q_playlist = "select * from playlist where u_id IS NULL;";
                                    if (!$r_playlist = $mysqli->query($q_playlist)) {
                                        echo "failed " . $mysqli->error;
                                        exit();
                                    }

                                    $playlists = $r_playlist->fetch_all(MYSQLI_ASSOC);
                                    foreach ($playlists as $playlist) {
                                        echo  '<a href="process_playlistmusic.php?m=a&p_id=' . 
                                                    $playlist["playlist_id"] . 
                                                    '&m_id=' . $rows["music_id"] . 
                                                    '" class="popup-item">' .
                                                    $playlist["playlist_name"] . 
                                            '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php  $no++; } ?>
                </table>
            </div>
            
        </section>
    </main>
    
    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>

    <script>
        let user_popup = document.getElementById("popup-user-logo");
        let user_popup_bt = document.getElementById("popup-bt-user");
        let popup = document.getElementsByClassName("popup")[0];

        user_popup_bt.onclick = () => {
            user_popup.classList.toggle("show");
            popup.style.display = "block";
        }

        window.onclick = (event) => {
            if (event.target == popup) {
                popup.style.display = "none";
                for (let p of document.getElementsByClassName("popup-container")) {
                    p.classList.remove("show");
                }
            }
        }

        function showMusicPopup(e) {
            let music_popup = e.nextElementSibling;
            music_popup.classList.toggle("show");
            popup.style.display = "block";
        }
    </script>
</body>

</html>