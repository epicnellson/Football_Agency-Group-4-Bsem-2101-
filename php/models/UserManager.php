<?php
/**
 * UserManager.php
 * Handles role-specific user management operations
 * Manages creation and retrieval of player, agent, and club manager profiles
 * 
 * @author Football Agent SL Team
 * @version 1.0
 * @package Models
 */

class UserManager {
    /** @var mysqli Database connection object */
    private $conn;

    /**
     * Constructor - Initialize database connection
     * 
     * @param mysqli $db Database connection object
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create player profile with proper NULL value handling
     * 
     * @param int $user_id User ID from users table
     * @param string $position Player position
     * @param float|null $height Player height in cm (null if not provided)
     * @param float|null $weight Player weight in kg (null if not provided)
     * @param string $preferred_foot Preferred foot
     * @param string|null $current_club Current club name (null if not provided)
     * @param int|null $agent_id Agent ID (null if not assigned)
     * @return bool True on success, false on failure
     */
    public function createPlayer($user_id, $position, $height, $weight, $preferred_foot, $current_club, $agent_id) {
        $query = "INSERT INTO players 
                 (user_id, position, height, weight, preferred_foot, current_club, agent_id) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        // Convert empty strings to NULL
        $height = !empty($height) ? $height : null;
        $weight = !empty($weight) ? $weight : null;
        $current_club = !empty($current_club) ? $current_club : null;
        $agent_id = !empty($agent_id) ? $agent_id : null;
        
        // Bind parameters
        $stmt->bind_param("isddssi", $user_id, $position, $height, $weight, $preferred_foot, $current_club, $agent_id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Player creation error: " . $stmt->error);
            return false;
        }
    }

    // Create agent profile - FIXED to handle NULL values and add error logging
    public function createAgent($user_id, $license_number, $years_experience, $specialization) {
        $query = "INSERT INTO agents 
                 (user_id, license_number, years_experience, specialization) 
                 VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        // Convert empty strings to NULL
        $license_number = !empty($license_number) ? $license_number : null;
        $years_experience = !empty($years_experience) ? (int)$years_experience : 0;
        $specialization = !empty($specialization) ? $specialization : null;
        
        $stmt->bind_param("isis", $user_id, $license_number, $years_experience, $specialization);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Agent creation error: " . $stmt->error);
            return false;
        }
    }

    // Create club manager profile - FIXED to handle NULL values
    public function createClubManager($user_id, $club_name, $club_location, $club_level) {
        $query = "INSERT INTO club_managers 
                 (user_id, club_name, club_location, club_level) 
                 VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        // Convert empty strings to NULL
        $club_name = !empty($club_name) ? $club_name : null;
        $club_location = !empty($club_location) ? $club_location : null;
        $club_level = !empty($club_level) ? $club_level : 'Local';
        
        $stmt->bind_param("isss", $user_id, $club_name, $club_location, $club_level);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Club manager creation error: " . $stmt->error);
            return false;
        }
    }

    // Get available agents for dropdown
    public function getAvailableAgents() {
        $query = "SELECT u.id, u.first_name, u.last_name 
                  FROM users u 
                  WHERE u.role = 'agent' AND u.is_active = 1 
                  ORDER BY u.first_name";
        $result = $this->conn->query($query);
        return $result;
    }

    // Check if user already has a role profile
    public function userHasRoleProfile($user_id, $role) {
        switch ($role) {
            case 'player':
                $query = "SELECT id FROM players WHERE user_id = ?";
                break;
            case 'agent':
                $query = "SELECT id FROM agents WHERE user_id = ?";
                break;
            case 'club_manager':
                $query = "SELECT id FROM club_managers WHERE user_id = ?";
                break;
            default:
                return false;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }

    // Get player by user ID
    public function getPlayerByUserId($user_id) {
        $query = "SELECT p.*, u.username, u.first_name, u.last_name, u.email 
                  FROM players p 
                  JOIN users u ON p.user_id = u.id 
                  WHERE p.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    // Get agent by user ID
    public function getAgentByUserId($user_id) {
        $query = "SELECT a.*, u.username, u.first_name, u.last_name, u.email 
                  FROM agents a 
                  JOIN users u ON a.user_id = u.id 
                  WHERE a.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    // Get club manager by user ID
    public function getClubManagerByUserId($user_id) {
        $query = "SELECT cm.*, u.username, u.first_name, u.last_name, u.email 
                  FROM club_managers cm 
                  JOIN users u ON cm.user_id = u.id 
                  WHERE cm.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}
?>