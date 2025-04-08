<?php
class TodoModel {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
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
        return $stmt->execute(["todoId" => $todoId, "text"=>$text]);
        return [
            "status" => "success",
            "message" => "Todo updated successfully.",
            "data" => null
        ];
    }

    public function getTodo($userId, $todoId){
        if($todoId){
            $stmt = $this->pdo->prepare('SELECT * FROM todos WHERE user_id = :userId AND todo_id = :todoId');
            $stmt->execute(["userId"=>$userId, "todoId"=>$todoId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $this->pdo->prepare('SELECT * FROM todos WHERE user_id = :userId');
            $stmt->execute(["userId"=>$userId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [
            "status" => "success",
            "message" => "Todo(s) retrieved successfully.",
            "data" => $result
        ];
    }

    public function deleteTodo($todoId){
       $stmt = $this->pdo->prepare('DELETE FROM todos WHERE todo_id = :todoId');
       $stmt->execute(["todoId"=>$todoId]);
       return [
        "status" => "success",
        "message" => "Todo deleted successfully.",
        "data" => null
    ];
    }
}
?>