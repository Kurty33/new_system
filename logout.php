<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location:login.php");

    $sql = "UPDATE user_account_tbl 
        SET status = 'inactive', last_active = NOW() 
        WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['UserID']);
$stmt->execute();
$stmt->close();
?>