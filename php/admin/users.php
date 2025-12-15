<?php
// php/admin/users.php
include '../includes/admin_check.php';
include '../dbConnect.php';
include '../models/UserModel.php';

$userModel = new UserModel($conn);
$message = '';

// Handle user deletion
if (isset($_GET['delete_id'])) {
    // CSRF Token Validation for GET requests
    if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    
    $delete_id = filter_var($_GET['delete_id'], FILTER_SANITIZE_NUMBER_INT);
    
    // Validate ID
    if (!is_numeric($delete_id) || $delete_id <= 0) {
        $message = "<div class='error'>Invalid user ID!</div>";
    } else {
        // Prevent admin from deleting themselves
        if ($delete_id != $_SESSION['user_id']) {
            if ($userModel->delete($delete_id)) {
                $message = "<div class='success'>User deleted successfully!</div>";
            } else {
                $message = "<div class='error'>Error deleting user!</div>";
            }
        } else {
            $message = "<div class='error'>You cannot delete your own account!</div>";
        }
    }
}

// Get all users
$users = $userModel->readAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Football Agent SL</title>
    <link rel="stylesheet" href="../../styles.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: #2a2a2a;
            border-radius: 10px;
            overflow: hidden;
        }
        .user-table th, .user-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #333;
        }
        .user-table th {
            background-color: #1a1a1a;
            color: #00cc66;
            font-weight: bold;
        }
        .user-table tr:hover {
            background-color: #333;
        }
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 0.25rem;
            font-size: 0.9rem;
        }
        .btn-primary {
            background-color: #00cc66;
            color: #000;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }
        .btn-info {
            background-color: #17a2b8;
            color: white;
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
        .role-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .role-admin { background: #dc3545; color: white; }
        .role-player { background: #007bff; color: white; }
        .role-agent { background: #28a745; color: white; }
        .role-club_manager { background: #6f42c1; color: white; }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .search-box {
            margin-bottom: 1rem;
        }
        .search-box input {
            padding: 0.75rem;
            border: 1px solid #444;
            border-radius: 5px;
            background: #1a1a1a;
            color: white;
            width: 300px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="../../images/logo.png" alt="Football Agent Sierra Leone" class="logo-img">
                <span class="logo-text">User Management</span>
            </div>
            <nav>
                <ul>
                    <li><a href="../../index.php">Home</a></li>
                    <li><a href="dashboard.php">Admin</a></li>
                    <li><a href="users.php" class="active">Users</a></li>
                    <!-- <li><a href="create_user.php">Add </a></li> -->
                    <!-- <li><a href="../dashboard.php">U/Dashboard</a></li> -->
                    <li><a href="../logout.php">Logout </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="admin-container">
            <h2 class="section-title">User Management</h2>
            
            <?php echo $message; ?>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <h3>All Users (<?php echo $users->num_rows; ?>)</h3>
                </div>
                <a href="create_user.php" class="btn btn-primary">➕ Add New User</a>
            </div>

            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($users->num_rows > 0) {
                        while ($user = $users->fetch_assoc()) {
                            $roleClass = 'role-' . $user['role'];
                            $statusText = $user['is_active'] ? 'Active' : 'Inactive';
                            $statusClass = $user['is_active'] ? 'success' : 'error';
                            
                            echo "<tr>";
                            echo "<td>{$user['id']}</td>";
                            echo "<td><strong>{$user['username']}</strong></td>";
                            echo "<td>{$user['first_name']} {$user['last_name']}</td>";
                            echo "<td>{$user['email']}</td>";
                            echo "<td><span class='role-badge {$roleClass}'>" . ucfirst($user['role']) . "</span></td>";
                            echo "<td>{$user['phone']}</td>";
                            echo "<td><span class='{$statusClass}' style='padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;'>{$statusText}</span></td>";
                            echo "<td>" . date('M j, Y', strtotime($user['created_at'])) . "</td>";
                            echo "<td class='action-buttons'>";
                            echo "<a href='edit_user.php?id={$user['id']}' class='btn btn-edit'>Edit</a>";
                            if ($user['id'] != $_SESSION['user_id']) {
                                echo "<a href='users.php?delete_id={$user['id']}&csrf_token=" . urlencode($_SESSION['csrf_token'] = bin2hex(random_bytes(32))) . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete {$user['username']}?\")'>Delete</a>";
                            } else {
                                echo "<button class='btn' style='background: #6c757d; color: white;' disabled>Delete</button>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' style='text-align: center; padding: 2rem;'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="contact-info">
                    <h4>Contact Information</h4>
                    <p><a href="mailto:nelson@footballagent.sl">📧nelson@footballagent.sl</a></p>
                    <p><a href="tel:+23279826564">📞 +232 79 826-564</a></p>
                    <p><a href="https://www.google.com/maps/search/?api=1&query=Goderich+Street+Freetown+Sierra+Leone" target="_blank" rel="noopener">
                        📍15 Goderich Street,<br>Freetown, Sierra Leone</a>
                    </p>
                </div>
                <div class="social-links">
                    <h4>Follow Us</h4>
                    <a href="#" aria-label="Facebook">Facebook</a>
                    <a href="#" aria-label="Twitter">Twitter</a>
                    <a href="#" aria-label="Instagram">Instagram</a>
                    <a href="#" aria-label="LinkedIn">LinkedIn</a>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Football Agent Sierra Leone. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php $conn->close(); ?>