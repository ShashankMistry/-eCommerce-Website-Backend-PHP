<?php

require_once 'Connection.php';


$products = [];

$endpoint = $_SERVER['REQUEST_URI'];


if (strpos($endpoint, '/products') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM product");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products, JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode("Error: something went wrong" . $e->getMessage());
    } catch (InvalidArgumentException $e) {
        http_response_code(400);
        $error_message = "Invalid input: " . $e->getMessage();
    } catch (Exception $e) {
        $error_code = 500;
        $error_message = "An error occurred: " . $e->getMessage();
    }
} elseif (strpos($endpoint, '/post/products') !== false && $_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
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
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode("Error: something went wrong" . $e->getMessage());
    } catch (InvalidArgumentException $e) {
        http_response_code(400);
        $error_message = "Invalid input: " . $e->getMessage();
    } catch (Exception $e) {
        $error_code = 500;
        $error_message = "An error occurred: " . $e->getMessage();
    }
} elseif (strpos($endpoint, '/delete/products') !== false && $_SERVER['REQUEST_METHOD'] == 'DELETE') {
    try {
        $JSON = file_get_contents('php://input');
        $data = json_decode($JSON, true);
        $_POST = $data;
        $stmt = $conn->prepare("DELETE FROM product WHERE id = :id");
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();
        echo json_encode("Record deleted successfully with ID: " . $_POST['id']);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode("Error: something went wrong" . $e->getMessage());
    } catch (InvalidArgumentException $e) {
        http_response_code(400);
        $error_message = "Invalid input: " . $e->getMessage();
    } catch (Exception $e) {
        $error_code = 500;
        $error_message = "An error occurred: " . $e->getMessage();
    }
} elseif (strpos($endpoint, '/update/products') !== false && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    try {
        $JSON = file_get_contents('php://input');
        $data = json_decode($JSON, true);
        $_POST = $data;
        $stmt = $conn->prepare("UPDATE product SET name = :name, description = :description, details = :details, image = :image, price = :price WHERE id = :id");
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':description', $_POST['description']);
        $stmt->bindParam(':details', $_POST['details']);
        $stmt->bindParam(':image', $_POST['image']);
        $stmt->bindParam(':price', $_POST['price']);
        $stmt->execute();
        echo json_encode("Record updated successfully");
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode("Error: something went wrong" . $e->getMessage());
    } catch (InvalidArgumentException $e) {
        http_response_code(400);
        $error_message = "Invalid input: " . $e->getMessage();
    } catch (Exception $e) {
        $error_code = 500;
        $error_message = "An error occurred: " . $e->getMessage();
    }
} else {
    echo json_encode("Error: Endpoint not found");
}

$conn = null;
