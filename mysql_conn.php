<?php

$servername = "localhost";
$username = "smha118";
$password = "12dlfdl";
$db = "hoffman2";
$conn = mysqli_connect(
    $servername,
    $username,
    $password,
    $db
);


// Check connection

if (!$conn) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die("Connection failed: " . mysqli_connect_error());
}
// // // Create database
// $sql = 'INSERT INTO hoffman2_logs (sessionID, user_email, user_name, cmds) 
//         VALUES ("' . $sessionID . '", "' . $user_email . '", "' . $user_name . '","' . $cmds . '"); ';
// echo ($sql);
// if (mysqli_query($conn, $sql)) {
//     echo "Database created successfully";
// } else {
//     echo "Error creating database: " . mysqli_error($conn);
// }

// mysqli_close($conn);



// Create connection
