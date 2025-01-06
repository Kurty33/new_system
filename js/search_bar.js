function searchInventory() {
    const query = document.getElementById("searchInvent").value;

    // Fetch data from the server using the search query
    const url = query ? `search_inventory.php?q=${encodeURIComponent(query)}` : 'search_inventory.php';

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector("#inventory_table tbody");
            tableBody.innerHTML = ""; // Clear the current table rows

            if (data.length > 0) {
                data.forEach(item => {
                    const row = document.createElement("tr");

                    // Add the 'low-stock-row' class if the quantity is less than or equal to 100
                    if (item.quantity <= 100) {
                        row.classList.add('low-stock-row');
                    }

                    const status =
                        item.quantity > 199 ? "High Stock" :
                        item.quantity >= 101 ? "Mid Stock" :
                        "Low Stock";
                    
                    row.setAttribute("data-product-id", item.ProductID);
                    row.innerHTML = `
                        <td>${item.product_name}</td>
                        <td>${item.product_category}</td>
                        <td>${item.quantity}</td>
                        <td>${Number(item.unit_price).toFixed(2)}</td>
                        <td>${status}</td>
                        <td class="text-center">
                            <button class='btn btn-success btn-sm' title='Edit' style='margin-right: 8px;'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <button class='btn btn-danger btn-sm' title='Archive'>
                                <i class='fas fa-archive'></i>
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                tableBody.innerHTML = `<tr><td colspan="6">No items found</td></tr>`;
            }
        })
        .catch(error => console.error("Error fetching inventory data:", error));
}
