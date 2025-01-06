<?php
require "database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $UserID = $_POST["UserID"] ?? null;

    if ($UserID) {
        $sql = "DELETE FROM user_account_tbl WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $UserID);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to delete user."]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid user ID."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>
