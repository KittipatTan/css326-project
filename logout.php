<?php

require_once("check_session.php");

if (session_destroy()) {
    header('location: login.php');
    exit(0);
} else {
    echo "logout failed";
}

?>