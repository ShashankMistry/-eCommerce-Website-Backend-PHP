<?php
require_once 'Connection.php';
$endpoint = $_SERVER['REQUEST_URI'];

if (strpos($endpoint, 'GET/cart') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $email = $_GET['email'];
    try {
        $stmt = $conn->prepare("SELECT * FROM cart WHERE email = '$email'");
        $stmt->execute();
        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cart, JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
        http_response_code(400); // Return a 400 error
        echo json_encode("Error: something went wrong" . $e->getMessage());
    }
}

//add to cart using post method
elseif (strpos($endpoint, '/post/cart') !== false && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $JSON = file_get_contents('php://input');
    $data = json_decode($JSON, true);
    $_POST = $data;
    $stmt = $conn->prepare("INSERT INTO cart (productId, name, price, email, quantity)
    VALUES (:productId, :name, :price, :email, :quantity)");
    $stmt->bindParam(':productId', $_POST['productId']);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':price', $_POST['price']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':quantity', $_POST['quantity']);
    $stmt->execute();
    echo json_encode("New record created successfully");
}
//delete from cart using post method
elseif (strpos($endpoint, '/delete/cart') !== false && $_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id = $_GET['id'];
    $email = $_GET['email'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE productId = '$id' AND email = '$email'");
    $stmt->execute();
    echo json_encode("Record deleted successfully");
}
//update cart using put method
elseif (strpos($endpoint, '/update/cart') !== false && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $JSON = file_get_contents('php://input');
    $data = json_decode($JSON, true);
    $_POST = $data;
    $stmt = $conn->prepare("UPDATE cart SET quantity = :quantity WHERE productId = :productId AND email = :email");
    $stmt->bindParam(':productId', $_POST['productId']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':quantity', $_POST['quantity']);
    $stmt->execute();
    echo json_encode("Record updated successfully");
}