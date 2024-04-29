<?php
define('DB_SERVER','localhost');
define('DB_USER','dev');
define('DB_PASS' ,'dev');
define('DB_NAME', 'marketing');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
// Check connection
if (mysqli_connect_errno())
{
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// Receive the data sent from the client-side
$data = json_decode(file_get_contents("php://input"), true);
$currentDateTime = date("Y-m-d H:i:s");
$sql = "INSERT INTO user_data (ip, product_link, clicked_date) VALUES ('" . $data['ip'] . "', '" . $data['product_link'] . "', '$currentDateTime')";
if (mysqli_query($con, $sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_connect_error();
}

// Close conection
mysqli_close($con);
?>