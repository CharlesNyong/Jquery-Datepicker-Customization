<?php

 /*establish database connection with database information*/

 $db_username = "asikpo_270User";
 $db_name = "asikpo_270Project";
 $host = "localhost";
 $password = "charlesnyong96";

 $connection = mysqli_connect($host, $db_username, $password, $db_name);


  if(mysqli_connect_errno()){
  	echo "Failed to connect: " . mysqli_connect_errno();
  }
  else{
  //	echo "Connected!";
  }

?>
