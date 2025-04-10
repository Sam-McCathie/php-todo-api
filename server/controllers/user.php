<?php
    require_once "models/user.php";
    require_once "helpers/helpers.php";
    
    class UserController {
        private $userModel;

        public function __construct($pdo){
            $this->userModel = new UserModel($pdo); 
        }

        public function handleRequest($requestMethod, $userId = null){
            try{
                $input = json_decode(file_get_contents('php://input'), true);
                $username = $input["username"];

                switch($requestMethod){
                    case 'GET' :
                        if(isset($userId)){
                            echo json_encode(["data" => "user data retrieved"]);
                        } else {
                            echo json_encode(["data" => "users data retrieved"]);
                        }
                        break;
                    case 'POST' :
                        if(isset($username)){
                            $response = $this->userModel->createUser($username);
                            sendResponse($response);
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
                        if(isset($userId)){
                            echo json_encode(["message" => "user updated"]);
                        } else {
                            echo json_encode(["error" => "UserId required to update"]);
                        }
                        break;
                    case "DELETE" :
                        if(isset($userId)){
                            echo json_encode(["message" => "user deleted"]);
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
                sendResponse([
                    "status" => "error",
                    "message" => $e->getMessage(),
                ], 500);
            }
        }
    }
?>