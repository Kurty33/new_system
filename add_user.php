<?php
require "database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $birthdate = $_POST['birthdate'];
    $contact_number = $_POST['contact_number'];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $checkEmailQuery = "SELECT * FROM user_account_tbl WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists."]);
        exit;
    }

    $currentDate = new DateTime();
    $birthDate = new DateTime($birthdate);
    $age = $currentDate->diff($birthDate)->y;

    if ($age < 15) {
        echo json_encode(["status" => "error", "message" => "You must be at least 15 years old to register."]);
        exit;
    }

    $sql = "INSERT INTO user_account_tbl (firstname, lastname, email, role, birthdate, contact_number, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $firstname, $lastname, $email, $role, $birthdate, $contact_number, $password);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "UserID" => $conn->insert_id]);
    } else {
        echo json_encode(["success" => false, "message" => "Error adding user: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
