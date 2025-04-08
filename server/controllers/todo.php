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
                    echo json_encode ([
                        "status" => "error",
                        "message" => "Please provide a userId",
                        "data" => null
                    ]);
                }
                break;
            case 'POST' :
                $input = json_decode(file_get_contents('php://input'),true);
                if(isset($input["userId"]) && isset($input["text"])){
                    $response = $this->todoModel->createTodo($input["userId"],$input["text"]);
                    http_response_code(201);
                    echo json_encode($response);
                } else {
                    $errorMessage = "";
                    if(!isset($input["userId"])){
                        $errorMessage = "todo userId not set";
                    } else {
                        $errorMessage = "todo text not set";
                    }
                    echo json_encode(["error" => $errorMessage]);
                }
                break;
            case 'PATCH' :
                if(isset($todoId) && isset($text)){
                    $response = $this->todoModel->updateTodo($todoId, $text);
                    echo json_encode($response);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "todoId($todoId) & text($text) required to PATCH",
                        "data" => null
                    ]);
                }
                break;
            case "DELETE" :
                if(isset($todoId)){
                    echo json_encode(["message" => "todo deleted"]);
                } else {
                    echo json_encode(["error" => "UserId required to delete"]);
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