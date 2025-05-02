<?php
include("dbcon.php");

// Simulated user ID for now — replace with session in production
$user_id = 2;

date_default_timezone_set('Asia/Manila');

// ✅ Mark notifications as seen
if (isset($_GET['view']) && $_GET['view'] == 'yes') {
    $sqlUpdate = "UPDATE notifications SET status = 1 WHERE status = 0 AND user_id = ?";
    $stmt = mysqli_prepare($con_waste_warehouse, $sqlUpdate);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['error' => 'Failed to prepare update statement: ' . mysqli_error($con_waste_warehouse)]);
        exit;
    }
}

// ✅ Count unseen notifications
$sqlCount = "SELECT COUNT(*) as count FROM notifications WHERE status = 0 AND user_id = ?";
$stmtCount = mysqli_prepare($con_waste_warehouse, $sqlCount);
if ($stmtCount) {
    mysqli_stmt_bind_param($stmtCount, "i", $user_id);
    mysqli_stmt_execute($stmtCount);
    mysqli_stmt_bind_result($stmtCount, $unseenNotification);
    mysqli_stmt_fetch($stmtCount);
    mysqli_stmt_close($stmtCount);
} else {
    echo json_encode(['error' => 'Failed to prepare count statement: ' . mysqli_error($con_waste_warehouse)]);
    exit;
}

// ✅ Fetch notifications
$sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY date DESC LIMIT 10";
$stmtSelect = mysqli_prepare($con_waste_warehouse, $sql);
if ($stmtSelect) {
    mysqli_stmt_bind_param($stmtSelect, "i", $user_id);
    mysqli_stmt_execute($stmtSelect);
    $result = mysqli_stmt_get_result($stmtSelect);
} else {
    echo json_encode(['error' => 'Failed to prepare select statement: ' . mysqli_error($con_waste_warehouse)]);
    exit;
}

// ✅ Format notifications
$output = "";
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Safely format date and other content
        $formattedDate = date("Y-m-d h:i:s", strtotime($row['date'])); // 12-hour format with AM/PM
        $output .= '<ul class="notification-dropdown">
                        <li class="date-row">
                            <span class="type">' . htmlspecialchars($row['type']) . '</span>
                            <span class="date-text">' . htmlspecialchars($formattedDate) . '</span>
                        </li>
                        <li class="message">' . htmlspecialchars($row['message']) . '</li>
                        <li class="name">' . htmlspecialchars($row['name']) . '</li>
                    </ul>';
    }
}

// ✅ JSON response
header('Content-Type: application/json');
echo json_encode([
    'count' => $unseenNotification,
    'notification' => $output
]);
