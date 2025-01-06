<?php
session_start();

$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
$lastname = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

require "database.php";


$query = "
    SELECT 
        archives_tbl.ProductID, 
        inventory_tbl.product_name, 
        inventory_tbl.product_category, 
        inventory_tbl.quantity, 
        inventory_tbl.unit_price,
        inventory_tbl.Status,
        archives_tbl.Archive_date 
    FROM 
        archives_tbl
    INNER JOIN 
        inventory_tbl 
    ON 
        archives_tbl.ProductID = inventory_tbl.ProductID
";

$result = $conn->query($query);
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
    <style>
        body {
            font-family: 'Gilroy-Bold', sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: white;
            color: #343a40;
            position: fixed;
            transition: all 0.3s ease;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .sidebar-header {
            font-family: 'Gilroy-Bold', sans-serif;
            font-size: 1.5rem;
            color: #604be8;
            text-align: center;
            padding: 20px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 60px;
        }

        .sidebar .sidebar-header img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .sidebar.collapsed .sidebar-header span {
            display: none;
        }

        .sidebar .sidebar-item {
            font-family: 'Gilroy-Bold', sans-serif;
            padding: 10px 15px;
            margin: 5px 10px;
            display: flex;
            align-items: center;
            white-space: nowrap;
            transition: all 0.3s ease;
            color: #8E91AF;
            text-decoration: none;
            border-radius: 5px;
        }

        .sidebar .sidebar-item.active {
            background-color: #604be8;
            color: white;
        }

        .sidebar .sidebar-item:hover {
            background-color: #f0f0f5;
            color: #604be8;
        }

        .sidebar .sidebar-item i {
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .sidebar .sidebar-item .dropdown-icon {
            margin-left: auto;
            font-size: 1rem;
            position: absolute;
            right: 15px;
        }

        .sidebar.collapsed .sidebar-item span {
            display: none;
        }

        .sidebar.collapsed .sidebar-item i {
            margin-right: 0;
        }

        .dropdown-items {
            display: none;
            flex-direction: column;
            padding-left: 20px;
        }

        .dropdown-open {
            display: block;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background-color: #EFF3FE;
            transition: margin-left 0.3s ease;
        }

        .content.collapsed {
            margin-left: 80px;
        }

                /* User Profile Section at the bottom of the sidebar */
        .user-profile {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: calc(100% - 40px);
            text-align: center;
            color: #343a40;
            transition: all 0.3s ease;
        }

        .sidebar .dropdown-open + .user-profile {
            bottom: 80px; /* Adjust as per the required distance */
        }

        .profile-img-container {
            width: 50px;
            height: 50px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 10px;
            position: relative;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .user-profile {
            left: 10px; /* Adjust for collapsed sidebar */
            width: calc(100% - 20px);
        }

        .user-name, .user-role {
            font-size: 0.9rem; /* Reduced text size */
        }

        .sidebar.collapsed .user-name,
        .sidebar.collapsed .user-role {
            font-size: 0.8rem; /* Further reduced size when collapsed */
        }

        .open-profile-btn {
            font-size: 0.8rem; /* Smaller button font size */
            padding: 5px 10px;
        }

        .sidebar.collapsed .open-profile-btn {
            display: none; /* Hide button when sidebar is collapsed */
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-img-no-image {
            width: 100%;
            height: 100%;
            background-color: #d1d1d1; /* Light grey background */
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 1.5rem;
            color: white;
        }

        .sidebar.collapsed .profile-img-container {
            width: 40px; /* Smaller size when sidebar is collapsed */
            height: 40px;
        }

        .dropdown-open ~ .user-profile {
            bottom: 150px; /* Adjusted to push user profile below dropdown */
        }

        .profile-initials {
            font-weight: bold;
        }

        .user-profile .profile-img-no-image {
            display: none; /* Hide the fallback circle by default */
        }

        /* Show the fallback circle if no profile image is available */
        .profile-img[src='default-profile.png'] {
            display: none; /* Hide the image */
        }

        .profile-img[src='default-profile.png'] + .profile-img-no-image {
            display: block; /* Show the fallback circle */
        }

        .user-name {
            font-weight: bold;
            margin: 0;
            font-size: 1rem;
        }

        .user-role {
            font-size: 0.9rem;
            color: #8e91af;
            margin: 5px 0;
        }

        .open-profile-btn {
            width: 100%;
            padding: 8px 12px;
            margin-top: 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            background-color: #EEF3FC;
            color: #604be8;
            border: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .open-profile-btn:hover {
            background-color: #d6e0ff;
            color: #4b3ac0;
        }


        .toggle-btn {
            position: absolute;
            top: 20px;
            left: 250px;
            background-color: white;
            color: #343a40;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            transition: left 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        }

        .toggle-btn.collapsed {
            left: 80px;
        }

        .toggle-btn:hover {
            background-color: #604be8;
            color: white;
        }

        .toggle-btn i {
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .sidebar .sidebar-item span {
                display: none;
            }

            /* Make the search bar smaller and stack items vertically */
            .content form {
                width: 100%; /* Take full width */
            }

            .content form input {
                width: 100%; /* Full width for input field */
                padding: 10px 40px; /* Adjust padding */
                margin-bottom: 10px; /* Add spacing between elements */
            }

            .content {
                display: flex;
                flex-direction: column; /* Stack items vertically */
                align-items: flex-start; /* Align items to start */
                padding: 10px; /* Adjust padding for smaller screens */
            }

            .content h2 {
                font-size: 1.5rem; /* Reduce font size for smaller screens */
                margin-top: 10px;
                margin-bottom: 10px;
            }

            .content > div {
                display: flex;
                flex-direction: column; /* Stack notification and search bar */
                width: 100%;
                align-items: flex-start; /* Align items */
            }

            /* Notification icon adjustments */
            .content i.fas.fa-bell {
                margin-right: 0; /* Remove right margin */
                margin-bottom: 10px; /* Add spacing between elements */
                font-size: 1.2rem; /* Reduce size */
            }

            .toggle-btn {
                left: 80px; /* Adjust button position */
            }
        }

            input::placeholder {
                font-family: 'Gilroy-Bold', sans-serif;
            }

    </style>
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="images/logo.png" alt="Logo">
        <span>Admin System</span>
    </div>
    <a href="admin_page.php" class="sidebar-item" id="dashboardItem">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
    <div class="sidebar-item dropdown" id="inventoryDropdown">
        <i class="fas fa-box"></i>
        <span>Inventory</span>
        <i class="fas fa-chevron-down dropdown-icon"></i>
    </div>
    <div class="dropdown-items" id="dropdownItems">
        <a href="admin_inventory.php" class="sidebar-item" id="viewInventory">
            <i class="fas fa-eye"></i>
            <span>View Inventory</span>
        </a>
        <a href="admin_pos.php" class="sidebar-item">
            <i class="fas fa-credit-card"></i>
            <span>POS</span>
        </a>
        <a href="admin_discount.php" class="sidebar-item">
            <i class="fas fa-percent"></i>
            <span>Discount</span>
        </a>
        <a href="admin_archive.php" class="sidebar-item active" id="viewArchive">
            <i class="fas fa-archive"></i>
            <span>Archive</span>
        </a>
    </div>
    <a href="admin_account.php" class="sidebar-item" id="accountsItem">
        <i class="fas fa-user-circle"></i>
        <span>Accounts</span>
    </a>
    <a href="admin_transac.php" class="sidebar-item" id="transactionsItem">
        <i class="fas fa-exchange-alt"></i>
        <span>Transactions</span>
    </a>
    <a href="reports.html" class="sidebar-item" id="reportsItem">
        <i class="fas fa-chart-line"></i>
        <span>Reports</span>
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
        <h2 style="font-family: 'Gilroy-Bold', sans-serif; margin-left: 15px;">Archive</h2>
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
            placeholder="Search product name, category, etc." 
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
                <th scope="col">Product Name</th>
                <th scope="col">Category</th>
                <th scope="col">Stock</th>
                <th scope="col">Price</th>
                <th scope="col">Status</th>
                <th scope="col">Archive Date</th>
                <th scope="col" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $rowClass = ($row["quantity"] < 100) ? 'low-quantity' : '';
                    
                    echo "<tr class='$rowClass'>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["product_category"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>" . $row["unit_price"] . "</td>";
                    echo "<td>" . $row["Status"] . "</td>";
                    echo "<td>" . htmlspecialchars($row['Archive_date']) . "</td>";
                    echo "<td class='text-center'>
                        <button class='btn btn-danger btn-sm' title='Return'
                        data-bs-toggle='modal' 
                        data-bs-target='#returnModal' 
                        data-id='" . $row["ProductID"] . "' 
                        data-name='" . $row["product_name"] . "' 
                        data-category='" . $row["product_category"] . "'>
                            <i class='fas fa-undo-alt'></i>
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

    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnModalLabel">Return Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="returnModalMessage"></p>
                <button id="confirmReturn" class="btn btn-primary">Confirm Return</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div>
</div>

</div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleBtn = document.getElementById('toggleBtn');
    const toggleIcon = toggleBtn.querySelector('i');
    const inventoryDropdown = document.getElementById('inventoryDropdown');
    const dropdownItems = document.getElementById('dropdownItems');
    const dropdownIcon = inventoryDropdown.querySelector('.dropdown-icon');
    const userProfile = document.querySelector('.user-profile');
    const accountsItem = document.getElementById('accountsItem');
    const viewInventory = document.getElementById('viewInventory');
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

    // Inventory dropdown toggle
    inventoryDropdown.addEventListener('click', () => {
        dropdownItems.classList.toggle('dropdown-open');
        dropdownIcon.classList.toggle('fa-chevron-up');
        dropdownIcon.classList.toggle('fa-chevron-down');
        // Adjust "Accounts" distance
        if (dropdownItems.classList.contains('dropdown-open')) {
        accountsItem.style.marginTop = '0'; // Remove extra space when dropdown is open
    } else {
        accountsItem.style.marginTop = '10px'; // Add some space when dropdown is closed
    }

        userProfile.style.bottom = dropdownItems.classList.contains('dropdown-open') ? '-150px' : '20px';
    });

    viewArchive.addEventListener('click', () => {
        document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));
        viewArchive.classList.add('active');
    });

    document.addEventListener('DOMContentLoaded', () => {
    const inventoryDropdown = document.getElementById('inventoryDropdown');
    const dropdownItems = document.getElementById('dropdownItems');
    const dropdownIcon = inventoryDropdown.querySelector('.dropdown-icon');

    // Check if any child item is active on page load
    const isChildActive = Array.from(dropdownItems.querySelectorAll('.sidebar-item')).some(item =>
        item.classList.contains('active')
    );

    if (isChildActive) {
        dropdownItems.classList.add('dropdown-open');
        dropdownIcon.classList.remove('fa-chevron-down');
        dropdownIcon.classList.add('fa-chevron-up');
    }

    // Sidebar dropdown toggle
    inventoryDropdown.addEventListener('click', () => {
        if (!isChildActive) {
            dropdownItems.classList.toggle('dropdown-open');
            dropdownIcon.classList.toggle('fa-chevron-up');
            dropdownIcon.classList.toggle('fa-chevron-down');
        }
    });
});


    function openProfile() {
    // You can redirect to a profile page or open a modal, etc.
    window.location.href = 'profile_page.html'; // Example redirection to profile page
    }

</script>

<script>
// Listen for the click event on any return button
document.addEventListener('DOMContentLoaded', function() {
    let productId, productName, productCategory;

    // Listen for the click event on any return button
    document.querySelectorAll('.btn-danger').forEach(button => {
        button.addEventListener('click', function() {
            productId = this.getAttribute('data-id');
            productName = this.getAttribute('data-name');
            productCategory = this.getAttribute('data-category');

            // Display the modal with a confirmation message
            document.getElementById('returnModalMessage').innerText = `Are you sure you want to return the product "${productName}" from the archive?`;

            // Show the modal
            const returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
            returnModal.show();
        });
    });

    // Handle the confirm return action
    document.getElementById('confirmReturn').addEventListener('click', function() {
        fetch('return_product.php', {
            method: 'POST',
            body: JSON.stringify({ ProductID: productId }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close the modal
                const returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
                returnModal.hide();

                location.reload(); // Or update the table dynamically without a page reload
            } else {
                alert('Failed to return the product. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});


</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
<script src="js/header.js"></script>
</html>
