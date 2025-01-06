<?php
session_start();

$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
$lastname = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

require "database.php";


$sql = "
    SELECT r.ReorderID, r.ProductID, r.quantity_needed, r.delivery_date, r.reorder_date, r.status,
           i.product_name, i.product_category
    FROM reorder_tbl r
    JOIN inventory_tbl i ON r.ProductID = i.ProductID
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Staff</title>
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

        .low-stock-row {
            background-color: #ffcccc; /* Light red background */
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
        <span>Inventory Staff</span>
    </div>
    <a href="stock_page.php" class="sidebar-item" id="dashboardItem">
        <i class="fas fa-box"></i>
        <span>Inventory</span>
    </a>
    <a href="stock_reorder.php" class="sidebar-item active" id="ReorderItem">
        <i class="fas fa-sync-alt"></i>
        <span>Reorder</span>
    </a>

    <div class="user-profile">
        <div class="profile-img-container">
        <?php
        // Fetch user profile image as Base64 from the database
        $profileImage = $row['profile']; // Replace with your query result column
        $imageBase64 = !empty($profileImage) ? base64_encode($profileImage) : null;
        ?>
        <img 
            src="<?php echo $imageBase64 ? 'data:image/jpeg;base64,' . $imageBase64 : 'default-profile.png'; ?>" 
            alt="User Profile" 
            class="profile-img" 
            onerror="this.onerror=null; this.src='default-profile.png';"
        >
        <?php if (!$imageBase64): ?>
        <div class="profile-img-no-image">
            <span class="profile-initials"><?php echo htmlspecialchars(substr($lastname, 0, 1)); ?></span>
        </div>
        <?php endif; ?>
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
        <h2 style="font-family: 'Gilroy-Bold', sans-serif; margin-left: 15px;">Reorder</h2>
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
            id = "searchInvent" 
            style="
                padding: 10px 10px 10px 40px; 
                border: 1px solid #fff; 
                border-radius: 10px; 
                outline: none; 
                width: 350px;
                font-family: 'Gilroy', sans-serif;
            "
            onkeyup="searchInventory()"
        >
    </form>
</div>
<div style="margin: 15px;">
    <table class="table table-striped" style="background-color: white;" id="inventory_table">
        <thead style="background-color: #06d6a0; color: white;">
            <tr>
                <th scope="col">Product Name</th>
                <th scope="col">Category</th>
                <th scope="col">Quantity</th>
                <th scope="col">Deliver Days</th>
                <th scope="col">Reorder Date</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    
                    echo "<tr>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["product_category"] . "</td>";
                    echo "<td>" . $row["quantity_needed"] . "</td>";
                    echo "<td>" . $row["delivery_date"] . "</td>";
                    echo "<td>" . $row["reorder_date"] . "</td>";
                    echo "<td class='status-cell'>" . $row["status"] . "</td>";

                    if ($row["status"] != "Completed" && $row["status"] != "Cancelled") {
                        echo "<td class='text-center'>
                                <button class='btn btn-success btn-sm mark-completed-btn' 
                                    data-id='" . $row['ReorderID'] . "' 
                                    title='Complete Order' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#confirmCompletedModal'>
                                    <i class='fas fa-check-circle'></i>
                                </button>
                                <button class='btn btn-danger btn-sm cancel-order-btn' 
                                    data-id='" . $row['ReorderID'] . "' 
                                    title='Cancel Order'
                                    data-bs-toggle='modal' 
                                    data-bs-target='#confirmCancelModal'>
                                    <i class='fas fa-times-circle'></i>
                                </button>
                            </td>";

                } else {
                    echo "<td class='text-center'></td>"; // No button for completed orders
                }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No reorder found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="modal fade" id="confirmCompletedModal" tabindex="-1" aria-labelledby="confirmCompletedLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmCompletedLabel">Confirm Completion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to mark this reorder as completed?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="confirmCompleteBtn">Mark Complete</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-labelledby="confirmCancelModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmCancelModalLabel">Cancel Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to cancel this order?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="confirmCancelBtn">Cancel Order</button>
      </div>
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

    if (dropdownItems.classList.contains('dropdown-open')) {
        userProfile.style.bottom = '-150px'; // Push user profile below dropdown
        } else {
            userProfile.style.bottom = '20px'; // Reset position
        }
    });

    // Toggle active state for dashboard
    ReorderItem.addEventListener('click', () => {
        document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));
        ReorderItem.classList.add('active');
    });

    function openProfile() {
    // You can redirect to a profile page or open a modal, etc.
    window.location.href = 'profile_page.html'; // Example redirection to profile page
    }

</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let reorderIdToUpdate = null;

    // Capture Reorder ID when button is clicked for completing the order
    document.querySelectorAll(".mark-completed-btn").forEach(button => {
        button.addEventListener("click", function () {
            reorderIdToUpdate = this.getAttribute("data-id");
        });
    });

    // Capture Reorder ID when cancel button is clicked
    document.querySelectorAll(".cancel-order-btn").forEach(button => {
        button.addEventListener("click", function () {
            reorderIdToUpdate = this.getAttribute("data-id");

            // Show the cancel confirmation modal
            const cancelModal = new bootstrap.Modal(document.getElementById("confirmCancelModal"));
            cancelModal.show();
        });
    });

    // Handle cancel order confirmation
    document.getElementById("confirmCancelBtn").addEventListener("click", function () {
        if (!reorderIdToUpdate) return;

        fetch("update_reorder_status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({ ReorderID: reorderIdToUpdate, action: 'cancel' }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update status visually in the table
                    const row = document.querySelector(`button[data-id='${reorderIdToUpdate}']`).closest("tr");
                    row.querySelector("td:nth-child(6)").textContent = "Cancelled"; // Update status column

                    // Hide the cancel button after cancelling the order
                    row.querySelector(".cancel-order-btn").style.display = "none";
                    row.querySelector(".mark-completed-btn").style.display = "none"; // Hide the "Complete" button as well

                    // Close the modal
                    const cancelModal = bootstrap.Modal.getInstance(document.getElementById("confirmCancelModal"));
                    cancelModal.hide();
                } else {
                    alert(data.error || "Failed to cancel order.");
                }
            })
            .catch(error => console.error("Error:", error));
    });

    // Handle modal confirmation for completing the order
    document.getElementById("confirmCompleteBtn").addEventListener("click", function () {
        if (!reorderIdToUpdate) return;

        fetch("update_reorder_status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({ ReorderID: reorderIdToUpdate, action: 'complete' }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update status visually in the table
                    const row = document.querySelector(`button[data-id='${reorderIdToUpdate}']`).closest("tr");
                    row.querySelector("td:nth-child(6)").textContent = "Completed"; // Update status column
                    
                    // Hide the "Complete" button
                    row.querySelector(".mark-completed-btn").style.display = "none";
                    row.querySelector(".cancel-order-btn").style.display = "none";
                    
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById("confirmCompletedModal"));
                    modal.hide();
                } else {
                    alert(data.error || "Failed to update status.");
                }
            })
            .catch(error => console.error("Error:", error));
    });
});


</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
<script src="js/search_bar.js"></script>
<script src="js/header.js"></script>
</html>
