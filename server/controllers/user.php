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
                            $response = $this->userModel->createUser($username);
                            sendResponse($response, 201);
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
            } catch (Exception $e) {
                sendResponse([
                    "status" => "error",
                    "message" => $e->getMessage(),
                ], 500);
            }
        }
    }
?>