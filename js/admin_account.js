
    document.getElementById("addUserForm").addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent form from submitting the traditional way
    
        const formData = new FormData(this);
    
        fetch("add_user.php", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new user to the table dynamically
                    const tableBody = document.querySelector("#useraccount_table tbody");
                    const newRow = document.createElement("tr");
    
                    newRow.setAttribute("data-user-id", data.UserID); // Use ID from server response
                    newRow.innerHTML = `
                        <td>${formData.get("firstname")} ${formData.get("lastname")}</td>
                        <td>${formData.get("email")}</td>
                        <td>${formData.get("role")}</td>
                        <td>${formData.get("birthdate")}</td>
                        <td>${formData.get("contact_number")}</td>
                        <td>Active</td>
                        <td class='text-center'>
                            <button class='btn btn-success btn-sm' title='Edit' style='margin-right: 8px;'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <button class='btn btn-danger btn-sm delete-btn' data-bs-toggle='modal' data-bs-target='#deleteModal' title='Delete' data-user-id='${data.UserID}'>
                                <i class='fas fa-trash'></i>
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(newRow);
    
                    // Close the modal
                    const addUserModal = bootstrap.Modal.getInstance(document.getElementById("addUserModal"));
                    addUserModal.hide();
    
                    // Reattach delete button handlers
                    attachDeleteHandlers();

                } else {
                    alert(data.message || "Error adding user.");
                }
            })
            .catch(error => console.error("Error:", error));
    });

    document.getElementById("email").addEventListener("blur", function() {
        const email = document.getElementById("email").value;
        const feedback = document.getElementById("emailFeedback");
        
        if (email) {
            // Send an AJAX request to check if email exists
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "check_email.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === "error") {
                        feedback.textContent = response.message;
                        feedback.style.display = "block";
                    } else {
                        feedback.style.display = "none";
                    }
                }
            };
            xhr.send("email=" + encodeURIComponent(email));
        }
    });

    function checkAge() {
        const birthdate = document.getElementById("birthdate").value;
        const feedback = document.getElementById("birthdateFeedback");
    
        if (!birthdate) return; // Skip if the field is empty
    
        const currentDate = new Date();
        const birthDate = new Date(birthdate);
        const age = currentDate.getFullYear() - birthDate.getFullYear();
        const monthDifference = currentDate.getMonth() - birthDate.getMonth();
        const isUnderage = age < 15 || (age === 15 && monthDifference < 0);
    
        if (isUnderage) {
            feedback.textContent = "You must be at least 15 years old to register.";
            feedback.style.display = "block";
        } else {
            feedback.textContent = "";
            feedback.style.display = "none";
        }
    }

    const today = new Date().toISOString().split('T')[0];

  // Set the max attribute to today's date
  document.getElementById('birthdate').setAttribute('max', today);
  document.getElementById('editBirth').setAttribute('max', today);


  let userIdToDelete = null;

  // Handle delete button click
  document.querySelectorAll('.btn-danger').forEach(button => {
      button.addEventListener('click', function () {
          const userRow = this.closest("tr");
          userIdToDelete = this.getAttribute('data-user-id'); // Store the user ID from the button's data-user-id attribute
          const deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
          deleteModal.show();
      });
  });
  
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-success').forEach(button => {
      button.addEventListener('click', function () {
        const userId = this.closest('tr').dataset.userId;
  
        // Fetch data from the server
        fetch(`edit_user.php?UserID=${userId}`)
            .then(response => {
                console.log('Response status:', response.status); // Debug status
                return response.json();
            })
            .then(data => {
                console.log('Fetched data:', data); // Debug fetched data
                if (data.error) {
                    alert('Error fetching user data: ' + data.error);
                    return;
                }
    
                // Populate modal fields
                document.getElementById('editUserID').value = data.UserID;
                document.getElementById('editFirstName').value = rowData.firstname;
                document.getElementById('editLastName').value = data.lastname;
                document.getElementById('editEmail').value = data.email;
                document.getElementById('editRole').value = data.role;
                document.getElementById('editBirthDate').value = data.birthdate;
                document.getElementById('editContactNumber').value = data.contact_number;
        
                // Show the modal
                const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                editModal.show();
            })
            .catch(error => console.error('Error fetching user data:', error));
        });
        });
    });

  document.getElementById('editUserForm').addEventListener('submit', function (e) {
    e.preventDefault();
  
    const formData = new FormData(this);
  
    fetch('edit_user.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('User updated successfully!');
          location.reload(); // Reload page to see changes
        } else {
          alert('Error updating user: ' + data.message);
        }
      })
      .catch(error => console.error('Error updating user:', error));
  });
  
  