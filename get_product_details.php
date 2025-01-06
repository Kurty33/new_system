<?php
include 'database.php';

$productId = $_GET['id'];  // Make sure it's 'id' from the URL query parameter

$sql = "SELECT ProductID, product_name, product_category, quantity FROM inventory_tbl WHERE ProductID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    echo json_encode($product);
} else {
    echo json_encode(["error" => "Product not found."]);
}
?>
