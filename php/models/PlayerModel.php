<?php
/**
 * PlayerModel.php
 * Handles all database operations for player management
 * Includes CRUD operations for player profiles and related queries
 * 
 * @author Football Agent SL Team
 * @version 1.0
 * @package Models
 */

class PlayerModel {
    /** @var mysqli Database connection object */
    private $conn;
    
    /** @var string Name of players table */
    private $table_name = "players";

    /**
     * Constructor - Initialize database connection
     * 
     * @param mysqli $db Database connection object
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create player profile
     * 
     * @param int $user_id User ID from users table
     * @param string $date_of_birth Player's date of birth
     * @param string $position Player position (Goalkeeper, Defender, Midfielder, Forward)
     * @param float $height Player height in cm
     * @param float $weight Player weight in kg
     * @param string $preferred_foot Preferred foot (Left, Right, Both)
     * @param string $current_club Current club name
     * @param int $agent_id Agent ID (optional)
     * @param string $video_url Video URL (optional)
     * @param string $stats Player statistics in JSON format
     * @return bool True on success, false on failure
     */
    public function create($user_id, $date_of_birth, $position, $height, $weight, $preferred_foot, $current_club, $agent_id, $video_url, $stats) {
        $query = "INSERT INTO " . $this->table_name . " 
                 (user_id, date_of_birth, position, height, weight, preferred_foot, current_club, agent_id, video_url, stats) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issddssiss", $user_id, $date_of_birth, $position, $height, $weight, $preferred_foot, $current_club, $agent_id, $video_url, $stats);
        
        return $stmt->execute();
    }

    // Read all players with user details
    public function readAllWithUsers() {
        $query = "SELECT p.*, u.username, u.first_name, u.last_name, u.email, u.phone 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  ORDER BY u.first_name";
        $result = $this->conn->query($query);
        return $result;
    }

    // Read single player by ID
    public function readOne($id) {
        $query = "SELECT p.*, u.username, u.first_name, u.last_name, u.email, u.phone, u.address 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  WHERE p.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Read player by user ID
    public function readByUserId($user_id) {
        $query = "SELECT p.*, u.username, u.first_name, u.last_name, u.email, u.phone 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  WHERE p.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Update player profile
    public function update($id, $date_of_birth, $position, $height, $weight, $preferred_foot, $current_club, $agent_id, $video_url, $stats) {
        $query = "UPDATE " . $this->table_name . " 
                 SET date_of_birth=?, position=?, height=?, weight=?, preferred_foot=?, 
                     current_club=?, agent_id=?, video_url=?, stats=?
                 WHERE id=?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssddssissi", $date_of_birth, $position, $height, $weight, $preferred_foot, $current_club, $agent_id, $video_url, $stats, $id);
        
        return $stmt->execute();
    }

    // Delete player profile
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }

    // Get players by position
    public function getPlayersByPosition($position) {
        $query = "SELECT p.*, u.first_name, u.last_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  WHERE p.position = ? 
                  ORDER BY u.first_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $position);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Get available agents for player assignment
    public function getAvailableAgents() {
        $query = "SELECT u.id, u.first_name, u.last_name 
                  FROM users u 
                  WHERE u.role = 'agent' AND u.is_active = 1 
                  ORDER BY u.first_name";
        $result = $this->conn->query($query);
        return $result;
    }

    // Get players without agent
    public function getPlayersWithoutAgent() {
        $query = "SELECT p.*, u.first_name, u.last_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  WHERE p.agent_id IS NULL 
                  ORDER BY u.first_name";
        $result = $this->conn->query($query);
        return $result;
    }
}
?>