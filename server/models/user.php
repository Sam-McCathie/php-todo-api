<?php

class UserModel{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function createUser($username){
        $stmt = $this->pdo->prepare("INSERT INTO users (username) VALUES (:username)");
        $stmt->execute(["username" => $username]);
        return [
            "status" => "success",
            "message" => "user: '$username', created successfully",
            "data" =>["userId" => $this->pdo->lastInsertId()]
        ];
    }
}