<?php

$mysqli = new mysqli('localhost','spytiforadmin','njbuJv5FYb*xYsX%','spytifor');
   if($mysqli->connect_errno){
      echo $mysqli->connect_errno.": ".$mysqli->connect_error;
   }
   
?>