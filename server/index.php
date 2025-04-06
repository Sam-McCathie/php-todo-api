<?php
    require_once "./routes/routes.php";

    $database = getEnv("MYSQL_DATABASE");
    $user = getEnv("MYSQL_USER");
    $password = getEnv("MYSQL_PASSWORD");

    try{
        $pdo = new PDO("mysql:host=mysql;port=3306;dbname=$database;charset=utf8mb4", $user, $password);
    } catch(PDOException $e){
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
        exit;
    }
    
    $requestURI = $_SERVER['REQUEST_URI']; 
    $requestMethod = $_SERVER['REQUEST_METHOD']; 
    
    routes($requestURI, $requestMethod, $pdo);  
?>