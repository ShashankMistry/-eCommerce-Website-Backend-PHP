<?php

require_once 'Connection.php';

$endpoint = $_SERVER['REQUEST_URI'];

//$endpoint ends with /users


if (strpos($endpoint, 'get/user') && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users, JSON_PRETTY_PRINT);
    //get history of orders by user id
} elseif (strpos($endpoint, '/orders/purchase_hitstory') !== false && $_SERVER['REQUEST_METHOD'] == 'GET'){
    $email = $_GET['id'];
    $product_id = $_GET['pId'];
    try {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE email = :email AND productId = :product_id");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($orders, JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
        http_response_code(400); // Return a 400 error
        echo "Error: something went wrong";
        // Handle other types of exceptions
    }
    //get all orders from email
} elseif (strpos($endpoint, '/order/email') !== false && $_SERVER['REQUEST_METHOD'] == 'GET'){
    $email = $_GET['id'];
    try {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE email = :id");
        $stmt->bindParam(':id', $email);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users, JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
        http_response_code(400); // Return a 400 error
        echo "Error: something went wrong";
        // Handle other types of exceptions
    }
} elseif (strpos($endpoint, '/post/users') !== false && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $JSON = file_get_contents('php://input');
    $data = json_decode($JSON, true);
    $_POST = $data;
    $stmt = $conn->prepare("INSERT INTO users (fName, lName, email, pass, address)
    VALUES (:fName, :lName, :email, :password, :address)");
    $stmt->bindParam(':fName', $_POST['fName']);
    $stmt->bindParam(':lName', $_POST['lName']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':password', $_POST['password']);
    $stmt->bindParam(':address', $_POST['address']);
    $stmt->execute();
    echo json_encode("New record created successfully");
} elseif (strpos($endpoint, '/delete/users') !== false && $_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $JSON = file_get_contents('php://input');
    $data = json_decode($JSON, true);
    $_POST = $data;
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->execute();
    echo json_encode("Record deleted successfully with ID: " . $_POST['id'] );
} elseif (strpos($endpoint, '/update/users') !== false && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $JSON = file_get_contents('php://input');
    $data = json_decode($JSON, true);
    $_POST = $data;
    
    $stmt = $conn->prepare("UPDATE users SET fName = :fName, lName = :lName, email = :email, pass = :password, address = :address WHERE id = :id");

$stmt->bindParam(':id', $_POST['id']);
$stmt->bindParam(':fName', $_POST['fName']);
$stmt->bindParam(':lName', $_POST['lName']);
$stmt->bindParam(':email', $_POST['email']);
$stmt->bindParam(':password', $_POST['pass']);
$stmt->bindParam(':address', $_POST['address']);
$stmt->execute();
    echo json_encode("Record updated successfully");
} else {
    echo "Error: something went wrong else";
}

$conn = null;
?>