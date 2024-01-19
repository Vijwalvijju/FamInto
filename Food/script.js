function myFunction() {
  var x = document.getElementById("myNavbar");
  if (x.className === "navbar") {
    x.className += " responsive";
  } else {
    x.className = "navbar";
  }
}

// Track selected items
const selectedItems = new Set();

// Function to add item to the cart
function addToCart(itemId) {
  selectedItems.add(itemId);
  updateSelectedItemsList();
}

// Function to update the selected items list
function updateSelectedItemsList() {
  const selectedItemsList = document.getElementById("selectedItemsList");
  selectedItemsList.innerHTML = "";

  for (const itemId of selectedItems) {
    const listItem = document.createElement("li");
    listItem.textContent = `Item ${itemId}`;
    selectedItemsList.appendChild(listItem);
  }
}

// Function to open the order modal
function openOrderModal() {
  const modal = document.getElementById("orderModal");
  modal.style.display = "block";
}

// Close the order modal when clicking the close button
document.getElementsByClassName("close")[0].onclick = function () {
  const modal = document.getElementById("orderModal");
  modal.style.display = "none";
};

// Function to send the order
function sendOrder() {
  const customerName = document.getElementById("customerName").value;
  const customerPhone = document.getElementById("customerPhone").value;

  // Check if there are selected items
  if (selectedItems.size === 0) {
    alert("Please add items to the cart before placing an order.");
    return;
  }

  // Prepare the order data
  const orderData = {
    name: customerName,
    phone: customerPhone,
    items: Array.from(selectedItems),
  };

  // Send the order data to the server using fetch
  fetch('order.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(orderData),
  })
  .then(response => {
    if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
    }
    return response.json();
})
  .then(data => {
    // Handle the server's response
    console.log(data);

    // Display the total cost and any additional message to the user
    alert(`Order placed successfully! Your token number is: ${data.token}\nTotal Cost: ${data.total_cost}\n${data.message}`);
  })
  .catch(error => {
    console.error('Error:', error);
    alert("Failed to place the order. Please try again.");
  });

  // Close the modal after sending the order
  const modal = document.getElementById("orderModal");
  modal.style.display = "none";
}
