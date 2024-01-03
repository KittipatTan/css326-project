<?php
require_once("check_session.php");

if (is_numeric($_GET["m_id"])) {
    $_SESSION["playing_m_id"] = $_GET["m_id"];
}

if (is_numeric($_SESSION["playing_m_id"])) {
    require_once("connect.php");
?>

<div class="music-player">
    <?php 
    $m_id = $_SESSION["playing_m_id"]; 
    $q_music = "SELECT music_id,
                       duration,
                       music.artist_id as a_id,
                       music_name,
                       path,
                       music.image_path as m_image_path,
                       name
                FROM music
                INNER JOIN artist
                ON music.artist_id = artist.artist_id
                WHERE music_id = $m_id;";

    if (!$play_song = $mysqli -> query($q_music)) {
        echo "failed";
        exit();
    }

    $music = $play_song->fetch_assoc();  
    
    ?>
    <audio src="uploadsong/<?php echo $music["path"]; ?>" controls autoplay></audio>
</div>

<!-- <div class="App__now-playing-bar">
    <div class="function">
        <div class="music">
        <img src="https://images.genius.com/31c7d09c4aa1b324bb911d0db72453a3.1000x1000x1.jpg" alt=""/>
        <div class="details">
            <div class="name">Night Change</div>
            <div class="artist">One Direction</div>
        </div>
        <div class="love">
            <img src="https://cdn-icons-png.flaticon.com/512/2589/2589175.png" alt="love"/>
        </div>
        </div>
        <div class="playback">
        <div class="upper">
            <img src="https://cdn-icons-png.flaticon.com/512/724/724979.png" alt="shuffle"/>
            <img src="https://cdn-icons-png.flaticon.com/512/3318/3318703.png" alt="back"/>
            <img src="https://cdn-icons-png.flaticon.com/512/2088/2088562.png" alt="pause" class="pause"/>
            <img src="https://cdn-icons-png.flaticon.com/512/4211/4211386.png" alt="forward"/>
            <img src="https://cdn-icons-png.flaticon.com/512/4146/4146819.png" alt="loop"/>
        </div>
        <div class="lower">
            <div class="text">0:00</div>
            <div class="line"></div>
            <div class="text">3:46</div>
        </div>
        </div>
        <div class="control">
        <div class="images">
            <img src="https://cdn-icons-png.flaticon.com/512/26/26615.png" alt="mic"/>
            <img src="https://cdn-icons-png.flaticon.com/512/98/98068.png" alt="queue"/>
            <img src="https://cdn-icons-png.flaticon.com/512/2777/2777142.png" alt="device"/>
            <img src="https://cdn-icons-png.flaticon.com/512/25/25695.png" alt="volume"/>
            <div class="line"></div>
        </div>
        </div>
    </div>
</div> -->

<?php } ?>
  