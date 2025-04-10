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
                        try{
                            $response = $this->todoModel->createTodo($userId, $text);
                            sendResponse($response, 201);
                        } catch (PDOException $e) {
                            $errorCode = $e->errorInfo[1];
                            if ($errorCode == 1452) { // Foreign key constraint violation
                                sendResponse([
                                    "status" => "error",
                                    "message" => "Invalid user_id. The user does not exist.",
                                ], 400);
                                exit;
                            } else {
                                throw $e;
                            }
                        }
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
        } catch (PDOException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode === 22007) { // Invalid data type
                sendResponse([
                    "status" => "error",
                    "message" => "Invalid input data type.",
                ], 400);
                exit;
            } else if ($errorCode === 1406) { // Data too long for column
                sendResponse([
                    "status" => "error",
                    "message" => "Input data exceeds the allowed length.",
                ], 400);
                exit;
            } else {
                sendResponse([
                    "status" => "error",
                    "message" => $e->getMessage(),
                ], 500);
            }

        }
    }
}
?>