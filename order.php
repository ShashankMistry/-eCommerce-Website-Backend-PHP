<?php

require_once 'Connection.php';

$endpoint = $_SERVER['REQUEST_URI'];

//get all orders
if (strpos($endpoint, 'GET/orders') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    try{
    $stmt = $conn->prepare("SELECT * FROM orders");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($orders, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    http_response_code(400); // Return a 400 error
    echo json_encode("Error: something went wrong");
}
}
//update orders
elseif (strpos($endpoint, '/update/orders') !== false && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $JSON = file_get_contents('php://input');
    $data = json_decode($JSON, true);
    $_POST = $data;
    $stmt = $conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
    $stmt->bindParam(':status', $_POST['status']);
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->execute();
    echo json_encode("Record updated successfully");
}
//delete orders
elseif (strpos($endpoint, '/delete/orders') !== false && $_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $JSON = file_get_contents('php://input');
    $data = json_decode($JSON, true);
    $_POST = $data;
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = :id");
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->execute();
    echo json_encode("Record deleted successfully");
}
//add orders
elseif (strpos($endpoint, '/post/orders') !== false && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $JSON = file_get_contents('php://input');
    $data = json_decode($JSON, true);
    $_POST = $data;
    $stmt = $conn->prepare("INSERT INTO orders (productName, productId, name, email,  total, quantity)
    VALUES (:productName, :productId, :name, :email, :total, :quantity)");
    $stmt->bindParam(':productName', $_POST['productName']);
    $stmt->bindParam(':productId', $_POST['productId']);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':total', $_POST['total']);
    $stmt->bindParam(':quantity', $_POST['quantity']);
    $stmt->execute();
    echo json_encode("New record created successfully");

}

?>