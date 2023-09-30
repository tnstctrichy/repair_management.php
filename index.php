<?php
// Database connection (replace with your actual database details)
$host = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$database = "repair_management";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitizeInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

// Add Repair Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_request"])) {
    // Code for adding a repair request (keep this code)
    // ...
}

// Update Repair Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_request"])) {
    // Code for updating a repair request (keep this code)
    // ...
}

// Delete Repair Request
if (isset($_GET["delete_id"])) {
    $request_id = $_GET["delete_id"];
    $sql = "DELETE FROM repair_requests WHERE id = $request_id";

    if ($conn->query($sql) === TRUE) {
        echo "Repair Request Deleted Successfully!";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Export to .txt (keep this code)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["export_txt"])) {
    // Code for exporting to .txt
    // ...
}

// Fetch all repair requests for viewing
$sql = "SELECT * FROM repair_requests";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TNSTC(kum) Ltd., Trichy Region - Computer Repair Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>TNSTC(kum) Ltd., Trichy Region</h1>
        <h2>Computer Repair Management System</h2>
    </header>
    <!-- Menu -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="add_request.php">Add Repair Request</a></li>
            <li><a href="update_request.php">Update Repair Request</a></li>
            <li><a href="delete_request.php">Delete Repair Request</a></li>
            <li><a href="export_txt.php">Export to .txt</a></li>
            
        </ul>
    </nav>
    <main>
        <h2>All Repair Requests</h2>
        <!-- Display Repair Requests in a table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Received Date</th>
                    <th>Received DSRR Number</th>
                    <th>Depot</th>
                    <th>Item</th>
                    <th>Make</th>
                    <th>Serial Number</th>
                    <th>Problem Description</th>
                    <th>Solved Repair Description</th>
                    <th>Send Date</th>
                    <th>Send DSRR Number</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['received_date']}</td>";
                        echo "<td>{$row['received_dsrr_number']}</td>";
                        echo "<td>{$row['depot']}</td>";
                        echo "<td>{$row['item']}</td>";
                        echo "<td>{$row['make']}</td>";
                        echo "<td>{$row['serial_number']}</td>";
                        echo "<td>{$row['problem_description']}</td>";
                        echo "<td>{$row['solved_description']}</td>";
                        echo "<td>{$row['send_date']}</td>";
                        echo "<td>{$row['send_dsrr_number']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No repair requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> TNSTC(kum) Ltd., Trichy Region
    </footer>
</body>
</html>
