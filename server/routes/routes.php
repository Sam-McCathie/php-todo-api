<?php
    require_once 'helpers/helpers.php';
    require_once 'controllers/todo.php';
    require_once 'controllers/user.php';


    function routes($requestURI, $requestMethod, $pdo){
        header('Content-Type: application/json');

        $todoPattern = '#^/todos(?:/(\d+))?$#';
        $userPattern = '#^/users(?:/(\d+))?$#';

        $todoMatch = matchRouteAndExtractId($todoPattern, $requestURI);
        $userMatch = matchRouteAndExtractId($userPattern, $requestURI);

        if ($todoMatch['matched']) {
            $todoId = $todoMatch['id'];
            $todoController = new TodoController($pdo);
            $todoController->handleRequest($requestMethod, $todoId);
        } else if ($userMatch['matched']) {
            $userId = $userMatch['id'];
            $userController = new UserController;
            $userController->handleRequest($requestMethod, $userId);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Endpoint not valid"
            ]);
        }
    }
?>