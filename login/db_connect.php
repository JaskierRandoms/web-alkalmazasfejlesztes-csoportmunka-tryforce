<?php 
    
        $server = "localhost";
        $username = "root";
        $password = "";
        $database = "web";

        $con = new mysqli($server, $username, $password, $database) or die("Kapcsolódási hiba!");

        $con->set_charset("utf8");