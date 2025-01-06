<?php
require "database.php";

if (isset($_POST["email"])) {
    $email = $_POST["email"];
    
    // Check if the email exists in the database
    $sql = "SELECT * FROM user_account_tbl WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Email exists
        echo json_encode(["status" => "error", "message" => "Email already exists."]);
    } else {
        // Email does not exist
        echo json_encode(["status" => "success"]);
    }
}
?>
