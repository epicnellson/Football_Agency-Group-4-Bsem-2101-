<?php
// php/admin/create_user.php
include '../includes/admin_check.php';
include '../dbConnect.php';
include '../models/UserModel.php';
include '../models/UserManager.php';

$userModel = new UserModel($conn);
$userManager = new UserManager($conn);
$message = '';

// Get available agents for dropdown
$agents = $userManager->getAvailableAgents();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    
    // Input validation and sanitization
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $role = htmlspecialchars(trim($_POST['role']), ENT_QUOTES, 'UTF-8');
    $first_name = htmlspecialchars(trim($_POST['first_name']), ENT_QUOTES, 'UTF-8');
    $last_name = htmlspecialchars(trim($_POST['last_name']), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($_POST['phone']), ENT_QUOTES, 'UTF-8');
    $address = htmlspecialchars(trim($_POST['address']), ENT_QUOTES, 'UTF-8');
    $date_of_birth = htmlspecialchars(trim($_POST['date_of_birth']), ENT_QUOTES, 'UTF-8');
    
    // Basic validation
    $errors = [];
    if (empty($username) || strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    if (empty($role) || !in_array($role, ['admin', 'player', 'agent', 'club_manager'])) {
        $errors[] = "Valid role is required";
    }
    if (empty($first_name) || strlen($first_name) < 2) {
        $errors[] = "First name must be at least 2 characters";
    }
    if (empty($last_name) || strlen($last_name) < 2) {
        $errors[] = "Last name must be at least 2 characters";
    }
    
    if (!empty($errors)) {
        throw new Exception(implode(', ', $errors));
    }

    try {
        // Check if username already exists
        if ($userModel->usernameExists($username)) {
            throw new Exception("Username '$username' already exists");
        }

        // Check if email already exists
        if ($userModel->emailExists($email)) {
            throw new Exception("Email '$email' already exists");
        }

        // STEP 1: Create base user first
        $userId = $userModel->create($username, $email, $password, $role, $first_name, $last_name, $phone, $address, $date_of_birth);
        
        // Log for debugging
        error_log("User creation - User ID returned: " . ($userId ? $userId : "false"));
        
        if (!$userId || $userId === false) {
            throw new Exception("Failed to create user account - no user ID returned");
        }

        // STEP 2: Create role-specific profile
        if ($role == 'player') {
            $playerSuccess = $userManager->createPlayer(
                $userId,
                $_POST['position'] ?? 'Forward',
                $_POST['height'] ?? null,
                $_POST['weight'] ?? null,
                $_POST['preferred_foot'] ?? 'Right',
                $_POST['current_club'] ?? null,
                !empty($_POST['agent_id']) ? $_POST['agent_id'] : null
            );
            
            if (!$playerSuccess) {
                // If player profile fails, delete the user to maintain consistency
                $userModel->delete($userId);
                throw new Exception("Failed to create player profile");
            }
            
        } elseif ($role == 'agent') {
            $agentSuccess = $userManager->createAgent(
                $userId,
                $_POST['license_number'] ?? null,
                $_POST['years_experience'] ?? 0,
                $_POST['specialization'] ?? null
            );
            
            if (!$agentSuccess) {
                // If agent profile fails, delete the user to maintain consistency
                $userModel->delete($userId);
                throw new Exception("Failed to create agent profile");
            }
            
        } elseif ($role == 'club_manager') {
            $clubSuccess = $userManager->createClubManager(
                $userId,
                $_POST['club_name'] ?? null,
                $_POST['club_location'] ?? null,
                $_POST['club_level'] ?? 'Local'
            );
            
            if (!$clubSuccess) {
                // If club manager profile fails, delete the user to maintain consistency
                $userModel->delete($userId);
                throw new Exception("Failed to create club manager profile");
            }
        }

        $message = "<div class='success'>User created successfully! User ID: $userId</div>";

    } catch (Exception $e) {
        $message = "<div class='error'>Error: " . $e->getMessage() . "</div>";
        error_log("User creation error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Admin</title>
    <link rel="stylesheet" href="../../styles.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: #2a2a2a;
            border-radius: 15px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #00cc66;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #444;
            border-radius: 5px;
            background: #1a1a1a;
            color: white;
            font-size: 1rem;
        }
        .role-specific {
            display: none;
            padding: 1rem;
            background: #333;
            border-radius: 5px;
            margin-top: 1rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb;
        }
        h4 {
            color: #00cc66;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="../../images/logo.png" alt="Football Agent Sierra Leone" class="logo-img">
                <span class="logo-text">Admin Panel - Create User</span>
            </div>
            <nav>
                <ul>
                    <li><a href="../../index.php">Home</a></li>
                    <li><a href="dashboard.php">Admin</a></li>
                    <li><a href="users.php">Manage</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="form-container">
            <h2>Create New User</h2>
            <?php echo $message; ?>
            
            <form method="POST" id="userForm">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Role *</label>
                        <select name="role" id="roleSelect" required onchange="showRoleFields()">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="player">Player</option>
                            <option value="agent">Agent</option>
                            <option value="club_manager">Club Manager</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" name="last_name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth">
                    </div>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3"></textarea>
                </div>

                <!-- Player Specific Fields -->
                <div id="playerFields" class="role-specific">
                    <h4>Player Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Position</label>
                            <select name="position">
                                <option value="Goalkeeper">Goalkeeper</option>
                                <option value="Defender">Defender</option>
                                <option value="Midfielder">Midfielder</option>
                                <option value="Forward">Forward</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Current Club</label>
                            <input type="text" name="current_club">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Height (cm)</label>
                            <input type="number" step="0.1" name="height">
                        </div>
                        <div class="form-group">
                            <label>Weight (kg)</label>
                            <input type="number" step="0.1" name="weight">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Preferred Foot</label>
                            <select name="preferred_foot">
                                <option value="Right">Right</option>
                                <option value="Left">Left</option>
                                <option value="Both">Both</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Assign Agent (Optional)</label>
                            <select name="agent_id">
                                <option value="">No Agent</option>
                                <?php 
                                if ($agents && $agents->num_rows > 0) {
                                    while ($agent = $agents->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($agent['id']) . '">' . 
                                             htmlspecialchars($agent['first_name']) . ' ' . 
                                             htmlspecialchars($agent['last_name']) . 
                                             '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Agent Specific Fields -->
                <div id="agentFields" class="role-specific">
                    <h4>Agent Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>License Number</label>
                            <input type="text" name="license_number">
                        </div>
                        <div class="form-group">
                            <label>Years of Experience</label>
                            <input type="number" name="years_experience" value="0" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Specialization</label>
                        <input type="text" name="specialization" placeholder="e.g., Youth Development, International Transfers">
                    </div>
                </div>

                <!-- Club Manager Specific Fields -->
                <div id="clubManagerFields" class="role-specific">
                    <h4>Club Details</h4>
                    <div class="form-group">
                        <label>Club Name</label>
                        <input type="text" name="club_name">
                    </div>
                    <div class="form-group">
                        <label>Club Location</label>
                        <input type="text" name="club_location" placeholder="e.g., Freetown, Bo City">
                    </div>
                    <div class="form-group">
                        <label>Club Level</label>
                        <select name="club_level">
                            <option value="Local">Local</option>
                            <option value="National">National</option>
                            <option value="International">International</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="cta-button" style="width: 100%; margin-top: 1rem;">
                    <span>Create User</span>
                </button>
            </form>
        </div>
    </main>

    <script>
        function showRoleFields() {
            // Hide all role-specific fields first
            document.querySelectorAll('.role-specific').forEach(field => {
                field.style.display = 'none';
            });
            
            // Show fields for selected role
            const role = document.getElementById('roleSelect').value;
            if (role === 'player') {
                document.getElementById('playerFields').style.display = 'block';
            } else if (role === 'agent') {
                document.getElementById('agentFields').style.display = 'block';
            } else if (role === 'club_manager') {
                document.getElementById('clubManagerFields').style.display = 'block';
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>