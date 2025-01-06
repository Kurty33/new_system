<?php
// archive_item.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productId = $data['ProductID'];

    // Database connection
    include 'database.php'; // Assume this includes $conn

    // Insert into archives_tbl
    $query = "INSERT INTO archives_tbl (ProductID, Archive_date) VALUES (?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        // Success
        echo json_encode(['success' => true]);
    } else {
        // Failure
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
