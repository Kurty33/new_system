<?php
include 'database.php';

$productId = $_POST['productId'];  // Match input name in form
$quantityNeeded = $_POST['quantityNeeded'];  // Match input name in form
$deliveryDays = $_POST['deliveryDays'];  // Match input name in form

$sql = "INSERT INTO reorder_tbl (ProductID, quantity_needed, delivery_date, reorder_date) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $productId, $quantityNeeded, $deliveryDays);

if ($stmt->execute()) {
    echo "Reorder submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}
?>
