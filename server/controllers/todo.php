<?php
require_once "models/todo.php";
require_once "helpers/helpers.php";

class TodoController {
    private $todoModel;

    public function __construct($pdo){
        $this->todoModel = new TodoModel($pdo);
    }

    public function handleRequest($requestMethod, $todoId = null){
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $userId = $input["userId"] ?? null;
            $text = $input["text"] ?? null;

            switch ($requestMethod) {
                case 'GET':
                    if (isset($userId)) {
                        $response = $this->todoModel->getTodo($userId, $todoId);
                        sendResponse($response);
                    } else {
                        sendResponse([
                            "status" => "error",
                            "message" => "userId required to GET",
                        ], 400);
                    }
                    break;

                case 'POST':
                    if (isset($userId) && isset($text)) {
                        $response = $this->todoModel->createTodo($userId, $text);
                        sendResponse($response, 201);
                    } else {
                        sendResponse([
                            "status" => "error",
                            "message" => "userId($userId) & text($text) required to POST",
                        ], 400);
                    }
                    break;

                case 'PATCH':
                    if (isset($todoId) && isset($text)) {
                        $response = $this->todoModel->updateTodo($todoId, $text);
                        sendResponse($response, $response['httpCode'] ?? 200);
                    } else {
                        sendResponse([
                            "status" => "error",
                            "message" => "todoId($todoId) & text($text) required to PATCH",
                        ], 400);
                    }
                    break;

                case 'DELETE':
                    if (isset($todoId)) {
                        $response = $this->todoModel->deleteTodo($todoId);
                        sendResponse($response, $response['httpCode'] ?? 200);
                    } else {
                        sendResponse([
                            "status" => "error",
                            "message" => "todoId required to Delete",
                        ], 400);
                    }
                    break;

                default:
                    sendResponse(["error" => "Method not allowed"], 405);
                    break;
            }
        } catch (Exception $e) {
            sendResponse([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }
}
?>