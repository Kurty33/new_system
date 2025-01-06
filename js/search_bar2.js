function searchUser() {
    const query = document.getElementById("searchBar").value;

    // Determine the appropriate URL based on the search query
    const url = query ? `search_account.php?q=${encodeURIComponent(query)}` : 'search_account.php';

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector("#useraccount_table tbody");
            tableBody.innerHTML = ""; // Clear the current table rows

            if (data.length > 0) {
                data.forEach(item => {
                    const row = document.createElement("tr");
                    $status = $UserID ? "Active" : "Inactive";

                    row.setAttribute("data-user-id", item.UserID); // Add data-user-id for each row
                    row.innerHTML = `
                        <td>${item.firstname} ${item.lastname}</td>
                        <td>${item.email}</td>
                        <td>${item.role}</td>
                        <td>${item.birthdate}</td>
                        <td>${item.contact_number}</td> 
                        <td>${item.status}</td>   
                        <td class='text-center'>
                            <button class='btn btn-success btn-sm' title='Edit' style='margin-right: 8px;'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <button class='btn btn-danger btn-sm delete-btn' title='Delete'>
                                <i class='fas fa-trash'></i>
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                // Reattach delete button functionality
                attachDeleteHandlers();
            } else {
                tableBody.innerHTML = `<tr><td colspan="7">No Users Found</td></tr>`;
            }
        })
        .catch(error => console.error("Error:", error));
}

// Attach event listeners for delete buttons
function attachDeleteHandlers() {
    const deleteButtons = document.querySelectorAll(".delete-btn");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const userId = this.closest("tr").dataset.userId;

            if (!userId) {
                alert("Invalid user ID.");
                return;
            }

            // Show the delete confirmation modal
            const deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
            deleteModal.show();

            // Handle delete confirmation
            const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
            confirmDeleteBtn.onclick = () => {
                fetch(`delete_user.php`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ id: userId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`tr[data-user-id='${UserID}']`).remove();
                            alert("User deleted successfully.");
                        } else {
                            alert(data.message || "Error deleting user.");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            };
        });
    });
}

// Attach handlers initially when the page loads
document.addEventListener("DOMContentLoaded", () => {
    attachDeleteHandlers();
});
