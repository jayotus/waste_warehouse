<?php
// session_start(); // Removed session_start()
include("dbcon.php");

// $user_id = $_SESSION['user_id']; // Get logged in user ID
$user_id = 2; // Hardcoded user ID for testing,  Consider removing this in production

date_default_timezone_set('Asia/Manila');
$today = date("Y-m-d"); // Get today's date (YYYY-MM-DD) - Not used

//Check if the 'view' parameter is set to 'yes' in the GET request.
if (isset($_GET['view']) && $_GET['view'] == 'yes') {
    // Update the notification status to 'read' (status = 1) for the specific user.
    $sqlUpdate = "UPDATE notifications SET status = 1 WHERE status = 0 AND user_id = ?"; // Use a prepared statement
    $stmt = mysqli_prepare($con_waste_warehouse, $sqlUpdate); // Prepare the statement
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id); // Bind the user ID parameter
        mysqli_stmt_execute($stmt); // Execute the update
        mysqli_stmt_close($stmt); // Close the statement
    } else {
        // Handle the error appropriately.  Do NOT use die() in a live application.
        echo json_encode(['error' => 'Failed to prepare update statement: ' . mysqli_error($con_waste_warehouse)]);
        exit; // Stop further execution.
    }
}

// COUNT UNSEEN NOTIFICATIONS for the specific user.
$sqlCount = "SELECT COUNT(*) as count FROM notifications WHERE status = 0 AND user_id = ?"; // Use a prepared statement
$stmtCount = mysqli_prepare($con_waste_warehouse, $sqlCount);
if($stmtCount){
    mysqli_stmt_bind_param($stmtCount, "i", $user_id);
    mysqli_stmt_execute($stmtCount);
    mysqli_stmt_bind_result($stmtCount, $unseenNotification); // Bind the result
    mysqli_stmt_fetch($stmtCount);  // Fetch the value into $unseenNotification
    mysqli_stmt_close($stmtCount);
} else {
    echo json_encode(['error' => 'Failed to prepare count statement' . mysqli_error($con_waste_warehouse)]);
    exit;
}
// $rowCount = mysqli_fetch_assoc($resultCount); // Removed - Use prepared statement result
// $unseenNotification = $rowCount['count']; // Already fetched from prepared statement

// FETCH NOTIFICATIONS for the specific user, ordered by date.
$sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY date DESC LIMIT 10"; //  Use a prepared statement
$stmtSelect = mysqli_prepare($con_waste_warehouse, $sql);
if($stmtSelect){
     mysqli_stmt_bind_param($stmtSelect, "i", $user_id);
     mysqli_stmt_execute($stmtSelect);
     $result = mysqli_stmt_get_result($stmtSelect); // Get the result set
} else {
    echo json_encode(['error' => 'Failed to prepare select statement' . mysqli_error($con_waste_warehouse)]);
    exit;
}

// $result = $con_waste_warehouse->query($sql); // Removed - Use prepared statement

if (!$result) {
    //  die("Error executing query: " . $con_waste_warehouse->error); // Removed die
    echo json_encode(['error' => 'Error executing query: ' . mysqli_error($con_waste_warehouse)]);
    exit;
}

$notifications = [];
$output = "";

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '<ul class="notification-dropdown">
                            <li class="date-row">
                                <span class="type">' . htmlspecialchars($row['type']) . '</span>
                                <span class="date-text">' . htmlspecialchars(date("Y-m-d h:i:s A", strtotime($row['date']))) . '</span>
                            </li>
                            <li class="message">' . htmlspecialchars($row['message']) . '</li>
                            <li class="name">' . htmlspecialchars($row['name']) . '</li>
                        </ul>';
    }
}

header('Content-Type: application/json');
echo json_encode([
        'count' => $unseenNotification,
        'notification' => $output
    ]);
?>
