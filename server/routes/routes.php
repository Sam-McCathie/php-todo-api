<?php
    require_once 'helpers/helpers.php';
    require_once 'controllers/todo-controller.php';
    require_once 'controllers/user-controller.php';


    function routes($requestURI, $requestMethod){
        header('Content-Type: application/json');

        $todoPattern = '#^/todos(?:/(\d+))?$#';
        $userPattern = '#^/users(?:/(\d+))?$#';

        $todoMatch = matchRouteAndExtractId($todoPattern, $requestURI);
        $userMatch = matchRouteAndExtractId($userPattern, $requestURI);

        if ($todoMatch['matched']) {
            $todoId = $todoMatch['id'];
            $todoController = new TodoController;
            $todoController->handleRequest($requestMethod, $todoId);
        } else if ($userMatch['matched']) {
            $userId = $userMatch['id'];
            $userController = new UserController;
            $userController->handleRequest($requestMethod, $userId);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Endpoint not valid"]);
        }
    }
?>