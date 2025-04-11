<?php
class TodoModel {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    # todoId passed as url param
    public function getTodo($userId, $todoId){
        if (isset($userId) && isset($todoId)) {
            $stmt = $this->pdo->prepare('SELECT * FROM todos WHERE user_id = :userId AND todo_id = :todoId');
            $stmt->execute(["userId" => $userId, "todoId" => $todoId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $this->pdo->prepare('SELECT * FROM todos WHERE user_id = :userId');
            $stmt->execute(["userId" => $userId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if (!$result) {
            return [
                "status" => "success",
                "message" => "No todo(s) found.",
                "data" => []
            ];
        }

        return [
            "status" => "success",
            "message" => "Todo(s) retrieved successfully.",
            "data" => $result
        ];
    }

    public function createTodo($userId, $text){
        $stmt = $this->pdo->prepare('INSERT INTO todos (user_id, text) VALUES (:userId, :text)');
        $stmt->execute(["userId" => $userId, "text" => $text]);
        return [
            "status" => "success",
            "message" => "Todo created successfully.",
            "data" => ["todoId" => $this->pdo->lastInsertId()]
        ];
    }

    public function updateTodo($todoId, $text, $complete){
        #todoId is checked in controller
        if (isset($text) && isset($complete)) {
            $stmt = $this->pdo->prepare(
                'UPDATE todos 
                SET text = :text, complete = :complete 
                WHERE todo_id = :todoId'
            );
            $stmt->execute(["todoId" => $todoId, "text" => $text, "complete" => $complete]);
            $action = "text & complete";
        } else if (isset($text)) {
            $stmt = $this->pdo->prepare(
                'UPDATE todos 
                SET text = :text 
                WHERE todo_id = :todoId'
            );
            $stmt->execute(["todoId" => $todoId, "text" => $text]);
            $action = "text";
        } else {
            $stmt = $this->pdo->prepare(
                'UPDATE todos 
                SET complete = :complete 
                WHERE todo_id = :todoId'
            );
            $stmt->execute(["todoId" => $todoId, "complete" => $complete]);
            $action = "complete";
        }

        $rowsModified = $stmt->rowCount();

        if ($rowsModified === 0) {
            // Check if the todoId exists
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM todos WHERE todo_id = :todoId');
            $stmt->execute(["todoId" => $todoId]);
            $todoExists = $stmt->fetchColumn();

            if(!$todoExists){
                return [
                    "status" => "error",
                    "message" => "Error updating todo. todoId: $todoId not found",
                    "httpCode" => 404
                ];
            } else {
                return [
                        "status" => "success",
                        "message" => "No changes made. Data already matches the database.",
                ];
            }
        }

        return [
            "status" => "success",
            "message" => "Todo $action updated successfully.",
        ];
    }

    public function deleteTodo($todoId){
        $stmt = $this->pdo->prepare('DELETE FROM todos WHERE todo_id = :todoId');
        $stmt->execute(["todoId" => $todoId]);
        $rowsModified = $stmt->rowCount();

        if ($rowsModified === 0) {
            return [
                "status" => "error",
                "message" => "Error deleting todo. todoId: $todoId not found",
                "httpCode" => 404
            ];
        }

        return [
            "status" => "success",
            "message" => "todoId: $todoId deleted successfully.",
        ];
    }
}
?>