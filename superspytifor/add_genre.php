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
    <link rel="stylesheet" href="styles.css">
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
                <li class="nav-item">
                    <a href="add_playlist.php" class="nav-link">
                        Add playlist
                    </a>
                </li>
                <li class="nav-item active">
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
        <h3 class="topic">Add genre</h3>

        <form class="info-container" action="process_addgenre.php" method="post">
            <input class="img-upload" type="file" name="genre_img" accept="image/png, image/jpeg">
            <div class="info-edit" id="genre-add">
                <input class="info-name" type="text" name="genre_name" placeholder="Name">
                <input class="info-name" type="text" name="genre_color" placeholder="Color code">
            </div>
            <input class="info-submit" type="submit" name="add" value="Add">
        </form>

        <section class="genre-search">
            <form class="search-bar-container" action="add_genre.php" method="get">
                <div class="search-section">
                    <input type="search" class="search-bar-input" name="n" placeholder="genre name">
                    <button type="submit" class="search-bar-submit"></button>
                </div>
            </form>
        </section>

        <section id="genre-display">
            <!-- query all songs of this artist and display here -->
            <?php 
            $song ?>
            <div class="song-container">
                <table class="song-table">
                    <tr class="tr-header">
                        <th class="th-genre no">#</th>
                        <th class="th-genre name">Name</th>
                        <th class="th-genre color-code">Color code</th>
                        <th class="th-genre link"></th>
                        <th class="th-genre link"></th>
                    </tr>

                    <?php
                    $genre_name = $_GET["n"];
                    $genre_name = "$genre_name%";
                    $stmt = $mysqli->prepare("select * from genre where genre_name like ? ;");
                    $stmt->bind_param("s", $genre_name);
                
                    if (!$stmt->execute()) {
                        echo "select failed";
                        exit();
                    } 
                
                    $getaG = $stmt->get_result();
                    
                    $no = 1;
                    while ($row = $getaG->fetch_array()) {
                    ?>

                    <tr>
                        <td class="td-genre no"><?php echo $no ?></td>
                        <td class="td-genre name"><?php echo $row['genre_name']; ?></td>
                        <td class="td-genre color-code"><span style="background-color: <?php echo $row["color"]; ?>; "></span> <?php echo $row["color"]; ?></td>
                        <td class="td-genre link">
                            <a href="modify_genre.php?m=e&name=<?php echo $row["genre_name"] ?>" class="a-genre">Edit</a>
                        </td>
                        <td class="td-genre link">
                            <a href="modify_genre.php?m=d&name=<?php echo $row["genre_name"] ?>" class="a-genre">Delete</a>
                        </td>
                    </tr>

                <?php $no++; } ?>
                </table>
            </div>
        </section>
    </main>
    
    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>
</body>

</html>