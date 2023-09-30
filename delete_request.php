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

// Fetch repair requests from the database
$sql = "SELECT * FROM repair_requests";
$result = $conn->query($sql);

// Initialize the success message
$successMessage = "";

// Handle request deletion
if (isset($_GET["delete_id"])) {
    $request_id = $_GET["delete_id"];
    $deleteSql = "DELETE FROM repair_requests WHERE id = $request_id";

    if ($conn->query($deleteSql) === TRUE) {
        $successMessage = "Repair Request Deleted Successfully!";
    } else {
        $successMessage = "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TNSTC(kum) Ltd., Trichy Region - Delete Repair Request</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .success-message {
            background-color: yellow;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
    </style>
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
        <h2>Delete Repair Request</h2>

        <!-- Display success message -->
        <?php if (!empty($successMessage)): ?>
        <div class="success-message">
            <?php echo $successMessage; ?>
        </div>
        <?php endif; ?>

        <!-- Display repair requests as a table -->
        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Received Date</th>
                    <th>Received DSRR Number</th>
                    <th>Depot</th>
                    <th>Item</th>
                                        <!-- Other table headers here -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['received_date']; ?></td>
                    <td><?php echo $row['received_dsrr_number']; ?></td>
                    <td><?php echo $row['depot']; ?></td>
                    <td><?php echo $row['item']; ?></td>
                    <!-- Other table data here -->
                    <td><a href="delete_request.php?delete_id=<?php echo $row['id']; ?>">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No repair requests found.</p>
        <?php endif; ?>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> TNSTC(kum) Ltd., Trichy Region
    </footer>
</body>
</html>
