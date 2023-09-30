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

// Fetch repair requests
$sql = "SELECT * FROM repair_requests";
$result = $conn->query($sql);

$repairRequests = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $repairRequests[] = $row;
    }
}

// Initialize success message
$successMessage = "";

// Update Repair Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_request"])) {
    // Code for updating a repair request
    $request_id = sanitizeInput($_POST["request_id"]);
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

    $sql = "UPDATE repair_requests SET
            received_date = '$received_date',
            received_dsrr_number = '$received_dsrr_number',
            depot = '$depot',
            item = '$item',
            make = '$make',
            serial_number = '$serial_number',
            problem_description = '$problem_description',
            solved_description = '$solved_description',
            send_date = '$send_date',
            send_dsrr_number = '$send_dsrr_number'
            WHERE id = $request_id";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Repair Request Updated Successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TNSTC(kum) Ltd., Trichy Region - Update Repair Request</title>
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
        <h2>Update Repair Request</h2>
        <form method="POST" action="update_request.php">
            <label for="request_id">Select Request to Update:</label>
            <select name="request_id" required>
                <option value="">Select Request</option>
                <?php foreach ($repairRequests as $request) : ?>
                    <option value="<?php echo $request['id']; ?>"><?php echo $request['id']; ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" name="fetch_request" value="Fetch Request">
        </form>

        <?php if (isset($_POST["fetch_request"]) && !empty($_POST["request_id"])) : ?>
            <h3>Edit Repair Request</h3>
            <form method="POST" action="update_request.php">
                <?php
                $selectedRequestId = sanitizeInput($_POST["request_id"]);
                $selectedRequest = null;

                foreach ($repairRequests as $request) {
                    if ($request["id"] == $selectedRequestId) {
                        $selectedRequest = $request;
                        break;
                    }
                }
                ?>

                <input type="hidden" name="request_id" value="<?php echo $selectedRequestId; ?>">

                <label for="received_date">Received Date:</label>
                <input type="date" name="received_date" value="<?php echo $selectedRequest['received_date']; ?>" required><br>

                <label for="received_dsrr_number">Received DSRR Number:</label>
                <input type="text" name="received_dsrr_number" value="<?php echo $selectedRequest['received_dsrr_number']; ?>" required><br>

                <label for="depot">Depot:</label>
                <input type="text" name="depot" value="<?php echo $selectedRequest['depot']; ?>" required><br>

                <label for="item">Item:</label>
                <input type="text" name="item" value="<?php echo $selectedRequest['item']; ?>" required><br>

                <label for="make">Make:</label>
                <input type="text" name="make" value="<?php echo $selectedRequest['make']; ?>" required><br>

                <label for="serial_number">Serial Number:</label>
                <input type="text" name="serial_number" value="<?php echo $selectedRequest['serial_number']; ?>" required><br>

                <label for="problem_description">Problem Description:</label><br>
                <textarea name="problem_description" rows="4" required><?php echo $selectedRequest['problem_description']; ?></textarea><br>

                <label for="solved_description">Solved Description:</label><br>
                <textarea name="solved_description" rows="4"><?php echo $selectedRequest['solved_description']; ?></textarea><br>

                <label for="send_date">Send Date:</label>
                <input type="date" name="send_date" value="<?php echo $selectedRequest['send_date']; ?>" required><br>

                <label for="send_dsrr_number">Send DSRR Number:</label>
                <input type="text" name="send_dsrr_number" value="<?php echo $selectedRequest['send_dsrr_number']; ?>" required><br>

                <input type="submit" name="update_request" value="Update">
            </form>
        <?php endif; ?>

        <!-- Display success message -->
        <?php if (!empty($successMessage)): ?>
            <div class="success-message">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> TNSTC(kum) Ltd., Trichy Region
    </footer>
</body>
</html>
