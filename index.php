<?php

require_once 'Connection.php';


$products = [];

$endpoint = $_SERVER['REQUEST_URI'];


if (strpos($endpoint, '/products') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {

    // Fetch data from the endpoint
    $stmt = $conn->prepare("SELECT * FROM product");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products, JSON_PRETTY_PRINT);
    // Process the data as needed
    // ...
} elseif (strpos($endpoint, '/post/products') !== false && $_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch data from the endpoint

    $JSON = file_get_contents('php://input');
    $data = json_decode($JSON, true);
    $_POST = $data;
    $stmt = $conn->prepare("INSERT INTO product (name, description, details, image, price) 
    VALUES (:name, :description, :details, :image, :price)");
    
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':description', $_POST['description']);
    $stmt->bindParam(':details', $_POST['details']);
    $stmt->bindParam(':image', $_POST['image']);
    $stmt->bindParam(':price', $_POST['price']);

    $stmt->execute();
    echo json_encode("New record created successfully");
} else {
    // Return an error message
    echo json_encode("Error: Endpoint not found");
}

// Close the database connection
$conn = null;
