<?php

    /* Attempt MySQL server connection. Assuming you are running MySQL

    server with default setting (user 'root' with no password) */

    $link = mysqli_connect("localhost", "root", "root", "zurctechbr");

     

    // Check connection

    if($link === false){

        die("ERROR: Could not connect. " . mysqli_connect_error());

    }

     

    // Attempt insert query execution
    $T = addslashes(trim($_GET['t']));
    $H = addslashes(trim($_GET['h']));

    $sql = "INSERT INTO log (temperatura, humidade) VALUES ($T, $H)";

    if(mysqli_query($link, $sql)){

        echo "Records inserted successfully.";

    } else{

        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);

    }

     

    // Close connection

    mysqli_close($link);

    ?>

