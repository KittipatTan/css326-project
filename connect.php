<?php

$mysqli = new mysqli('localhost','spytiforuser','#qdWani$X3jAUHkK','spytifor');
   if($mysqli->connect_errno){
      echo $mysqli->connect_errno.": ".$mysqli->connect_error;
   }
   
?>