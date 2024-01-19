<!-- remove_order.php -->

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
    $requestData = json_decode($jsonData, true);

    // Process the request data
    $orderId = $requestData['orderId'];

    try {
        // Prepare and execute the SQL query to remove the order
        $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->execute([$orderId]);

        // Return a success response
        $response = [
            'success' => true,
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (PDOException $e) {
        // Handle database error
        http_response_code(500);
        echo json_encode(['error' => 'Failed to remove the order from the database']);
    }
} else {
    // Handle invalid request method
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>
