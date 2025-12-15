<?php
/**
 * UserModel.php
 * Handles all database operations for user management
 * Includes CRUD operations, authentication, and role-based queries
 * 
 * @author Football Agent SL Team
 * @version 1.0
 * @package Models
 */

class UserModel {
    /** @var mysqli Database connection object */
    private $conn;
    
    /** @var string Name of the users table */
    private $table_name = "users";

    /**
     * Constructor - Initialize database connection
     * 
     * @param mysqli $db Database connection object
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * CREATE - Add new user to database
     * 
     * @param string $username Unique username for the user
     * @param string $email User's email address
     * @param string $password Plain text password (will be hashed)
     * @param string $role User role (admin, player, agent, club_manager)
     * @param string $first_name User's first name
     * @param string $last_name User's last name
     * @param string $phone User's phone number (optional)
     * @param string $address User's address (optional)
     * @return int|false User ID on success, false on failure
     */
    public function create($username, $email, $password, $role, $first_name, $last_name, $phone, $address) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO " . $this->table_name . " 
                 (username, email, password, role, first_name, last_name, phone, address) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssss", $username, $email, $hashedPassword, $role, $first_name, $last_name, $phone, $address);
        
        if ($stmt->execute()) {
            // Return the inserted user's ID instead of just true
            return $this->conn->insert_id;
        }
        
        return false;
    }

    /**
     * READ - Get all users from database
     * 
     * @return mysqli_result Result set containing all users ordered by creation date
     */
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    /**
     * READ - Get single user by ID
     * 
     * @param int $id User ID to retrieve
     * @return array|null User data as associative array, null if not found
     */
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * READ - Get user by username
     * 
     * @param string $username Username to search for
     * @return array|null User data as associative array, null if not found
     */
    public function readByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * UPDATE - Update user information
     * 
     * @param int $id User ID to update
     * @param string $username Updated username
     * @param string $email Updated email address
     * @param string $role Updated user role
     * @param string $first_name Updated first name
     * @param string $last_name Updated last name
     * @param string $phone Updated phone number
     * @param string $address Updated address
     * @return bool True on success, false on failure
     */
    public function update($id, $username, $email, $role, $first_name, $last_name, $phone, $address) {
        $query = "UPDATE " . $this->table_name . " 
                 SET username=?, email=?, role=?, first_name=?, last_name=?, phone=?, address=?
                 WHERE id=?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssssi", $username, $email, $role, $first_name, $last_name, $phone, $address, $id);
        
        return $stmt->execute();
    }

    /**
     * DELETE - Delete user from database
     * 
     * @param int $id User ID to delete
     * @return bool True on success, false on failure
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }

    /**
     * Verify login credentials
     * 
     * @param string $username Username to verify
     * @param string $password Plain text password to verify
     * @return array|false User data on success, false on failure
     */
    public function verifyLogin($username, $password) {
        $user = $this->readByUsername($username);
        
        if ($user && password_verify($password, $user['password']) && $user['is_active']) {
            return $user;
        }
        return false;
    }

    /**
     * Check if username already exists in database
     * 
     * @param string $username Username to check
     * @return bool True if username exists, false otherwise
     */
    public function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    /**
     * Check if email already exists in database
     * 
     * @param string $email Email address to check
     * @return bool True if email exists, false otherwise
     */
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    /**
     * Get users by role
     * 
     * @param string $role Role to filter by (admin, player, agent, club_manager)
     * @return mysqli_result Result set containing users with specified role
     */
    public function getUsersByRole($role) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE role = ? ORDER BY first_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>