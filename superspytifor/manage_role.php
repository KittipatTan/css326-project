<?php

require_once("../check_session.php");
require_once("connect_admin.php");
require_once("check_admin.php"); 

check_privilege($_SESSION["role_id"], 1);
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
                <li class="nav-item active">
                    <a href="manage_role.php" class="nav-link">
                        Manage role
                    </a>
                </li>
            </div>
        </ul>
    </nav>
    <main>
        <h3 class="topic">Manage user role</h3>

        <!-- <form class="info-container" action="process_addrole.php" method="post">
            <div class="info-edit" id="role-add">
                <input class="info-name" type="text" name="role_name" placeholder="Role Name">
                
            </div>
            <input class="info-submit" type="submit" name="add" value="Add">
        </form> -->

        <section class="user-search">
            <form class="search-bar-container" action="manage_role.php" method="get">
                <div class="search-section">
                    <input type="search" class="search-bar-input" name="n" placeholder="User's name or email">
                    <button type="submit" class="search-bar-submit"></button>
                </div>
            </form>
        </section>

        <section id="user-display">
            <!-- query all songs of this artist and display here -->
            <div class="song-container">
                <table class="song-table">
                    <tr class="tr-header">
                        <th class="th-user no">#</th>
                        <th class="th-user name">Name</th>
                        <th class="th-user email">Email</th>
                        <th class="th-user role">Role</th>
                        <th class="th-user link"></th>
                        <th class="th-user link"></th>
                    </tr>

                    <?php 
                    $q_user = "SELECT uid, name, email, role_name
                               FROM user
                               LEFT OUTER JOIN role
                               ON user.role_id = role.role_id
                               WHERE NOT user.role_id = 1 OR user.role_id IS NULL
                               ORDER BY user.role_id";

                    if (!$r_user = $mysqli->query($q_user)) {
                        echo "failed";
                        exit();
                    }

                    $users = $r_user->fetch_all(MYSQLI_ASSOC);
                    $no = 0;
                    foreach ($users as $user) {
                        $no++;
                    ?>

                    <tr>
                        <td class="td-user no"><?php echo $no; ?></td>
                        <td class="td-user name"><?php echo $user["name"]; ?></td>
                        <td class="td-user email"><?php echo $user["email"]; ?></td>
                        <td class="td-user role"><?php echo $user["role_name"]; ?></td>
                        <td class="td-user link">
                            <a href="modify_role.php?m=e&id=<?php echo $user["uid"]; ?>" class="a-user">Edit</a>
                        </td>
                        <td class="td-user link">

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