<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="styling.css">
</head>
<body>
<h1>Hotel Transylvania<br>Admin Page</h1>
<a href="index.php"><button id="bookingbutton">Home</button></a>

<?php include 'connect.php'; ?>

<section id="guestsandrooms">
    <h2>Room Info</h2>
    <table>
        <tr>
            <th>Room Number</th>
            <th>Room Type</th>
            <th>Cost Per Night</strong>
            <th>Vacancy</th>
            <th>LoginID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Stay Duration</th>
        </tr>

        <?php
        $sql = "SELECT * FROM Guests G RIGHT JOIN Rooms R on G.RoomNo = R.RoomNo";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['RoomNo']}</td>";
                echo "<td>{$row['RoomType']}</td>";
                echo "<td>{$row['CostPerNight']}</td>";
                echo "<td>{$row['Vacancy']}</td>";
                echo "<td>{$row['LoginID']}</td>";
                echo "<td>{$row['Name']}</td>";
                echo "<td>{$row['Phone']}</td>";
                echo "<td>{$row['Email']}</td>";
                echo "<td>{$row['Addresss']}</td>";
                echo "<td>{$row['Days']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No rooms available!</td></tr>";
        }
        ?>
    </table>
</section>

<section id="editrooms">
    <h2>Add a new room:</h2>
    <form id="newRoom" method="POST" action="admin.php">
        <input type="hidden" name="action" value="add">
        <label for="roomNo">Enter room to add:</label>
        <input type="number" id="roomNo" name="roomNo" required>
        <br>

        <label for="type">Enter room type:</label>
        <select id="type" name="type" required>
            <option value="Single">Single</option>
            <option value="Double">Double</option>
            <option value="Suite">Suite</option>
        </select>
        <br>

        <label for="cost">Enter cost per night:</label>
        <input type="number" id="cost" name="cost" step="0.01" required>
        <br>

        <label for="vacancy">Enter vacancy:</label>
        <select id="vacancy" name="vacancy" required>
            <option value="Available">Available</option>
        </select>
        <br>

        <button type="submit">Create Room</button>
    </form>

    <h2>Delete a room:</h2>
    <form id="deleteRoom" method="POST" action="admin.php">
        <input type="hidden" name="action" value="delete">
        <label for="room">Enter room to delete:</label>
        <input type="number" id="room" name="room" required><br>
        <button type="submit">Delete Room</button>
    </form>

    <h2>Guest Checkout:</h2>
    <form id="deleteUser" method="POST" action="admin.php">
        <input type="hidden" name="action" value="deleteG">
        <label for="id">Enter LoginID to Check Out:</label>
        <input type="number" id="id" name="id" required><br>
        <button type="submit">Checkout</button>
    </form>
</section>

</body>
</html>


<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'add') {
        $roomNo = $_POST['roomNo'];
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $cost = floatval($_POST['cost']);
        $vacancy = mysqli_real_escape_string($conn, $_POST['vacancy']);

        $checkRoomSql = "SELECT * FROM Rooms WHERE RoomNo = '$roomNo'";
        $checkRoomResult = $conn->query($checkRoomSql);

        if ($checkRoomResult->num_rows === 0) {
            $insertRoomSql = "INSERT INTO Rooms (RoomNo, RoomType, CostPerNight, Vacancy) VALUES ('$roomNo', '$type', '$cost', '$vacancy')";
            
            if ($conn->query($insertRoomSql) === TRUE) {
                echo '<script>alert("Room has been added!")</script>';
            } else {
                echo "Error: " . $insertRoomSql . "<br>" . $conn->error;
            }
        } else {
            echo '<script>alert("Room already exists!")</script>';
        }
    } elseif ($action == 'delete') {
        $room = $_POST['room'];

        $checkRoom1Sql = "SELECT * FROM Rooms WHERE RoomNo = '$room'";
        $checkRoom1Result = $conn->query($checkRoom1Sql);

        if ($checkRoom1Result->num_rows > 0) {
            $deleteRoomSql = "DELETE FROM Rooms WHERE RoomNo = '$room'";

            if ($conn->query($deleteRoomSql) === TRUE) {
                echo '<script>alert("Room has been removed!")</script>';
            } else {
                echo "Error: " . $deleteRoomSql . "<br>" . $conn->error;
            }
        } else {
            echo '<script>alert("Room does not exist!")</script>';
        }
    } elseif ($action == 'deleteG') {
        $id = $_POST['id'];
    
        $checkGuestSql = "SELECT * FROM Guests WHERE LoginID = '$id'";
        $checkGuestResult = $conn->query($checkGuestSql);
    
        if ($checkGuestResult->num_rows > 0) {
            $deleteGuestSql = "DELETE FROM Guests WHERE LoginID = '$id'";
            if ($conn->query($deleteGuestSql) === TRUE) {
                echo '<script>alert("Guest has been checked out!")</script>';
            } else {
                echo "Error deleting guest: " . $conn->error;
            }
        } else {
            echo '<script>alert("Guest entry does not exist!")</script>';
        }
    }
}
?>
