<?php
class TodoModel {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    # PDO Errors caught in controller

    public function getTodo($userId, $todoId){
            if(isset($userId) && isset($todoId)){
                $stmt = $this->pdo->prepare('SELECT * FROM todos WHERE user_id = :userId AND todo_id = :todoId');
                $stmt->execute(["userId"=>$userId, "todoId"=>$todoId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = $this->pdo->prepare('SELECT * FROM todos WHERE user_id = :userId');
                $stmt->execute(["userId"=>$userId]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if (!$result) {
                return [
                    "status" => "success",
                    "message" => "No todo(s) found.",
                    "data" => []
                ];
                exit;
            }

            return [
                "status" => "success",
                "message" => "Todo(s) retrieved successfully.",
                "data" => $result
            ];
    }

    public function createTodo($userId, $text){
        $stmt = $this->pdo->prepare('INSERT INTO todos (user_id, text) VALUES (:userId, :text)');
        $stmt->execute(["userId"=> $userId, "text"=>$text]);
        return [
            "status" => "success",
            "message" => "Todo created successfully.",
            "data" => ["todoId" => $this->pdo->lastInsertId()]
        ];
    }

    public function updateTodo($todoId, $text){
        $stmt = $this->pdo->prepare('UPDATE todos SET text = :text WHERE todo_id = :todoId');
        $stmt->execute(["todoId" => $todoId, "text" => $text]);

        if($stmt->rowCount() === 0){
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Error updating todo. todoId: $todoId not found",
                "data" => null
            ]);
            exit;
        }

        return [
            "status" => "success",
            "message" => "Todo updated successfully.",
            "data" => null
        ];
    }


    public function deleteTodo($todoId){
       $stmt = $this->pdo->prepare('DELETE FROM todos WHERE todo_id = :todoId');
       $stmt->execute(["todoId"=>$todoId]);

       if($stmt->rowCount() === 0){
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Error deleting todo. todoId: $todoId not found",
            "data" => null
        ]);
        exit;
    }

       return [
        "status" => "success",
        "message" => "todoId: $todoId deleted successfully.",
        "data" => null
    ];
    }
}
?>