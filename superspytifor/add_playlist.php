<!-- check if logged in or not -->
<!-- if not logged in, redirect to login page -->
<?php 
require_once("../check_session.php");
require_once("connect_admin.php"); 
require_once("check_admin.php"); 
check_privilege($_SESSION["role_id"], 0);
?>

<!-- if logged in, display the page with session of that user -->

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
                <li class="nav-item active">
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
        <h3 class="topic">Add playlist</h3>

        <form class="info-container" action="process_addplaylist.php" method="post" enctype="multipart/form-data">
            <input class="img-upload" type="file" name="playlist_img" accept="image/png, image/jpeg">
            <div class="info-edit" id="playlist-add">
                <input class="info-name" type="text" name="playlist_name" placeholder="Name">
                <textarea class="info-description" name="playlist_description" placeholder="Playlist Description"></textarea>
                <input class="info-checkbox" id="checkbox-isprivate" type="checkbox" name="isPrivate" value="1">
                <label for="checkbox-isprivate">Private playlist</label>
            </div>
            <input class="info-submit" type="submit" name="add" value="Add">
        </form>

        <section class="playlist-search">
            <form class="search-bar-container" action="add_playlist.php" method="get">
                <div class="search-section">
                    <input type="search" class="search-bar-input" name="n" placeholder="Playlist name">
                    <button type="submit" class="search-bar-submit"></button>
                </div>
            </form>
        </section>

        <section id="playlist-display">
            <!-- query all songs of this artist and display here -->
            <div class="song-container">
                <table class="song-table">
                    <tr class="tr-header">
                        <th class="th-playlist no">#</th>
                        <th class="th-playlist name">Name</th>
                        <th class="th-playlist description">Description</th>
                        <th class="th-playlist bool">isPublic</th>
                        <th class="th-playlist date">Created date</th>
                        <th class="th-playlist link"></th>
                        <th class="th-playlist link"></th>
                    </tr>
                    <?php
                    $p_name = $_GET['n'];
                    $p_name = "$p_name%";
                    
                    $stmt = $mysqli->prepare("select * from playlist where playlist_name like ? AND u_id IS NULL;");
                    $stmt->bind_param("s", $p_name);
                
                    if (!$stmt->execute()) {
                        echo "select failed";
                        exit();
                    } 
                    $getaG = $stmt->get_result();
                
                    $no = 1;
                    while ($rows = $getaG->fetch_array()) {
                    ?>
                    <tr>
                        <td class="td-playlist no"><?php echo $no; ?></td>
                        <td class="td-playlist name"><?php echo $rows['playlist_name']; ?></td>
                        <td class="td-playlist description"><?php echo $rows['description']; ?></td>
                        <td class="td-playlist bool"><?php echo $rows['ispublic']; ?></td>
                        <td class="td-playlist date"><?php echo $rows['created_date']; ?></td>
                        <td class="td-playlist link">
                            <a href="modify_playlist.php?m=e&id=<?php echo $rows["playlist_id"] ?>" class="a-playlist">Edit</a>
                        </td>
                        <td class="td-playlist link">
                            <a href="modify_playlist.php?m=d&id=<?php echo $rows["playlist_id"] ?>" class="a-playlist">Delete</a>
                        </td>
                    </tr>
                    
                    <?php $no++;} ?>  
                </table>
            </div>
        </section>
    </main>
    
    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>
</body>

</html>