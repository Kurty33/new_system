<?php
header('Content-Type: application/json');
require 'database.php'; // Update with your database connection

// Retrieve the posted data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['ProductID'])) {
    $productId = $data['ProductID'];

    // Start a transaction to ensure the action is atomic
    $conn->begin_transaction();

    try {
        // Step 1: Remove the product from the archives_tbl
        $sql = "DELETE FROM archives_tbl WHERE ProductID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
}

$conn->close();
?>
