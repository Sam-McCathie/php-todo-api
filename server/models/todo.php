<?php
class TodoModel {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function createTodo($userId, $text){
        $stmt = $this->pdo->prepare('INSERT INTO todos (user_id, text) VALUES (:userId, :text)');
        $stmt->execute(['userId'=>$userId, "text"=>$text]);
        return $this->pdo->lastInsertedId();
    }
}
?>