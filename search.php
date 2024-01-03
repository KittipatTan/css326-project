<?php 
require_once('check_session.php');
require_once('connect.php'); 

if (!isset($_GET["q"]) || $_GET["q"] === "") {
    $search = "%";
} else {
    $search = $_GET["q"];
    $search = "$search%";
}

$genre_pick = $_GET['genre'] ?? "any";

$allowed_filters = array("all", "artist", "song", "playlist");
if (!isset($_GET["filter"]) || !in_array($_GET["filter"], $allowed_filters)) {
    $filter = "all";
} else {
    $filter = $_GET["filter"];
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
        <form class="search-bar-container" action="search.php" method="get">
            <div class="search-section">
                <input type="search" class="search-bar-input" name="q" placeholder="What do you want to listen to?">
                <button type="submit" class="search-bar-submit"></button>
            </div>
            <div class="filter-section">
                <input class="input-filter-radio" type="radio" id="radio-all" name="filter" value="all" <?php echo ($filter === "all" ? "checked" : ""); ?> >
                <label class="label-filter" for="radio-all">All</label>
                <input class="input-filter-radio" type="radio" id="radio-artist" name="filter" value="artist" <?php echo ($filter === "artist" ? "checked" : ""); ?>>
                <label class="label-filter" for="radio-artist">Artists</label>
                <input class="input-filter-radio" type="radio" id="radio-song" name="filter" value="song" <?php echo ($filter === "song" ? "checked" : ""); ?>>
                <label class="label-filter" for="radio-song">Songs</label>
                <input class="input-filter-radio" type="radio" id="radio-playlist" name="filter" value="playlist" <?php echo ($filter === "playlist" ? "checked" : ""); ?>>
                <label class="label-filter" for="radio-playlist">Playlists</label>

                <select class="select-filter" name="genre" id="genre">
                    <option value="any">Any genre</option>

                    <?php
                    $qG1 = 'select genre_name from genre;';
                    if ($result = $mysqli->query($qG1)) {
                        while ($row = $result->fetch_assoc()) {
                            $row_genre_name = $row["genre_name"];
                            echo "<option value='$row_genre_name'" . ($row_genre_name === $genre_pick ? "selected" : "") . ">$row_genre_name</option>";
                        }
                    } else {
                        echo 'Query error: ' . $mysqli->error;
                        exit();
                    }
                    ?>

                </select>
            </div>
        </form>

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
                <li class="nav-item active">
                    <a href="search.php" class="nav-link">
                        <svg class="nav-logo">
                            <use href="img/logo.svg#logo-search-active"/>
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
                $q2 = "select * from playlist where u_id = $userid;";

                if (!$getplaylist = $mysqli->query($q2)) {
                    echo "failed " . $mysqli->error;
                    exit;
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
        <?php 
        
        if ($filter === "all" || $filter === "song") {
            
        ?>
        <h3 class="topic">Songs</h3>
        <div class="song-container">
            <table class="song-table" >
                <tr class="tr-header">
                    <th class="th-song-no">#</th>
                    <th class="th-song-title">Title</th>
                    <th class="th-song-like"></th>
                    <th class="th-song-duration">Duration</th>
                    <th class="th-song-popup"></th>
                </tr>

                <?php
                $q = "select music_id, 
                            music_name, 
                            duration, 
                            name, 
                            artist.artist_id, 
                            music.image_path as m_image_path,
                            path 
                    from music 
                    inner join artist 
                    on music.artist_id = artist.artist_id
                    where music_name like ?";

                if ($genre_pick !== "any") {
                    $q = "select music_id, 
                                music_name, 
                                duration, 
                                name, 
                                artist.artist_id, 
                                music.image_path as m_image_path,
                                path 
                        from music 
                        inner join artist 
                        on music.artist_id = artist.artist_id
                        where music_name like ? and genre_name = ?;";
                    $stmt = $mysqli->prepare($q);
                    $stmt->bind_param("ss", $search, $genre_pick);
                } else {
                    $stmt = $mysqli->prepare($q);
                    $stmt->bind_param("s", $search);
                }
            
                if (!$stmt->execute()) {
                    echo "failed";
                    exit();
                }

                $music_results = $stmt->get_result();

                $no=1;
                while($row = $music_results->fetch_assoc()) { 
                ?>

                <tr class="tr-data">
                    <td class="td-song-no"><?php echo $no; ?></td>
                    <td class="td-song-title">
                        <img class="song-img" src="profilepicture/<?php echo $row["m_image_path"] ?>" alt="">
                        <div class="song-content">
                            <a class="song-title" href='<?php echo $_SERVER["SCRIPT_NAME"] ?>?m_id=<?php echo $row['music_id'] ?>'><?php echo $row["music_name"]?></a>
                            <a class="artist" href="artist.php?artist_id=<?php echo $row['artist_id'] ?>"><?php echo $row['name'] ?></a>
                        </div>
                    </td>
                    <td class="td-song-like">
                        <a class="like-bt" href="process_like.php?m_id=<?php echo $row["music_id"] ?>&d=<?php echo $_SERVER["SCRIPT_NAME"] ?>">Like</a>
                    </td>
                    <td class="td-song-duration"><?php echo $row["duration"] ?></td>
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
                                $userid = $_SESSION["uid"];
                                $q_playlist = "select * from playlist where u_id = $userid;";
                                if (!$r_playlist = $mysqli->query($q_playlist)) {
                                    echo "failed " . $mysqli->error;
                                    exit();
                                }

                                $playlists = $r_playlist->fetch_all(MYSQLI_ASSOC);
                                foreach ($playlists as $playlist) {
                                    echo  '<a href="process_playlistmusic.php?m=a&p_id=' . 
                                                $playlist["playlist_id"] . 
                                                '&m_id=' . $row["music_id"] . 
                                                '" class="popup-item">' .
                                                $playlist["playlist_name"] . 
                                          '</a>';
                                }
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>

                <?php $no++; } ?>

            </table>
        </div>
        <?php }
        
        if ($filter === 'all' || $filter === 'artist') {
            
        ?>

        <h3 class="topic">Artists</h3>

        <div class="card-container">
            <?php 
            $q = "select * from artist where name like ?";
            $stmt = $mysqli->prepare($q);
            $stmt->bind_param("s", $search);
           
            if (!$stmt->execute()) {
                echo "failed ";
                exit();
            }

            $artist_results = $stmt->get_result();

            while($row = $artist_results->fetch_assoc()) {
            ?>

            <a class="card" href="artist.php?artist_id=<?php echo $row['artist_id'] ?>" >
                <img class="card-img rounded shadow" src="profilepicture/<?php echo $row["image_path"] ?>" alt="">
                <div class="card-text">
                    <p class="card-title"><?php echo $row["name"]?></p>
                    <p class="card-description"><?php echo $row["about"]?></p>
                </div>
            </a>

            <?php } ?>
        </div>

        <?php }
        
        if($filter === 'all' || $filter === 'playlist') {
            
        ?>

        <h3 class="topic">Playlists</h3>

        <div class="card-container">
            <?php
            $q = "select playlist_id,
                        playlist_name,
                        description, 
                        image_path 
                from  playlist
                where playlist_name like ?;";

            $stmt = $mysqli->prepare($q);
            $stmt->bind_param("s", $search);

            if (!$stmt->execute()) {
                echo "failed";
                exit();
            }

            $playlist_results = $stmt->get_result();
            
            while($row = $playlist_results->fetch_assoc()) {
            ?>

            <a class="card"  href="playlist.php?playlist_id=<?php echo $row['playlist_id'] ?>">
                <img class="card-img" src="profilepicture/<?php echo $row["image_path"] ?>" alt="">
                <div class="card-text">
                    <p class="card-title"><?php echo $row["playlist_name"]?></p>
                    <p class="card-description"><?php echo $row["description"]?></p>
                </div>
            </a>

            <?php } ?>
        </div>

        <?php } ?>

    </main>

    <?php require_once('playsong.php'); ?>

    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>

    <script src="js/popup.js"></script>
</body>


</html>