<?php
// php/logout.php
session_start();

// Check if logout is confirmed
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
    session_destroy();
    header('Location: login.php?message=logged_out');
    exit();
}

// If not confirmed, show confirmation page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Football Agent SL</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .logout-section {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
        }
        
        .logout-container {
            max-width: 500px;
            width: 90%;
            margin: 2rem auto;
            padding: 3rem;
            background: #2a2a2a;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 107, 107, 0.3);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .logout-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b6b, #ff5252);
        }
        
        .logout-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }
        
        .logout-header h2 {
            color: #ff6b6b;
            font-size: 2.2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .logout-message {
            color: #cccccc;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }
        
        .user-info {
            background: rgba(255, 107, 107, 0.1);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border: 1px solid #ff6b6b;
        }
        
        .user-info strong {
            color: #ff6b6b;
        }
        
        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            min-width: 120px;
        }
        
        .btn-logout {
            background: #ff6b6b;
            color: white;
        }
        
        .btn-logout:hover {
            background: #ff5252;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        @media (max-width: 480px) {
            .logout-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
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
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="logout-section">
            <div class="logout-container">
                <div class="logout-icon">🚪</div>
                <div class="logout-header">
                    <h2>Confirm Logout</h2>
                </div>
                
                <div class="user-info">
                    Currently logged in as: <strong><?php echo $_SESSION['username'] ?? 'Unknown User'; ?></strong>
                </div>
                
                <div class="logout-message">
                    Are you sure you want to log out?<br>
                    You will need to sign in again to access your account.
                </div>
                
                <div class="button-group">
                    <a href="logout.php?confirm=true" class="btn btn-logout">Yes, Logout</a>
                    <a href="javascript:history.back()" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </section>
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
                    <a href="" aria-label="Facebook">Facebook</a>
                    <a href="" aria-label="Twitter">Twitter</a>
                    <a href="" aria-label="Instagram">Instagram</a>
                    <a href="" aria-label="LinkedIn">LinkedIn</a>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Football Agent Sierra Leone. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>