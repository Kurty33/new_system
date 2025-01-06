<?php
    session_start();
?>

<?php
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
                <div class="login-title">Enter Email Account</div>
                <div class="login-form">
                    <!-- Added login title -->
                    
                    <form action="confirm.php" method="POST">
                    <?php
                    
                    function generateVerificationCode() {
                        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // Generates a 6-digit number, padded with leading zeros if necessary
                    }

                    $errors = array(); // Array to hold login errors

                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) { // Modified if condition
                        require_once "database.php"; // Assuming database connection script is in a separate file

                        $email = trim($_POST['email']);
                        $errors = [];

                        if (empty($email)) {
                            array_push($errors, "Email address is required.");
                        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            array_push($errors, "Invalid email format.");
                        }



                        if (empty($errors)) {
                        $sql = "SELECT * FROM user_account_tbl WHERE email = ?";
                        $stmt = mysqli_stmt_init($conn);
                        
                        if (mysqli_stmt_prepare($stmt, $sql)) {
                            mysqli_stmt_bind_param($stmt, "s", $email);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
        
                            if (mysqli_num_rows($result) == 1) {
                                $row = mysqli_fetch_assoc($result);

                                $verificationCode = generateVerificationCode();

                                $_SESSION['verification_code'] = $verificationCode;
                                $_SESSION['email'] = $email;
                                    
                                    $mail = new PHPMailer(true);
                                    try {
                                        // Server settings
                                        $mail->SMTPDebug = 2;                      
                                        $mail->isSMTP();                          
                                        $mail->Host       = 'smtp.gmail.com';  
                                        $mail->SMTPAuth   = true;                 
                                        $mail->Username   = 'oteyza.kurtrusselle@gmail.com';
                                        $mail->Password   = 'glqz ssnn uocq pvtp';      
                                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                        $mail->Port       = 587;

                                        // Recipients
                                        $mail->setFrom('oteyza.kurtrusselle@gmail.com', 'CS Flores Inventory System');
                                        $mail->addAddress($email);

                                        // Content
                                        $mail->isHTML(true);
                                        $mail->Subject = 'Verification Code';
                                        $mail->Body    = 'Hello, ' . htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']) . '.<br>Your verification code to change password is: <b>' . $verificationCode . '</b>';

                                        $mail->send();
                                        echo "<script type='text/javascript'>window.location.href = 'verify_pass.php';</script>";
                                        exit();
                                    } catch (Exception $e) {
                                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                    }
                                    } else { 
                                        echo "<div class='alert alert-danger'>No User Found with this Email</div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-danger'>Invalid Email or Password</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Failed to send verification email</div>";
                            }
                        }
                    ?>

                        <div class="mb-3 input-group">
                            <span class="input-group-text custom-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        
                        <button type="submit" name="submit" class="btn">Confirm</button>
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
