<?php
// Uncomment these lines during development if needed
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include('dbcon.php');

// Function to respond with a JSON message
function respond($status, $message = "")
{
    echo json_encode(["status" => $status, "message" => $message]);
    exit;
}

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond("error", "Invalid request method.");
}

// Retrieve and sanitize POST data
$id = $_POST['id'] ?? null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
$barcode = $_POST['barcode'] ?? null;
$code = $_POST['code'] ?? null;
$client = $_POST['client'] ?? null;
$stockTransfer = $_POST['stockTransfer'] ?? null;
$machineModel = $_POST['machineModel'] ?? null;
$machineSerial = $_POST['machineSerial'] ?? null;
$techName = $_POST['techName'] ?? null;
$owner = $_POST['owner'] ?? null;
$model = $_POST['model'] ?? null;
$description = $_POST['description'] ?? null;
$date_of_delivery = $_POST['date_of_delivery'] ?? null;

$returnedQuantity = isset($_POST['returnedQuantity']) ? (int)$_POST['returnedQuantity'] : 0;
$returnedBy = $_POST['returnedBy'] ?? null;
$returnedDeliveryDate = $_POST['returnedDeliveryDate'] ?? null;
$returnedBarcode = $_POST['returnedBarcode'] ?? null;


if ($returnedQuantity > $quantity || $returnedQuantity <= 0) {
    respond("error", "Returned quantity can't exceed delivered quantity or lessthan equal to 0.");
}

// get the info on delivery Out
$currentSelect = "SELECT * FROM delivery_out WHERE id = ?";
$stmntCurrent = $con->prepare($currentSelect);

if (!$stmntCurrent) {
    respond("error", "Database error: " . $con->error);
}

$stmntCurrent->bind_param("i", $id);
$stmntCurrent->execute();
$currentResult = $stmntCurrent->get_result();
$row = $currentResult->fetch_assoc();

if (!$row) {
    $stmntCurrent->close();
    respond("error", "Item not found for the specified details.");
}

$currentQuantity = (int)$row['quantity'];
$currentReturnQuantity = (int)$row['return_quantity'];
$stmntCurrent->close();

if (($currentReturnQuantity + $returnedQuantity) > $currentQuantity) {
    respond("error", "Exceed to the current returned quantity. the current quantity is " . $currentQuantity . ' and the returned quanity is ' . $currentReturnQuantity);
}
$newReturnedQuantity = $currentReturnQuantity + $returnedQuantity;


date_default_timezone_set('Asia/Manila');
$currentDate = date("Y-m-d h:i:s A");


// === Update delivery_out Table ===
$sqlUpdateTable = "UPDATE delivery_out SET return_quantity = ?, return_date = ?, returned_by = ?, date = ? WHERE id = ?";
$stmntUpdateDatabase = $con->prepare($sqlUpdateTable);
$stmntUpdateDatabase->bind_param("ssssi", $newReturnedQuantity, $returnedDeliveryDate, $returnedBy, $currentDate, $id);

// === Insert into delivery_out_history Table ===
$sqlInsertTable = "INSERT INTO delivery_out_history (
    date, returned_by, model, description, code, owner, date_of_delivery, barcode, 
    quantity, client, machine_model, machine_serial, tech_name, stock_transfer, 
    return_quantity, return_date, type, returned_barcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$sqlInsertNotif = "INSERT INTO notifications (date, type , message, status, user_id) VALUES (?, ?, ?, ?, ?)";
$smmntInsertNotif = $con_waste_warehouse->prepare($sqlInsertNotif);

$type_notif = "Returned Waste Parts";
$message_notif = "Returned Waste Parts with barcode: " . $barcode . " and quantity: " . $returnedQuantity . " has been returned by " . $returnedBy;
$status_notif = (int)"0";
$userId = 2;

if (!$smmntInsertNotif) {
    respond("error", "Database error: " . $con->error);
}

$smmntInsertNotif->bind_param(
    "sssii",  // 5 placeholders, with appropriate types
    $currentDate,
    $type_notif,
    $message_notif,
    $status_notif,
    $userId
);

$stmntInsertTable = $con->prepare($sqlInsertTable);
$type = "OUT";
$stmntInsertTable->bind_param(
    "ssssssssisssssisss",  // 18 placeholders, with appropriate types
    $currentDate,
    $returnedBy,
    $model,
    $description,
    $code,
    $owner,
    $date_of_delivery,
    $barcode,
    $quantity,            // int
    $client,
    $machineModel,
    $machineSerial,
    $techName,
    $stockTransfer,
    $returnedQuantity,    // int
    $returnedDeliveryDate,
    $type,
    $returnedBarcode
);

// === Execute Queries ===
$updateSuccess = $stmntUpdateDatabase->execute();
$insertSuccess = $stmntInsertTable->execute();
$insertNotifSuccess = $smmntInsertNotif->execute();

$stmntUpdateDatabase->close();
$stmntInsertTable->close();
$smmntInsertNotif->close();

if ($updateSuccess && $insertSuccess && $insertNotifSuccess) {
    respond("success", "Data recorded and updated successfully.");
} else {
    respond("error", "Failed to update or insert data.");
}
