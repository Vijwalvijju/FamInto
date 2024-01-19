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

// Fetch orders from the database
try {
    $stmt = $pdo->query("SELECT * FROM orders");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output orders as JSON (you can customize the display logic)
    header('Content-Type: application/json');
    echo json_encode($orders);
} catch (PDOException $e) {
    // Handle database error
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch data from the database']);
}
?>
