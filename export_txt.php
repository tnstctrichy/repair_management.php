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

// Initialize success messages
$successMessageTxt = "";
$successMessageCsv = "";

// Export to .txt or .csv
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["export_btn"])) {
    $format = $_POST["export"];
    
    // Define the SQL query to fetch repair requests
    $sql = "SELECT * FROM repair_requests";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        if ($format === "txt") {
            // Export to .txt
            $filename = "repair_requests.txt";
            $mime = "text/plain";
            
            // Create the tabulated data with spacing
            $txtData = "ID\tReceived Date\tReceived DSRR Number\tDepot\tItem\tMake\tSerial Number\tProblem Description\tSolved Repair Description\tSend Date\tSend DSRR Number\n";
            while ($row = $result->fetch_assoc()) {
                $txtData .= sprintf(
                    "%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n",
                    $row['id'],
                    $row['received_date'],
                    $row['received_dsrr_number'],
                    $row['depot'],
                    $row['item'],
                    $row['make'],
                    $row['serial_number'],
                    $row['problem_description'],
                    $row['solved_description'],
                    $row['send_date'],
                    $row['send_dsrr_number']
                );
            }
            
            // Output as a downloadable file
            header('Content-Type: ' . $mime);
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            echo $txtData;
            
            $successMessageTxt = "Data exported to $filename";
        } elseif ($format === "csv") {
            // Export to .csv
            $filename = "repair_requests.csv";
            $mime = "text/csv";
            
            // Create the CSV data
            $output = fopen('php://output', 'w');
            fputcsv($output, ["ID", "Received Date", "Received DSRR Number", "Depot", "Item", "Make", "Serial Number", "Problem Description", "Solved Repair Description", "Send Date", "Send DSRR Number"]);
            while ($row = $result->fetch_assoc()) {
                fputcsv($output, $row);
            }
            fclose($output);
            
            // Output as a downloadable file
            header('Content-Type: ' . $mime);
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $successMessageCsv = "Data exported to $filename";
        }
    } else {
        $successMessageTxt = "No repair requests found to export.";
        $successMessageCsv = "No repair requests found to export.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TNSTC(kum) Ltd., Trichy Region - Export to .txt/.csv</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .success-message {
            background-color: yellow;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
            <li><a href="export_txt.php">Export to .txt/.csv</a></li>
        </ul>
    </nav>
    <main>
        <h2>Export to .txt/.csv</h2>

        <!-- Display success messages -->
        <?php if (!empty($successMessageTxt)): ?>
        <div class="success-message">
            <?php echo $successMessageTxt; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($successMessageCsv)): ?>
        <div class="success-message">
            <?php echo $successMessageCsv; ?>
        </div>
        <?php endif; ?>

        <!-- Export form -->
        <form method="POST" action="export_txt.php">
            <label for="export">Select Format:</label>
            <select name="export" id="export">
                <option value="txt">Export as .txt</option>
                <option value="csv">Export as .csv</option>
            </select>
            <input type="submit" name="export_btn" value="Export">
        </form>

        <!-- Display tabulated table -->
        <h3>Repair Requests</h3>
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
                // Fetch and display data in the table
                $sql = "SELECT * FROM repair_requests";
                $result = $conn->query($sql);

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
                ?>
            </tbody>
        </table>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> TNSTC(kum) Ltd., Trichy Region
    </footer>
</body>
</html>
