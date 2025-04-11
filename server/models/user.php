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
        $rowsModified = $stmt->rowCount();

        if ($rowsModified === 0) {
            throw new Exception("Failed to create user: '$username'.");
        }

        return [
            "status" => "success",
            "message" => "user: '$username', created successfully",
            "data" =>["userId" => $this->pdo->lastInsertId()]
        ];
    }

    public function updateUser($userId, $username){
        $stmt = $this->pdo->prepare("UPDATE users SET username = :username WHERE user_id = :userId");
        $stmt->execute(["userId" => $userId, "username" => $username]);
        $rowsModified = $stmt->rowCount();

        if($rowsModified === 0){
            // Check if the userId exists
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE user_id = :userId');
            $stmt->execute(["userId" => $userId]);
            $userExists = $stmt->fetchColumn();

            if(!$userExists){
                return [
                    "status" => "error",
                    "message" => "Error updating user. userId: $userId not found",
                    "httpCode" => 404
                ];
            } else {
                return [
                    "status" => "success",
                    "message" => "No changes made. username already matches the database.",
                ];
            }  
        }

        return [
            "status" => "success",
            "message" => "User updated successfully.",
        ];
    }

    public function deleteUser($userId){
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = :userId");
        $stmt->execute(["userId" => $userId]);
        $rowsModified = $stmt->rowCount();

        if($rowsModified === 0){
            return [
                "status" => "error",
                "message" => "Error deleting user. userId: $userId not found",
                "httpCode" => 404
            ];
        }

        return [
            "status" => "success",
            "message" => "userId: $userId deleted successfully.",
        ];
    }
}