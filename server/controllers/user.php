<?php
    class UserController {
        private $userModel;

        public function __construct(){
            // will construct model later
        }

        public function handleRequest($requestMethod, $userId = null){
            switch($requestMethod){
                case 'GET' :
                    if(isset($userId)){
                        echo json_encode(["data" => "user data retrieved"]);
                    } else {
                        echo json_encode(["data" => "users data retrieved"]);
                    }
                    break;
                case 'POST' :
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
        }
    }
?>