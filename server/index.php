<?php
    require_once "./routes/routes.php";
    
    $requestURI = $_SERVER['REQUEST_URI']; // URL of request
    $requestMethod = $_SERVER['REQUEST_METHOD']; // request method
    // $user = getEnv("MYSQL_USER");
    
    routes($requestURI, $requestMethod);  
?>