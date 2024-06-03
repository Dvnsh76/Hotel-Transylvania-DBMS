<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="styling.css">
</head>
<body>
    <h1>Hotel Transylvania</h1>
    <span>Welcome Admins, please login</span>
    
    <a href="index.php"><button id="bookingbutton">Home</button></a>

    <section id="admin-login-form">
        <form method="POST" action="adminlogin.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Login</button>
        </form>

        <?php
        include 'connect.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            
            $sql = "SELECT * FROM Admins WHERE username = '$username' AND password = '$password'";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                header("Location: admin.php");
                exit();
            } else {
                echo "<p>Invalid, try again!</p>";
            }
        }
        ?>
    </section>
</body>
</html>
