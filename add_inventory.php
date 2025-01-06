<?php
header('Content-Type: application/json');
require 'database.php'; // Update with your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $category = $_POST['product_category'];
    $stock = $_POST['quantity'];
    $price = $_POST['unit_price'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Get image data
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageType = $_FILES['image']['type'];

    $sql = "INSERT INTO inventory_tbl (product_name, product_category, quantity, unit_price, image) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssids", $productName, $category, $stock, $price, $imageData);

    if ($stmt->execute()) {
        $lastId = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'product' => [
                'product_name' => $productName,
                'product_category' => $category,
                'quantity' => $stock,
                'unit_price' => $price,
            ]
        ]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false]);
}
}
?>
