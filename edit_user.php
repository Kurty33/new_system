<?php
require "database.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle fetching user data for modal
    $UserID = $_GET['UserID'];

    $sql = "SELECT * FROM user_account_tbl WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $UserID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user); // Return user data as JSON
    } else {
        echo json_encode(['error' => 'User not found']);
    }

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle updating user data
    $UserID = $_POST['UserID'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $birthdate = $_POST['birthdate'];
    $contact_number = $_POST['contact_number'];

    $sql = "UPDATE user_account_tbl SET 
                firstname = ?, 
                lastname = ?, 
                email = ?, 
                role = ?, 
                birthdate = ?, 
                contact_number = ? 
            WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $firstname, $lastname, $email, $role, $birthdate, $contact_number, $UserID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
