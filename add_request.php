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
    // Sanitize and validate inputs
    $received_date = sanitizeInput($_POST["received_date"]);
    $received_dsrr_number = sanitizeInput($_POST["received_dsrr_number"]);
    $depot = sanitizeInput($_POST["depot"]);
    $item = sanitizeInput($_POST["item"]);
    $make = sanitizeInput($_POST["make"]);
    $serial_number = sanitizeInput($_POST["serial_number"]);
    $problem_description = sanitizeInput($_POST["problem_description"]);
    $solved_description = sanitizeInput($_POST["solved_description"]);
    $send_date = sanitizeInput($_POST["send_date"]);
    $send_dsrr_number = sanitizeInput($_POST["send_dsrr_number"]);

    // Insert data into the repair_requests table
    $sql = "INSERT INTO repair_requests (received_date, received_dsrr_number, depot, item, make, serial_number, problem_description, solved_description, send_date, send_dsrr_number)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $received_date, $received_dsrr_number, $depot, $item, $make, $serial_number, $problem_description, $solved_description, $send_date, $send_dsrr_number);

    if ($stmt->execute()) {
        $successMessage = "Repair Request Added Successfully!";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TNSTC(kum) Ltd., Trichy Region - Add Repair Request</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .success {
            background-color: #FFFF00;
            color: #000;
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
        <h2>Add Repair Request</h2>
        <?php
        if (isset($successMessage)) {
            echo "<div class='success'>$successMessage</div>";
        } elseif (isset($errorMessage)) {
            echo "<div class='error'>$errorMessage</div>";
        }
        ?>
        <form method="POST" action="add_request.php">
            <label for="received_date">Received Date:</label>
            <input type="date" name="received_date" required><br>

            <label for="received_dsrr_number">Received DSRR Number:</label>
            <input type="text" name="received_dsrr_number" required><br>

            <label for="depot">Depot:</label>
            <input type="text" name="depot" required><br>

            <label for="item">Item:</label>
            <input type="text" name="item" required><br>

            <label for="make">Make:</label>
            <input type="text" name="make" required><br>

            <label for="serial_number">Serial Number:</label>
            <input type="text" name="serial_number" required><br>

            <label for="problem_description">Problem Description:</label><br>
            <textarea name="problem_description" rows="4" required></textarea><br>

            <label for="solved_description">Solved Description:</label><br>
            <textarea name="solved_description" rows="4"></textarea><br>

            <label for="send_date">Send Date:</label>
            <input type="date" name="send_date"><br>

            <label for="send_dsrr_number">Send DSRR Number:</label>
            <input type="text" name="send_dsrr_number"><br>

            <input type="submit" name="add_request" value="Submit">
        </form>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> TNSTC(kum) Ltd., Trichy Region
    </footer>
</body>
</html>
