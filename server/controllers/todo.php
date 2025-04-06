<?php
require_once "models/todo.php";

class TodoController {
private $todoModel;

public function __construct($pdo){
    $this->todoModel = new TodoModel($pdo);
}

public function handleRequest($requestMethod, $todoId = null){
    try{    
        switch($requestMethod){
            case 'GET' :
                if(isset($todoId)){
                    echo json_encode(["data" => "todo data retrieved"]);
                } else {
                    echo json_encode(["data" => "todos data retrieved"]);
                }
                break;
            case 'POST' :
                $input = json_decode(file_get_contents('php://input'),true);
                if(isset($input["userId"]) && isset($input["text"])){
                    $todoId = $this->todoModel->createTodo($input["userId"],$input["text"]);
                    http_response_code(201);
                    echo json_encode(["message" => "todo created by user {$input['userId']}, todo id = {$todoId}"]);
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
                if(isset($todoId)){
                    echo json_encode(["message" => "todo updated"]);
                } else {
                    echo json_encode(["error" => "UserId required to update"]);
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
    echo json_encode(["error" => "An error occurred: " . $e->getMessage()]);
    }  
}}
?>