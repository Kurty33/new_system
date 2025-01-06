<?php
require 'database.php';

if (isset($_GET['UserID'])) {
    $userId = intval($_GET['UserID']);
    $query = "SELECT * FROM user_account_tbl WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    echo json_encode($user);
} else {
    echo json_encode(['error' => 'User ID not provided.']);
}
?>
