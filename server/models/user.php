<?php

class UserModel{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    /**
     * Retrieves user(s) from the database.
     * 
     * - If $userId is provided, retrieves a single user with the given ID.
     * - If $userId is not provided, retrieves all users.
     * 
     * @param int|null $userId The ID of the user to retrieve, or null to retrieve all users.
     */
    public function getUser($userId){
        if(isset($userId)){
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :userId");
            $stmt->execute(["userId" => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM users");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if(!$result){
            return [
                "status" => "success",
                "message" => "No users(s) found.",
                "data" => []
            ];
        }

        return [
            "status" => "success",
            "message" => "User(s) retrieved successfully.",
            "data" => $result
        ];
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

    public function updateUser($userId, $username){

    }

    public function deleteUser($userId){

    }
}