<?php
//connect to central database

$user = "hittrax_yury";
$pass = "f-9Tt%@B*IDs";
$host = "localhost";

//$user = "ct_hitt_U4q2l";
//$pass = "3NJsYQY#NR3xTzK61nCg";
//$host = "localhost:3306";


//connection to the database
$dbConnection = mysqli_connect($host, $user, $pass, 'hittrax_centralDB')
or die("Error Establishing DB Connection");



// hittrax_yury
// f-9Tt%@B*IDs

?>