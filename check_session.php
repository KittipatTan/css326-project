<?php

if (!(session_start() && isset($_SESSION["uid"]))) {
    header("Location: login.php");
    exit();
}

?>