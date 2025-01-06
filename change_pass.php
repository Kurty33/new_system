<?php
    session_start();
?>

<?php
require_once "database.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <link id="favicon" rel="icon" type="image/png" href="images/logo.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
    <link href="https://fonts.cdnfonts.com/css/gilroy-bold" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Content -->
            <div class="col-md-7 col-12 left-content">
                <!-- Top Section (Logo and Title) -->
                <div class="top-section">
                    <div class="logo-container">
                        <img src="images/logo.png" alt="Logo" class="logo"> <!-- Replace with your logo path -->
                        <h1 class="mb-0">CS Flores Inventory System</h1>
                    </div>
                </div>

                <!-- Carousel -->
                <div class="carousel-container mt-auto">
                    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="images/side1.png" class="d-block w-100" alt="Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="images/side2.png" class="d-block w-100" alt="Image 2">
                            </div>
                            <div class="carousel-item">
                                <img src="images/side3.png" class="d-block w-100" alt="Image 3">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>


            <!-- Right Content (Login Form) -->
            <div class="col-md-5 col-12 right-content">
                <div class="login-title">Change Password</div>
                <div class="login-form">
                    <!-- Added login title -->
                    
                    <form action="change_pass.php" method="POST">
                    <?php
                    
                    $errors = array(); // Array to hold login errors

                    if (isset($_POST["submit"]) && !empty($_POST["password"]) && !empty($_POST["confirm_password"])) {
                        $password = $_POST["password"];
                        $confirmPassword = $_POST["confirm_password"];
                        $email = $_SESSION["email"];

                        $errors = [];

                        if (empty($password) || empty($confirmPassword)) {
                            $errors[] = "Both password fields are required.";
                        } elseif ($password !== $confirmPassword) {
                            $errors[] = "Passwords do not match.";
                        } elseif (strlen($password) < 8) {
                            $errors[] = "Password must be at least 8 characters long.";
                        } else {
                            // Check if the new password is the same as the old password
                            $sql_check = "SELECT password FROM user_account_tbl WHERE email = ?";
                            $stmt_check = mysqli_stmt_init($conn);
                    
                            if (mysqli_stmt_prepare($stmt_check, $sql_check)) {
                                mysqli_stmt_bind_param($stmt_check, "s", $email);
                                mysqli_stmt_execute($stmt_check);
                                mysqli_stmt_bind_result($stmt_check, $hashed_old_password);
                                mysqli_stmt_fetch($stmt_check);
                    
                                if (password_verify($password, $hashed_old_password)) {
                                    echo "<div class='alert alert-danger'>Password cannot be the same as the Old Password</div>";
                                }
                    
                                mysqli_stmt_close($stmt_check);
                            }

                            if (empty($errors)) {
                            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                            $sql_update = "UPDATE user_account_tbl SET password = ? WHERE email = ?";
                            $stmt_update = mysqli_stmt_init($conn);
                        
                            if (mysqli_stmt_prepare($stmt_update, $sql_update)) {
                                // Bind parameters (hashed password and email)
                                mysqli_stmt_bind_param($stmt_update, "ss", $hashed_password, $email);
        

                                    if (mysqli_stmt_execute($stmt_update)) {
                                        echo "<div class='alert alert-success'>Password updated successfully.</div>";
                                        // Optionally redirect to login or another page
                                        echo "<script type='text/javascript'>window.location.href = 'login.php';</script>";
                                        exit();
                                    } else {
                                        $errors[] = "Failed to update the password. Please try again.";
                                    }
                                } else {
                                    $errors[] = "Database error. Please try again later.";
                                }
                            }
                        }
                    }
            ?>

                        <div class="mb-3 input-group">
                            <span class="input-group-text custom-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text custom-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder=" Confirm Password" required>
                        </div>
                        <button type="submit" name="submit" class="btn">Change Password</button>
                        <a href="login.php" class="forgot-password">Go Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
<script src="js/header.js"></script>
</html>
