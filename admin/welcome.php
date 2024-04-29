<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('location: login.php');
    exit(); // Ensure the script stops execution after redirecting
}

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if form is submitted
if(isset($_POST['submit'])) {
    // Check if file is uploaded without errors
    if(isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
        // Database connection
        $db = mysqli_connect('localhost', 'dev', 'dev', 'marketing');

        // Get file details
        $file_name = $_FILES['fileToUpload']['name'];
        $file_tmp = $_FILES['fileToUpload']['tmp_name'];

        // Check if the uploaded file is a CSV
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if($file_ext == 'csv') {
            // Read CSV file
            $csv_data = array_map('str_getcsv', file($file_tmp));

            // Remove header row if present
            $header = array_shift($csv_data);

            // Insert data into database
            foreach($csv_data as $row) {
                // Prepare data for SQL query
                $id = mysqli_real_escape_string($db, $row[0]);
                $image = mysqli_real_escape_string($db, $row[1]);
                $link = mysqli_real_escape_string($db, $row[2]);
                $name = mysqli_real_escape_string($db, $row[3]);
                $description = mysqli_real_escape_string($db, $row[4]);

                // Construct SQL query
                $sql = "INSERT INTO product_data (id, product_image, product_link, product_name, product_description) VALUES ('$id', '$image', '$link', '$name', '$description') ON DUPLICATE KEY UPDATE product_image = '$image', product_link = '$link', product_name = '$name', product_description = '$description'";

                // Execute SQL query
                $result = mysqli_query($db, $sql);
                if(!$result) {
                    echo "Error inserting data: " . mysqli_error($db);
                }
            }
            ?>
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <p>Are you sure you want to upload more products?</p>
                    <button onclick="redirect('welcome.php')">Yes</button>
                    <button onclick="redirect('../index.php')">No</button>
                </div>
            </div>
            <script>
            // Function to redirect to the specified URL
            function redirect(url) {
                window.location.href = url;
            }
            </script>
            <?php
        } else {
            echo "Error: Please upload a CSV file.";
        }

        // Close database connection
        mysqli_close($db);
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel File</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
</head>
<body>
    <div class="form-container">
        <h2>File Upload Form</h2>
        <form action="welcome.php" method="post" enctype="multipart/form-data">
            <div class="file-input">
                <label for="fileToUpload">Select Excel file to upload:</label><br>
                <input type="file" name="fileToUpload" id="fileToUpload">
            </div>
            <input type="submit" value="Upload File" name="submit" class="submit-btn">
        </form>
    </div>
</body>
</html>
