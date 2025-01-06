<?php
session_start();

require "database.php";

$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
$lastname = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

$sql = "SELECT * FROM transaction_tbl";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin System</title>
    <link id="favicon" rel="icon" type="image/png" href="images/logo.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
    <link href="https://fonts.cdnfonts.com/css/gilroy-bold" rel="stylesheet">
    <link rel="stylesheet" href="css/cashier_page.css">
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="images/logo.png" alt="Logo">
        <span>Cashier System</span>
    </div>
    <a href="cashier_page.php" class="sidebar-item" id="dashboardItem">
        <i class="fas fa-credit-card"></i>
        <span>POS</span>
    </a>
    <a href="cashier_transac.php" class="sidebar-item active" id="transactionsItem">
        <i class="fas fa-exchange-alt"></i>
        <span>Transactions</span>
    </a>

    <div class="user-profile">
        <div class="profile-img-container">
        <img src="path_to_user_image.jpg" alt="User Profile" class="profile-img" onerror="this.onerror=null; 
        this.src='default-profile.png';">
        <div class="profile-img-no-image">
            <span class="profile-initials"><?php echo htmlspecialchars($lastname)?></span> <!-- You can dynamically insert initials here -->
        </div>
        </div>
        <div class="user-info">
            <p class="user-name"><?php echo htmlspecialchars($firstname) . " " . htmlspecialchars($lastname); ?></p>
            <p class="user-role"><?php echo htmlspecialchars($role)?></p>
        </div>
        <button class="btn btn-primary open-profile-btn" onclick="openProfile()">Open Profile</button>
    </div>

</div>

<button class="toggle-btn" id="toggleBtn">
    <i class="fas fa-chevron-left"></i>
</button>

<div class="content" id="content">
<div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; margin-top: 30px;">
    <div style="display: flex; align-items: center;">
        <h2 style="font-family: 'Gilroy-Bold', sans-serif; margin-left: 15px;">Transactions</h2>
    </div>
</div>
<div style="display: flex; align-items: center; margin-left: 15px;">
    <form style="margin: 0; display: flex; align-items: center; position: relative;">
        <i class="fas fa-search" 
            style="
                position: absolute; 
                left: 10px; 
                top: 50%; 
                transform: translateY(-50%); 
                color: #604be8;
            ">
        </i>
        <input 
            type="text" 
            placeholder="Search transaction, status, etc." 
            style="
                padding: 10px 10px 10px 40px; 
                border: 1px solid #fff; 
                border-radius: 10px; 
                outline: none; 
                width: 350px;
                font-family: 'Gilroy', sans-serif;
            "
        >
    </form>
</div>
<div style="margin: 15px;">
    <table class="table table-striped" style="background-color: white;">
        <thead style="background-color: #06d6a0; color: white;">
            <tr>
                <th scope="col">Transaction ID</th>
                <th scope="col">Product Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Discount</th>
                <th scope="col">Total</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $status = $UserID ? "Active" : "Inactive";
                    
                    echo "<tr>";
                    echo "<td>" . $row["TransactionID"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td>" . $row["discount"] . "</td>";
                    echo "<td>" . $row["total"] . "</td>";
                    echo "<td>" . htmlspecialchars($status) . "</td>";
                    echo "<td class='text-center'>
                        <button class='btn btn-success btn-sm' title='Edit' style='margin-right: 8px;'>
                            <i class='fas fa-edit'></i>
                        </button>
                        <button class='btn btn-danger btn-sm' title='Delete'>
                            <i class='fas fa-trash'></i>
                        </button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No items found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleBtn = document.getElementById('toggleBtn');
    const toggleIcon = toggleBtn.querySelector('i');
    const userProfile = document.querySelector('.user-profile');
    const accountsItem = document.getElementById('accountsItem');
    const transactionsItem = document.getElementById('transactionsItem');

    // Sidebar toggle
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('collapsed');
        toggleBtn.classList.toggle('collapsed');

        // Change icon based on state
        toggleIcon.classList.toggle('fa-chevron-right', sidebar.classList.contains('collapsed'));
        toggleIcon.classList.toggle('fa-chevron-left', !sidebar.classList.contains('collapsed'));
    });


    // Toggle active state for dashboard
    transactionsItem.addEventListener('click', () => {
        document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));
        transactionsItem.classList.add('active');
    });

    function openProfile() {
    // You can redirect to a profile page or open a modal, etc.
    window.location.href = 'profile_page.html'; // Example redirection to profile page
    }

</script>
<script src="js/header.js"></script>
<script src="js/cashier_page.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
