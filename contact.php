<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Football Agent Sierra Leone</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="images/logo.png" alt="Football Agent Sierra Leone" class="logo-img">
                <span class="logo-text">Nelson Football Agent SL</span>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="services.php">About</a></li>
                    <li><a href="contact.php" class="active">Contact</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="php/dashboard.php">Dashboard</a></li>
                        <li><a href="php/logout.php">Logout </a></li>
                    <?php else: ?>
                        <li><a href="php/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <!-- Contact Hero Section -->
        <section class="contact-hero">
            <div class="container">
                <div class="contact-hero-wrapper">
                    <div class="contact-hero-content">
                        <h2>Get In Touch</h2>
                        <p>Ready to take your football career to the next level? Contact us today and let's discuss how we can help you achieve your professional football dreams.</p>
                        <div class="contact-cta">
                            <p><strong>We're here to help you succeed</strong></p>
                            <ul>
                                <li>Professional career guidance</li>
                                <li>International opportunities</li>
                                <li>Personalized support</li>
                            </ul>
                        </div>
                    </div>
                    <div class="contact-hero-image">
                        <img src="images/img5.jpg" alt="Football Career Consultation" class="contact-hero-img">
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Information -->
        <section class="contact-info">
            <div class="container">
                <h3 class="section-title">Contact Information</h3>
                <div class="contact-details">
                    <div class="contact-item">
                        <h4>Email</h4>
                        <p><a href="mailto:nelson@footballagent.sl">nelson@footballagent.sl</a></p> 
                    </div>
                    <div class="contact-item">
                        <h4>Phone</h4>
                        <p><a href="tel:+23279826564">+232 79 826-564</a></p>  
                    </div>
                    <div class="contact-item">
                        <h4>Address</h4>
                        <p>15 Goderich Street<br>Freetown, Sierra Leone</p>
                    </div>
                    <div class="contact-item">
                        <h4>Business Hours</h4>
                        <p>Monday - Friday: 9AM - 6PM<br>Saturday: 10AM - 2PM</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Form & Map Section -->
        <section class="contact-form-map">
            <div class="container">
                <div class="form-map-wrapper">
                    <!-- Contact Form -->
                    <div class="contact-form-container">
                        <h3>Send Us a Message</h3>
                        <form class="contact-form" action="php/process_contact.php" method="POST">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <select id="subject" name="subject">
                                    <option value="general">General Inquiry</option>
                                    <option value="player">Player Representation</option>
                                    <option value="career">Career Development</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" name="message" rows="5" required placeholder="Tell us about your football goals and how we can help..."></textarea>
                            </div>
                            
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>">
                            <button type="submit" class="submit-btn">Send Message</button>
                        </form>
                        
                        <?php
                        // Display success or error messages
                        if (isset($_GET['success'])) {
                            echo '<div class="success-message" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-top: 1rem; border: 1px solid #c3e6cb;">';
                            echo '<strong>✓ Message sent successfully!</strong><br>We will get back to you soon.';
                            echo '</div>';
                        } elseif (isset($_GET['error'])) {
                            $error = urldecode($_GET['error']);
                            echo '<div class="error-message" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-top: 1rem; border: 1px solid #f5c6cb;">';
                            echo '<strong>✗ Error:</strong> ' . htmlspecialchars($error);
                            echo '</div>';
                        }
                        ?>
                    </div>

                    <!-- Map -->
                    <div class="map-container">
                        <h3>Find Us</h3>
                        <div class="map-wrapper">
                            <!-- Clickable Map Link -->
                            <a href="https://www.google.com/maps/dir/?api=1&destination=15+Goderich+Street,+Freetown,+Sierra+Leone" 
                               target="_blank" 
                               title="Get Directions to 15 Goderich Street, Freetown">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126930.73730617983!2d-13.2717476!3d8.4589489!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xf01c37f3e8d7397%3A0x9eaf28fd48de18a8!2sGoderich%20Street%2C%20Freetown%2C%20Sierra%20Leone!5e0!3m2!1sen!2ssl!4v1734489300000!5m2!1sen!2ssl"
                                    width="100%"
                                    height="400"
                                    style="border:0; pointer-events:none;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    class="location-map">
                                </iframe>
                            </a>
                    
                            <div class="map-info">
                                <h4>Our Location</h4>
                                <p>📍 15 Goderich Street, Freetown</p>
                                <p>We're located in the heart of Freetown, easily accessible from all major areas.</p>
                                <div class="location-features">
                                    <div class="feature">
                                        <span>🚗</span>
                                        <span>Parking Available</span>
                                    </div>
                                    <div class="feature">
                                        <span>🚌</span>
                                        <span>Near Bus Stops</span>
                                    </div>
                                    <div class="feature">
                                        <span>🕒</span>
                                        <span>Flexible Hours</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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