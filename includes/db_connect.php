<?php

function OpenCon() {
    $host = 'localhost';
    $user = 'root';
    $password = ''; 
    $dbname = 'talent_showcase';

    $conn = new mysqli($host, $user, $password, $dbname) or die("Connection failed: %s\n". $conn->error);

    return $conn;
}


function CloseCon($conn) {
    $conn -> close();
}

?>
