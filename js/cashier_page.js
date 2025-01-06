let itemNumber = 1; // Counter for item number in checkout
let totalAmount = 0; // Total price tracker

function openProductModal(productId, imageData, productName, productPrice, availableQuantity) {
    // Set the modal content dynamically
    document.getElementById('modalProductName').innerText = productName;
    document.getElementById('modalProductImage').src = 'data:image/jpeg;base64,' + imageData;
    // Store product information in modal data attributes
    document.getElementById('productModal').dataset.productId = productId;
    document.getElementById('productModal').dataset.productPrice = productPrice;
    document.getElementById('productModal').dataset.availableQuantity = availableQuantity; // Store available quantity
}

// Function to handle "Confirm" button click
document.getElementById('confirmPurchaseBtn').addEventListener('click', function() {
    const productId = document.getElementById('productModal').dataset.productId;
    const productName = document.getElementById('modalProductName').innerText;
    const productPrice = parseFloat(document.getElementById('productModal').dataset.productPrice);
    const availableQuantity = parseInt(document.getElementById('productModal').dataset.availableQuantity);
    const quantity = parseInt(document.getElementById('productQuantity').value);

    if (quantity > availableQuantity) {
        alert(`The available quantity for this product is ${availableQuantity}. Please enter a valid quantity.`);
        return; // Exit if quantity is too high
    }

    // Calculate total price for the product
    const totalPrice = productPrice * quantity;
    totalAmount += totalPrice;

    // Insert row into checkout table
    const tableBody = document.querySelector('#checkoutTable tbody');
    const newRow = tableBody.insertRow();
    newRow.innerHTML = `
        <td style="padding: 5px;">${itemNumber}</td>
        <td style="padding: 5px;">${productName}</td>
        <td style="padding: 5px;">${quantity}</td>
        <td style="padding: 5px;">₱${totalPrice.toFixed(2)}</td>
    `;

    // Update total amount in checkout section
    updateCheckoutTotal();

    // Increment item number for next row
    itemNumber++;

    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.hide();
});

// Function to update the total amount in the checkout container
function updateCheckoutTotal() {
    document.querySelector('#checkoutTotalAmount').innerText = `₱${totalAmount.toFixed(2)}`;
}