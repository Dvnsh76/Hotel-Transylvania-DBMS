<!DOCTYPE html>
<html>
<head>
    <title>Room Booking</title>
    <link rel="stylesheet" href="styling.css">

</head>
<body>
    <h1>Hotel Transylvania</h1>
    <a href="index.php"><button id="bookingbutton">Home</button></a>
    
    <?php include 'connect.php'; ?>


    <section id="tableforroom">
        
        <h2>Available Rooms</h2>
        <table>
            <tr>
                <th>Room Number</th>
                <th>Room Type</th>
                <th>Cost Per Night</th>
                <th>Vacancy</th>
            </tr>

            <?php
            
            $sql = "SELECT * FROM Rooms WHERE Vacancy = 'Available'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['RoomNo']}</td>";
                    echo "<td>{$row['RoomType']}</td>";
                    echo "<td>{$row['CostPerNight']}</td>";
                    echo "<td>{$row['Vacancy']}</td>";
                    echo "</tr>";
                }
            } else {
                echo '<script>alert("No rooms available!")</script>';
            }
            ?>
        </table>
    </section>

    <section id="roomdetails">
    <h2>Enter your details</h2>

    <form id="bookingForm" method="POST" action="roombook.php">
        <label for="roomNo">Enter room to book:</label>
        <input type="text" id="roomNo" name="roomNo" required>
        <br>
        
        <label for="days">Number of days to book:</label>
        <input type="number" id="days" name="days" required min="1">
        <br>
        
        <label for="loginID">Login ID:</label>
        <input type="text" id="loginID" name="loginID" required>
        <br>
        
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required>
        <br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>
        <br>
        
        <button type="submit">Submit</button>
    </form>
</section>


</body>
</html>

<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $roomNo = $_POST['roomNo'];
    $days = $_POST['days'];
    $loginID = $_POST['loginID'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $checkRoomSql = "SELECT * FROM Rooms WHERE RoomNo = '$roomNo' AND Vacancy = 'Available'";
    $checkRoomResult = $conn->query($checkRoomSql);

    if ($checkRoomResult->num_rows > 0) {
        $insertGuestSql = "INSERT INTO Guests (LoginID, RoomNo, Name, Phone, Email, Addresss, Days) VALUES ('$loginID', '$roomNo', '$name', '$phone', '$email', '$address', '$days')";
        
        if ($conn->query($insertGuestSql) === TRUE) {

            $updateRoomSql = "UPDATE Rooms SET Vacancy = 'Booked' WHERE RoomNo = '$roomNo'";
            if ($conn->query($updateRoomSql) === TRUE) {
                $costPerNightSql = "SELECT CostPerNight FROM Rooms WHERE RoomNo = '$roomNo'";
                $costPerNightResult = $conn->query($costPerNightSql);

                if ($costPerNightResult->num_rows > 0) {
                    $row = $costPerNightResult->fetch_assoc();
                    $costPerNight = floatval($row['CostPerNight']);
                    $amount = $costPerNight * intval($days);
                    
                    echo "Room $roomNo has been booked and the total amount is $amount";
                } else {
                    echo "Error fetching cost per night: " . $conn->error;
                }
                
            } else {
                echo "Error updating room vacancy: " . $conn->error;
            }
        } else {
            echo "Error: " . $insertGuestSql . "<br>" . $conn->error;
        }
    } else {
        echo '<script>alert("Room is not available!")</script>';
    }
}

?>
