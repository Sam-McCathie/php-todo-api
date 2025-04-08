<?php
require_once "models/todo.php";

class TodoController {
private $todoModel;

public function __construct($pdo){
    $this->todoModel = new TodoModel($pdo);
}

public function handleRequest($requestMethod, $todoId = null){
    try{    
        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $input["userId"] ?? null;
        $text = $input["text"] ?? null;

        switch($requestMethod){
            case 'GET' :
                if(isset($userId)){
                    $response = $this->todoModel->getTodo($userId, $todoId);
                    echo json_encode($response);
                }  else {
                    http_response_code(400);
                    echo json_encode ([
                        "status" => "error",
                        "message" => "userId required to GET",
                        "data" => null
                    ]);
                }
                break;
            case 'POST' :
                if(isset($userId) && isset($text)){
                    $response = $this->todoModel->createTodo($input["userId"],$input["text"]);
                    http_response_code(201);
                    echo json_encode($response);
                } else {
                    http_response_code(400);
                    echo json_encode([
                        "status" => "error",
                        "message" => "userId($userId) & text($text) required to POST",
                        "data" => null
                    ]);
                }
                break;
            case 'PATCH' :
                if(isset($todoId) && isset($text)){
                    $response = $this->todoModel->updateTodo($todoId, $text);
                    echo json_encode($response);
                } else {
                    http_response_code(400);
                    echo json_encode([
                        "status" => "error",
                        "message" => "todoId($todoId) & text($text) required to PATCH",
                        "data" => null
                    ]);
                }
                break;
            case "DELETE" :
                if(isset($todoId)){
                    $response = $this->todoModel->deleteTodo($todoId);
                    echo json_encode($response);
                } else {
                    http_response_code(400);
                    echo json_encode ([
                        "status" => "error",
                        "message" => "todoId required to Delete",
                        "data" => null
                    ]);
                }
                break;
            default :
                http_response_code(405);
                echo json_encode(["error" => "Method not allowed"]);
                break;
        }
    } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage(),
        "data" => null
    ]);
    }  
}}
?>