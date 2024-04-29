<?php
session_start();
// Establish database connection
$db = mysqli_connect('localhost', 'dev', 'dev', 'marketing');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL injection
    $username = mysqli_real_escape_string($db, $username);
    $password = mysqli_real_escape_string($db, $password);

    // Query the database for user
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) == 1) {
        // Login successful
        $_SESSION['username'] = $username;
        header('location: welcome.php');
        exit;
    } else {
        // Login failed
        $error = "Incorrect username or password";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
</head>
<body>
    <div class="container">
        <?php if (!isset($_SESSION['username'])): ?>
            <button class="open-button" onclick="openForm()">Open Login Form</button>
            <div class="form-popup" id="myForm">
                <form action="index.php" class="form-container" method="post">
                    <h1>Login</h1>

                    <label for="email"><b>Email</b></label>
                    <input type="text" placeholder="Enter Email" name="username" required>

                    <label for="psw"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password" required>

                    <button type="submit" class="btn">Login</button>
                    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                    <?php if (isset($error)): ?>
                        <p><?php echo $error; ?></p>
                    <?php endif; ?>
                </form>
            </div>
        <?php else: ?>
                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <p>Are you sure you want to upload more products?</p>
                        <button onclick="redirect('welcome.php')">Yes</button>
                        <button onclick="redirect('index.php?logout=true')" style="background-color: red;">Logout</button>
                        <button onclick="redirect('../index.php')" style="background-color: yellow;">Go To Home Page </button>
                    </div>
                </div>
                <script>
                // Function to redirect to the specified URL
                function redirect(url) {
                    window.location.href = url;
                }
                </script>
        <?php endif; ?>
    </div>
    <script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }
    </script>
</body>
</html>
