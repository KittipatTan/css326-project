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
                <li class="nav-item active">
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
        <h3 class="topic">Add artist</h3>

        <form class="info-container" action="process_addartist.php" method="post" enctype="multipart/form-data">
            <input class="img-upload" type="file" name="artist_img" accept="image/png, image/jpeg">
            <div class="info-edit" id="artist-add">
                <input class="info-name" type="text" name="artist_name" placeholder="Name">
                <textarea class="info-description" name="artist_about" placeholder="Artist about"></textarea>
            </div>
            <input class="info-submit" type="submit" name="add" value="Add">
        </form>

        <section class="artist-search">
            <form class="search-bar-container" action="add_artist.php" method="get">
                <div class="search-section">
                    <input type="search" class="search-bar-input" name="n" placeholder="Artist name">
                    <button type="submit" class="search-bar-submit"></button>
                </div>
            </form>
        </section>

        <section id="artist-display">
            <div class="song-container">
                <table class="song-table">
                    <tr class="tr-header">
                        <th class="th-artist no">#</th>
                        <th class="th-artist name">Name</th>
                        <th class="th-artist about">About</th>
                        <th class="th-artist link"></th>
                        <th class="th-artist link"></th>
                    </tr>
                    <!-- query all artists and display here -->
                    <?php
                    
                    $artist_name = $_GET["n"];
                    $q_artist = "SELECT * FROM artist"
                                 . ($artist_name ? " WHERE name LIKE '$artist_name%'" : "") . ";";

                    if (!$result_artist = $mysqli->query($q_artist)) {
                        echo $q_artist;
                        echo "failed " . $mysqli->error;
                    }

                    while ($row = $result_artist->fetch_array()) {
                    ?>
                    <tr>
                        <td class="td-artist no"><?php echo $row["artist_id"] ?></td>
                        <td class="td-artist name"><?php echo $row["name"] ?></td>
                        <td class="td-artist info"><?php echo $row["about"] ?></td>
                        <td class="td-artist link">
                            <a href="modify_artist.php?m=e&id=<?php echo $row["artist_id"] ?>" class="a-artist">Edit</a>
                        </td>
                        <td class="td-artist link">
                            <a href="modify_artist.php?m=d&id=<?php echo $row["artist_id"] ?>" class="a-artist">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
          
        </section>
    </main>
    
    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>
</body>

</html>