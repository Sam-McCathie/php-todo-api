<?php
    class TodoController {
        private $todoModel;

        public function __construct(){
            // will construct model later
        }

        public function handleRequest($requestMethod, $todoId = null){
            switch($requestMethod){
                case 'GET' :
                    if(isset($todoId)){
                        echo json_encode(["data" => "todo data retrieved"]);
                    } else {
                        echo json_encode(["data" => "todos data retrieved"]);
                    }
                    break;
                case 'POST' :
                    http_response_code(201);
                    echo json_encode(["message" => "todo created"]);
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
        }
    }
?>