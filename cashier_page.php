<?php
session_start();

require "database.php";

$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
$lastname = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

$sql = "SELECT * FROM inventory_tbl";
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
    <a href="cashier_page.php" class="sidebar-item active" id="dashboardItem">
        <i class="fas fa-credit-card"></i>
        <span>POS</span>
    </a>
    <a href="cashier_transac.php" class="sidebar-item" id="transactionsItem">
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

<div class="content" id="content" style="position: relative;">
    <div style="display: flex; align-items: center; justify-content: flex-start; padding-left: 15px; margin-top: 30px;">
        <h2 style="font-family: 'Gilroy-Bold', sans-serif;">Point of Sale</h2>
    </div>

    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; margin-top: 30px;">
        <form style="margin: 0; display: flex; align-items: center; position: relative; width: 350px;">
            <i class="fas fa-search" 
                style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #604be8;">
            </i>
            <input 
                type="text" 
                placeholder="Search product, category, etc." 
                id="searchBar"
                style="padding: 10px 10px 10px 40px; border: 1px solid #fff; border-radius: 10px; outline: none; width: 400px; font-family: 'Gilroy', sans-serif;">
        </form>
    </div>

    <!-- Main content container with flexbox layout -->
    <div style="display: flex; margin-top: 40px; padding-left: 15px; justify-content: space-between;">
        <!-- Products Container -->
        <div class="dashboard-containers" style="display: flex; flex-wrap: wrap; width: 75%; margin-right: 30px; max-height: 500px; overflow-y: auto;">
            <?php
            if ($result->num_rows > 0) {
                $count = 0; // Counter to ensure only 3 items per row
                while($row = $result->fetch_assoc()) {
                    $imageData = base64_encode($row['image']);
                    $productId = $row['ProductID'];
                    $productName = htmlspecialchars($row['product_name']);
                    $productPrice = number_format($row['unit_price'], 2);
                    $availableQuantity = $row['quantity'];
                    $count++;
                    // Dynamically generate each POS container
                    echo "
                    <div style='width: 29%; height: 250px; background-color: #fff; margin-right: 30px; margin-bottom: 30px; border-radius: 20px; cursor: pointer;' 
                        onclick='openProductModal($productId, \"$imageData\", \"$productName\", \"$productPrice\", $availableQuantity)' data-bs-toggle='modal' data-bs-target='#productModal'>
                        <img src='data:image/jpeg;base64," . $imageData . "' alt='" . $row['product_name'] . "' style='width: 80%; height: 150px; object-fit: cover; border-radius: 15px;' />
                        <div style='padding: 10px; text-align: center;'>
                            <h5>" . $row['product_name'] . "</h5>
                            <p>₱" . $productPrice . "</p>
                        </div>
                    </div>
                    ";
                    // If 3 items have been added, break the row and start a new one
                    if ($count % 3 == 0) {
                        echo "<div style='flex-basis: 100%;'></div>"; // Creates a line break
                    }
                }
            } else {
                echo "<p>No products available.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Product Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Product Image -->
        <div style="display: flex; justify-content: center;">
          <img id="modalProductImage" src="" alt="Product Image" style="width: 40%; height: auto; object-fit: cover; border-radius: 15px; margin-bottom: 15px;">
        </div>
        <!-- Product Name -->
        <h5 id="modalProductName"></h5>
        <!-- Quantity Selector -->
        <div class="mb-3">
          <label for="productQuantity" class="form-label">Quantity</label>
          <input type="number" class="form-control" id="productQuantity" value="1" min="1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirmPurchaseBtn">Confirm</button>
      </div>
    </div>
  </div>
</div>


        <!-- Checkout Container on the right -->
        <div style="position: fixed; top: 0; right: 0; width: 23%; background-color: #f7f7f7; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: 100%; display: flex; flex-direction: column; justify-content: space-between; z-index: 10;">
    <!-- Checkout Header -->
    <h4 style="text-align: center; margin-bottom: 20px;">Checkout</h4>

    <!-- Table for Cart Items -->
    <div style="overflow-y: auto; max-height: 200px; margin-bottom: 20px;">
        <table id="checkoutTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 5px; text-align: left;">No.</th>
                    <th style="padding: 5px; text-align: left;">Product</th>
                    <th style="padding: 5px; text-align: left;">Quantity</th>
                    <th style="padding: 5px; text-align: left;">Price</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic Rows will be added here -->
            </tbody>
        </table>
    </div>

    <!-- Total & Discount Section -->
    <div style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span>Amount:</span>
            <span><span id="checkoutTotalAmount">0.00</span></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span>Discount:</span>
            <span>₱0.00</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 25px;">
            <span>Total:</span>
            <span>₱<span id="checkoutTotalAmount">0.00</span></span>
        </div>
    </div>

    <!-- Checkout Button -->
    <button class="btn btn-primary" style="width: 100%; padding: 10px; margin-top: auto; background-color: #604be8;">Proceed to Checkout</button>
</div>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleBtn = document.getElementById('toggleBtn');
    const toggleIcon = toggleBtn.querySelector('i');
    const userProfile = document.querySelector('.user-profile');
    const accountsItem = document.getElementById('accountsItem');
    const dashboardItem = document.getElementById('dashboardItem');

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
    dashboardItem.addEventListener('click', () => {
        document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));
        dashboardItem.classList.add('active');
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
