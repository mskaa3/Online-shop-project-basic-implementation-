<?php 
$servername = "localhost";
    $username = "root";
    $pas = "julia";
    $dbname = "rental";
  //establishing connection
    $mysqli=mysqli_connect($servername, $username, $pas,$dbname);


         if(!$mysqli) {
            die("Connection failed: " . mysqli_connect_errno() .mysqli_connect_error());
        }
    ?>