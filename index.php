<?php

$host = "localhost";
$dbname = "assignment2_group8";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Query the database to retrieve product data
// $stmt = $conn->prepare("SELECT * FROM product");
// $stmt->execute();
// $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$products = [];
//fetch data only when endpoint is products
// Get the endpoint from the URL
$endpoint = $_SERVER['REQUEST_URI'];

// Check if the endpoint is present in the URL
//check if it is GET request
if (strpos($endpoint, '/products') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {

    // Fetch data from the endpoint
    $stmt = $conn->prepare("SELECT * FROM product");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products, JSON_PRETTY_PRINT);
    // Process the data as needed
    // ...

} else {
    // Return an error message
    echo json_encode("Error: Endpoint not found");
}

// Close the database connection
$conn = null;
