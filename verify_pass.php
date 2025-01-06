<?php
    session_start();
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
    <link rel="stylesheet" href="css/verify.css">
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
                <div class="login-title">Verify Your Account</div>
                <div class="login-form">
                    <!-- Added login title -->
                    
                    <form action="verify_pass.php" method="POST">
                    <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $verificationCode = implode("", $_POST['verification_code']);
                            $session_code = trim($_SESSION["verification_code"]);                            

                            if (strcmp($verificationCode, $session_code) === 0) { 
                                        echo "<script type='text/javascript'>window.location.href = 'change_pass.php';</script>";                   
                                    }
                                    exit();
                                }               
                    ?>
                    
                        <div class="mb-3">
                        <div class="verification-code-container">
                <!-- 6 individual input fields for the verification code -->
                <input type="text" class="verification-input" id="code1" name="verification_code[]" maxlength="1" oninput="moveFocus(this, 'code2')" required>
                <input type="text" class="verification-input" id="code2" name="verification_code[]" maxlength="1" oninput="moveFocus(this, 'code3')" required>
                <input type="text" class="verification-input" id="code3" name="verification_code[]" maxlength="1" oninput="moveFocus(this, 'code4')" required>
                <input type="text" class="verification-input" id="code4" name="verification_code[]" maxlength="1" oninput="moveFocus(this, 'code5')" required>
                <input type="text" class="verification-input" id="code5" name="verification_code[]" maxlength="1" oninput="moveFocus(this, 'code6')" required>
                <input type="text" class="verification-input" id="code6" name="verification_code[]" maxlength="1" required>
                        </div>
                        </div>
                        <button type="submit" name="submit" class="btn">Verify</button>
                        <a href="login.php" class="forgot-password">Go Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
<script src="header.js"></script>
</html>

