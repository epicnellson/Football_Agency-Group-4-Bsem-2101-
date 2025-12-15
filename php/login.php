<?php
// login.php
session_start();
include 'dbConnect.php';
include 'models/UserModel.php';

// Initialize UserModel
$userModel = new UserModel($conn);

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    
    // Input validation and sanitization
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $password = trim($_POST['password']);
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $login_error = 'Username and password are required!';
    } elseif (strlen($username) < 3) {
        $login_error = 'Username must be at least 3 characters!';
    } elseif (strlen($password) < 6) {
        $login_error = 'Password must be at least 6 characters!';
    } else {
        $user = $userModel->verifyLogin($username, $password);
    
    if ($user) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];
        
        // Redirect to homepage
        header('Location: ../index.php');
        exit();
        } else {
            $login_error = 'Invalid username or password!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Football Agent SL</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 5rem auto;
            padding: 2rem;
            background: #2a2a2a;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #00cc66;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #444;
            border-radius: 5px;
            background: #1a1a1a;
            color: white;
        }
        .btn {
            width: 100%;
            padding: 0.75rem;
            background: #00cc66;
            color: #000;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 1rem;
        }
        .error {
            color: #ff6b6b;
            background: rgba(255, 107, 107, 0.1);
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            border: 1px solid #ff6b6b;
        }
        .demo-info {
            background: rgba(0, 204, 102, 0.1);
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1rem;
            border: 1px solid #00cc66;
            color: #ccc;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="../images/logo.png" alt="Football Agent Sierra Leone" class="logo-img">
                <span class="logo-text">Nelson Football Agent SL</span>
            </div>
            <nav>
                <ul>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../services.php">About</a></li>
                    <li><a href="../contact.php">Contact</a></li>
                    <li><a href="login.php" class="active">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="login-container">
            <h2 class="section-title" style="text-align: center; margin-bottom: 2rem;">Login</h2>
            
            <?php if ($login_error): ?>
                <div class="error"><?php echo $login_error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
            
            <div class="demo-info">
                <strong>Demo Credentials:</strong><br>
                All users: password: <code>password</code><br>
                Try: <code>admin1</code>, <code>musa_tombo</code>, <code>nelson_agent</code>, <code>club_manager1</code>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="contact-info">
                    <h4>Contact Information</h4>
                    <p><a href="mailto:nelson@footballagent.sl">📧nelson@footballagent.sl</a></p>
                    <p> <a href="tel:+23279826564">📞 +232 79 826-564</a></p>
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