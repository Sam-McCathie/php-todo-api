<?php
    require_once 'helpers/helpers.php';

    function routes($requestURI, $requestMethod){
        header('Content-Type: application/json');

        $todoPattern = '#^/todos(?:/(\d+))?$#';
        $userPattern = '#^/users(?:/(\d+))?$#';

        $todoMatch = matchRouteAndExtractId($todoPattern, $requestURI);
        $userMatch = matchRouteAndExtractId($userPattern, $requestURI);

        if ($todoMatch['matched']) {
            $todoId = $todoMatch['id'];

            echo json_encode(["Method" => $requestMethod, "id"=>$todoId]);

        } else if ($userMatch['matched']) {
            $userId = $userMatch['id'];

            echo json_encode(["Method" => $requestMethod, "id"=>$userId]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Endpoint not valid"]);
        }
    }
?>