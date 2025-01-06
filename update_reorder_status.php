<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reorderId = $_POST['ReorderID'] ?? null;
    $action = $_POST['action'] ?? null; // 'complete' or 'cancel'

    if ($reorderId && $action) {
        // Start a transaction to ensure atomicity
        $conn->begin_transaction();

        try {
            // Retrieve the ProductID and quantity_needed for the given reorder_id
            $sql = "SELECT ProductID, quantity_needed FROM reorder_tbl WHERE ReorderID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $reorderId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $productId = $row['ProductID'];
                $quantityNeeded = $row['quantity_needed'];

                if ($action == 'complete') {
                    // Update status to 'Completed'
                    $sqlUpdateStatus = "UPDATE reorder_tbl SET status = 'Completed' WHERE ReorderID = ?";
                } elseif ($action == 'cancel') {
                    // Update status to 'Cancelled'
                    $sqlUpdateStatus = "UPDATE reorder_tbl SET status = 'Cancelled' WHERE ReorderID = ?";
                } else {
                    throw new Exception("Invalid action.");
                }

                $stmtUpdateStatus = $conn->prepare($sqlUpdateStatus);
                $stmtUpdateStatus->bind_param("i", $reorderId);
                $stmtUpdateStatus->execute();

                // Only update inventory if the order was completed (do not update inventory for cancelled orders)
                if ($action == 'complete') {
                    $sqlUpdateInventory = "UPDATE inventory_tbl SET quantity = quantity + ? WHERE ProductID = ?";
                    $stmtUpdateInventory = $conn->prepare($sqlUpdateInventory);
                    $stmtUpdateInventory->bind_param("ii", $quantityNeeded, $productId);
                    $stmtUpdateInventory->execute();
                }

                // Commit the transaction after both updates
                $conn->commit();
                echo json_encode(["success" => true]);
            } else {
                throw new Exception("Reorder ID not found.");
            }
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid Reorder ID or action."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>
