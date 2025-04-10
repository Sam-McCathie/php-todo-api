<?php
    require_once "models/user.php";
    require_once "helpers/helpers.php";
    
    class UserController {
        private $userModel;

        public function __construct($pdo){
            $this->userModel = new UserModel($pdo); 
        }

        # userId passed as url param
        public function handleRequest($requestMethod, $userId = null){
            try{
                $input = json_decode(file_get_contents('php://input'), true);
                $username = $input["username"] ?? null;

                switch($requestMethod){
                    case 'GET' :
                        $response = $this->userModel->getUser($userId);
                        sendResponse($response);
                        break;
                    case 'POST' :
                        if(isset($username)){
                            try {
                                $response = $this->userModel->createUser($username);
                                sendResponse($response, 201);
                            } catch (PDOException $e) {
                                $errorCode = $e->errorInfo[1]; // MySQL-specific error number
                                if ($errorCode == 1062) { // MySQL error code for duplicate entry
                                    sendResponse([
                                        "status" => "error",
                                        "message" => "Username '$username' already exists.",
                                    ], 409); // HTTP 409 Conflict
                                    exit;
                                } else {
                                    throw $e; 
                                }
                            }
                        } else {
                            sendResponse([
                                "status" => "error",
                                "message" => "username required to POST",
                            ], 400);
                        }
                        http_response_code(201);
                        echo json_encode(["message" => "user created"]);
                        break;
                    case 'PATCH' :
                        if(isset($userId) && isset($username)){
                            $response = $this->userModel->updateUser($userId, $username);
                            sendResponse($response, $response['httpCode'] ?? 200);
                        } else {
                            sendResponse([
                                "status" => "error",
                                "message" => "userId($userId) & username($username) required to PATCH",
                            ], 400);
                        }
                        break;
                    case "DELETE" :
                        if(isset($userId)){
                            $response = $this->userModel->deleteUser($userId);
                            sendResponse($response, $response['httpCode'] ?? 200);
                        } else {
                            sendResponse([
                                "status" => "error",
                                "message" => "userId($userId) required to DELETE",
                            ], 400);
                        }
                        break;
                    default :
                        http_response_code(405);
                        echo json_encode(["error" => "Method not allowed"]);
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