<?php
session_start();

require 'database.php';

// Fetch the user details using session UserID
$user_id = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';
$sql = "SELECT * FROM user_account_tbl WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$firstname = isset($user['firstname']) ? $user['firstname'] : '';
$lastname = isset($user['lastname']) ? $user['lastname'] : '';
$email = isset($user['email']) ? $user['email'] : '';
$role = isset($user['role']) ? $user['role'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link id="favicon" rel="icon" type="image/png" href="images/logo.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
    <link href="https://fonts.cdnfonts.com/css/gilroy-bold" rel="stylesheet">
    <style>
        body {
            font-family: 'Gilroy-Bold', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: white;
            position: fixed;
            border-right: 3px solid #604be8;
            transition: all 0.3s ease;
        }

        .sidebar .sidebar-header {
            font-size: 1.5rem;
            color: #604be8;
            text-align: center;
            padding: 20px 10px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar .sidebar-item {
            padding: 10px 20px;
            margin: 5px 10px;
            color: #8E91AF;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .sidebar .sidebar-item:hover {
            background-color: #f0f0f5;
            color: #604be8;
        }

        .content {
            margin-left: 250px;
            padding: 40px;
            transition: margin-left 0.3s ease;
        }

        .profile-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: 0 auto;
        }

        .profile-container h2 {
            text-align: center;
            color: #604be8;
            margin-bottom: 30px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 55px; /* Adjust height */
            padding: 10px 20px; /* Adjust padding */
            background-color: #f1f1f1;
            border: none;
            color: #333;
            font-size: 16px;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-top: 20px;
            border-radius: 8px; /* Rounded corners */
        }

        .logout-btn img {
            width: 24px; /* Adjust icon size */
            height: 24px;
            margin-right: 10px;
            transition: filter 0.3s ease; /* Smooth transition for hover effect */
        }

        .logout-btn:hover {
            background-color: red;
            color: white;
            cursor: pointer;
        }

        .logout-btn:hover img {
            filter: brightness(0) invert(1); /* Turns icon white on hover */
        }

        .btn-primary {
            background-color: #604BE8 !important;
            border-color: #604BE8 !important;
        }

        .btn-primary:hover {
            background-color: #4d3cb3 !important; /* Slightly darker shade for hover effect */
            border-color: #4d3cb3 !important;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .content {
                margin-left: 80px;
            }

            .profile-container {
                padding: 20px;
                margin: 10px;
            }

            .profile-container h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-header">
        <img src="images/logo.png" alt="Logo" style="width: 30px; height: 30px; margin-right: 10px;">
        <span>Profile Account</span>
    </div>
    <a href="admin_page.php" class="sidebar-item">
        <i class="fas fa-home"></i>
        <span>Go Back</span>
    </a>
</div>

<div class="content">
    <div class="profile-container">
        <h2>Profile Information</h2>
        <form action="update_profile.php" method="POST">
            <div class="row mb-3">
                <!-- First Name -->
                <div class="col-md-6">
                    <label for="firstname" class="form-label">First Name:</label>
                    <input type="text" class="form-control" name="firstname" 
                           value="<?php echo htmlspecialchars($firstname); ?>" required>
                </div>

                <!-- Last Name -->
                <div class="col-md-6">
                    <label for="lastname" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" name="lastname" 
                           value="<?php echo htmlspecialchars($lastname); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Email -->
                <div class="col-md-6">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" 
                           value="<?php echo htmlspecialchars($email); ?>" required>
                </div>

                <!-- Role -->
                <div class="col-md-6">
                    <label for="role" class="form-label">Role:</label>
                    <input type="text" class="form-control" name="role" 
                           value="<?php echo htmlspecialchars($role); ?>" disabled>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </form>

        <form action="logout.php" method="POST">
    <button type="submit" class="logout-btn w-100">
        <img src="images/logout.png" alt="Logout Icon">
        Logout
    </button>
</form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="js/header.js"></script>
</body>
</html>
