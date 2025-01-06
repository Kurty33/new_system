<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_account_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query
$query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

// SQL to search inventory
$sql = "SELECT * FROM user_account_tbl WHERE 
    firstname LIKE '%$query%' OR
    lastname LIKE '%$query%' OR
    email LIKE '%$query%' OR
    role LIKE '%$query%' OR
    birthdate LIKE '%$query%' OR 
    contact_number LIKE '%$query%'";

$result = $conn->query($sql);

// Prepare results
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>