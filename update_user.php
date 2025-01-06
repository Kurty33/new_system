<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['UserID']);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $birthdate = $_POST['birthdate'];
    $contactNumber = $_POST['contact_number'];

    $query = "UPDATE user_account_tbl SET firstname = ?, lastname = ?, email = ?, role = ?, birthdate = ?, contact_number = ? WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $firstname, $lastname, $email, $role, $birthdate, $contactNumber, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
    }
}
?>
