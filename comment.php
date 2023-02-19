<?php

require_once 'Connection.php';

$endpoint = $_SERVER['REQUEST_URI'];

if (strpos($endpoint, 'GET/comments') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM comments");
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($comments, JSON_PRETTY_PRINT);
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
} elseif (strpos($endpoint, '/comments/product') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("SELECT * FROM comment WHERE productId = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($comments, JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
        http_response_code(400);
        echo "Error: something went wrong";
    } catch (InvalidArgumentException $e) {
        http_response_code(400);
        $error_message = "Invalid input: " . $e->getMessage();
    } catch (Exception $e) {
        $error_code = 500;
        $error_message = "An error occurred: " . $e->getMessage();
    }
} elseif (strpos($endpoint, '/email/comments') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $JSON = file_get_contents('php://input');
        $data = json_decode($JSON, true);
        $_POST = $data;
        $email = $_POST['email'];
        $stmt = $conn->prepare("SELECT * FROM comment WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($comments, JSON_PRETTY_PRINT);
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
} elseif (strpos($endpoint, '/post/comments') !== false && $_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $JSON = file_get_contents('php://input');
        $data = json_decode($JSON, true);
        $_POST = $data;
        $stmt = $conn->prepare("INSERT INTO comment (productId, email, rating, review, image)
    VALUES (:productId, :email, :rating, :review, :image)");
        $stmt->bindParam(':productId', $_POST['productId']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':rating', $_POST['rating']);
        $stmt->bindParam(':review', $_POST['review']);
        $stmt->bindParam(':image', $_POST['image']);
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
} elseif (strpos($endpoint, '/delete/comments') !== false && $_SERVER['REQUEST_METHOD'] == 'DELETE') {
    try {
        $JSON = file_get_contents('php://input');
        $data = json_decode($JSON, true);
        $_POST = $data;
        $stmt = $conn->prepare("DELETE FROM comment WHERE productId = :productId AND email = :email AND id = :id");
        $stmt->bindParam(':productId', $_POST['productId']);
        $stmt->bindParam(':email', $_POST['email']);
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
}

$conn = null;
