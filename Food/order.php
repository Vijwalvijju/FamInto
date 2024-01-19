<?php

// Database configuration
$host = 'localhost';
$dbname = 'food';
$username = 'root';
$password = '';

// Attempt to establish a connection to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection error
    http_response_code(500);
    echo json_encode(['error' => 'Failed to connect to the database']);
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $jsonData = file_get_contents('php://input');
    $orderData = json_decode($jsonData, true);

    // Process the order data
    $customerName = $orderData['name'];
    $customerPhone = $orderData['phone'];
    $selectedItems = $orderData['items'];

    try {
        // Fetch item prices from the database based on $selectedItems
        $itemPrices = fetchItemPrices($selectedItems);
        // Calculate total cost
        $totalCost = calculateTotalCost($selectedItems, $itemPrices);

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_phone, selected_items,item_costs, total_cost) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$customerName, $customerPhone, json_encode($selectedItems), json_encode($itemPrices), $totalCost]);

        // Generate a token (for demonstration purposes, you can customize this logic)
        $token = uniqid();

        // Return a response with total cost
        $response = [
            'success' => true,
            'token' => $token,
            'total_cost' => $totalCost,
            'message' => 'Order placed successfully. Please take the order from the nearest delivery.',
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (PDOException $e) {
        // Handle database error
        http_response_code(500);
        echo json_encode(['error' => 'Failed to insert data into the database']);
    }
} else {
    // Handle invalid request method
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}

// Function to calculate total cost based on selected items
function fetchItemPrices($selectedItems)
{
    // Fetch item prices from the database based on $selectedItems
    // Replace this with your database query to get item prices
    $itemPrices = [
        1 => 30,   // Price for item with ID 1
        2 => 25,
        3 => 100,
        4 => 90,
        5 => 130,
        6 => 230   // Price for item with ID 2
        // Add more item prices as needed
    ];

    return $itemPrices;
}

// Function to calculate total cost based on selected items
function calculateTotalCost($selectedItems, $itemPrices)
{
    $totalCost = 0;

    foreach ($selectedItems as $itemId) {
        if (isset($itemPrices[$itemId])) {
            $totalCost += $itemPrices[$itemId];
        }
    }

    return $totalCost;
}
