GROUP 4 BSEM 2101

Football Agent Management System
A comprehensive user management system for a Football Agent website built with PHP and MySQL as part of Assignment 2.

A comprehensive web-based football agency management system built with PHP, MySQL, and modern web technologies. This system manages football players, agents, and club managers with role-based access control.

## 🚀 Features

### Core Functionality
- **User Authentication**: Secure login system with role-based access control
- **Role Management**: Four distinct user roles (Admin, Player, Agent, Club Manager)
- **Profile Management**: Comprehensive user profiles with role-specific information
- **Admin Dashboard**: Complete administrative control over users and system data

### User Roles
- **Admin**: Full system access, user management, dashboard overview
- **Player**: Personal profile, statistics tracking, agent assignment
- **Agent**: Player representation, license management, experience tracking
- **Club Manager**: Club information management, player scouting

### Technical Features
- **Secure Authentication**: Password hashing, session management, CSRF protection
- **Input Validation**: Comprehensive server-side validation and sanitization
- **Database Design**: Normalized relational database with foreign key constraints
- **Responsive Design**: Mobile-friendly interface with modern CSS
- **Error Handling**: Robust error logging and user-friendly error messages

## 📋 System Requirements

### Server Requirements
- **PHP**: Version 7.4 or higher
- **MySQL**: Version 5.7 or higher / MariaDB 10.2+
- **Web Server**: Apache or Nginx with mod_rewrite enabled
- **PHP Extensions**: mysqli, session, filter, json

### Browser Support
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## 🛠️ Installation Guide

### 1. Database Setup

1. **Create Database**
   ```sql
   CREATE DATABASE football_agent_db;
   ```

2. **Import Schema**
   ```bash
   mysql -u root -p football_agent_db < football_agent_db.sql
   ```

3. **Verify Tables**
   - `users` - User accounts and authentication
   - `players` - Player profiles and statistics
   - `agents` - Agent information and licensing
   - `club_managers` - Club management details

### 2. Configuration

1. **Database Connection**
   Edit `php/dbConnect.php`:
   ```php
   $serverName = "localhost";
   $userName = "root";
   $password = "your_password";
   $dbName = "football_agent_db";
   ```

2. **File Permissions**
   ```bash
   chmod 755 php/
   chmod 644 php/*.php
   chmod 755 php/models/
   chmod 644 php/models/*.php
   ```

### 3. Web Server Setup

#### XAMPP Setup
1. Place project in `htdocs/codee/`
2. Start Apache and MySQL services
3. Access via `http://localhost/codee/`

## 👤 Default Accounts

| Role | Username | Password | Access Level |
|------|----------|----------|--------------|
| Admin | admin1 | password | Full system access |
| Admin | admin2 | password | Full system access |
| Player | musa_tombo | password | Player profile |
| Player | kai_kamara | password | Player profile |
| Agent | nelson_agent | password | Agent dashboard |
| Agent | sarah_agent | password | Agent dashboard |
| Club Manager | club_manager1 | password | Club management |
| Club Manager | club_manager2 | password | Club management |

## 📁 Project Structure

```
codee/
├── index.php                 # Homepage
├── contact.php               # Contact page with form
├── services.php              # About and services page
├── styles.css                # Main stylesheet
├── images/                   # Image assets
├── php/
│   ├── login.php            # Login page
│   ├── logout.php           # Logout functionality
│   ├── dashboard.php        # User dashboard
│   ├── profile.php          # User profile management
│   ├── process_contact.php  # Contact form handler
│   ├── dbConnect.php        # Database connection
│   ├── includes/
│   │   ├── auth_check.php   # Authentication middleware
│   │   └── admin_check.php  # Admin access control
│   ├── models/
│   │   ├── UserModel.php    # User data operations
│   │   ├── PlayerModel.php  # Player data operations
│   │   └── UserManager.php  # Role-specific operations
│   └── admin/
│       ├── dashboard.php    # Admin dashboard
│       ├── create_user.php  # User creation
│       ├── users.php        # User management
│       ├── edit_user.php    # User editing
│       └── tables/          # Data display tables
└── football_agent_db.sql    # Database schema
```

## 🔧 Configuration Options

### Security Settings
- **Session Timeout**: 24 hours default
- **Password Requirements**: Minimum 6 characters
- **CSRF Protection**: Enabled on all forms
- **Input Sanitization**: HTML filtering and validation

### Email Configuration
Edit `php/process_contact.php` for email settings:
```php
$to = 'your-email@domain.com';
```

## 🚀 Usage Guide

### For Administrators
1. Login with admin credentials
2. Access admin dashboard via navigation
3. Create new users with appropriate roles
4. Manage existing users (edit/delete)
5. Monitor system statistics

### For Players
1. Login with player credentials
2. View personal dashboard
3. Update profile information
4. Track player statistics

### For Agents
1. Login with agent credentials
2. Manage assigned players
3. Update agent information
4. Track performance metrics

### For Club Managers
1. Login with club manager credentials
2. Manage club information
3. View player profiles
4. Update club details

## 🔒 Security Features

### Implemented Security Measures
- **Password Hashing**: Uses PHP's `password_hash()` with bcrypt
- **SQL Injection Prevention**: Prepared statements for all database queries
- **XSS Protection**: HTML special characters encoding
- **CSRF Protection**: Token-based validation on all forms
- **Input Validation**: Server-side validation and sanitization
- **Session Security**: Secure session configuration

### Recommended Additional Security
- HTTPS implementation
- Rate limiting on login attempts
- Password complexity requirements
- Two-factor authentication
- Regular security audits

## 🐛 Troubleshooting

### Common Issues

#### Database Connection Errors
```
Error: Connection failed: Access denied for user
```
**Solution**: Verify database credentials in `php/dbConnect.php`

#### Session Issues
```
Error: Headers already sent
```
**Solution**: Ensure no output before `session_start()`

#### Permission Errors
```
Error: Permission denied
```
**Solution**: Check file permissions and ownership

#### Email Not Sending
```
Error: Email sending failed
```
**Solution**: Configure mail server settings in PHP.ini

### Debug Mode
Enable error reporting for development:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## 📊 Performance Considerations

### Database Optimization
- Indexed primary and foreign keys
- Optimized queries with proper joins
- Connection pooling for high traffic

### Caching Strategy
- Static asset caching via headers
- Database query result caching
- Session data optimization

## 🔄 Version History

### Version 1.0 (Current)
- Initial release with core functionality
- User authentication and role management
- Admin dashboard and user management
- Contact form with email integration
- Security hardening and validation

## 🤝 Contributing

### Development Guidelines
1. Follow PSR-4 autoloading standards
2. Use prepared statements for all database queries
3. Implement proper error handling
4. Add comprehensive documentation
5. Test all functionality before deployment

### Code Style
- Use 4 spaces for indentation
- Follow PHP naming conventions
- Add PHPDoc comments to all functions
- Validate all user inputs

## 📞 Support

### Technical Support
- **Email**: nelson@footballagent.sl
- **Phone**: +232 79 826-564
- **Address**: 15 Goderich Street, Freetown, Sierra Leone

### Documentation
- **User Manual**: Available in admin dashboard
- **API Documentation**: Included in model files
- **Database Schema**: See `football_agent_db.sql`

## 📄 License

This project is proprietary software developed for Football Agent Sierra Leone. All rights reserved.

---

**Developed by**: Football Agent SL Team  
**Last Updated**: December 2025  
**Version**: 1.0.0

---

## 🎯 Assignment Requirements Completed
✅ User Roles & Database
4 User Roles: Admin, Player, Agent, Club Manager

Database Design: Proper table structure with relationships

Sample Data: 2 users per role (8 total users)

✅ CRUD Operations
Create: Add new users and player profiles through admin panel

Read: View all users with filtering and player management

Update: Edit user information and player profiles

Delete: Remove users and player profiles with confirmation

✅ Technical Features
Secure Authentication with session management

Role-based Access Control

Password Hashing for security

Input Validation and error handling

Responsive Design matching website theme

Database Export functionality for assignment submission

🚀 Quick Start Guide
Prerequisites
XAMPP/WAMP/MAMP installed

PHP 7.4+

MySQL/MariaDB

Web browser

Installation Steps
Setup Database

bash
# Import the database (if you have SQL file)
# Or run the setup script
http://localhost/your-project/php/setup_database.php
Access the System

Main URL: http://localhost/your-project/

Login URL: http://localhost/your-project/php/login.php

Demo Login Credentials

text
All users use password: 'password'

Admin: 
- admin1 / password
- admin2 / password

Player:
- musa_tombo / password  
- kai_kamara / password

Agent:
- nelson_agent / password
- sarah_agent / password

Club Manager:
- club_manager1 / password
- club_manager2 / password
📁 CORRECT Project Structure
text
football_agent_system/
├── index.html                 # Homepage
├── services.html              # About/Services page
├── contact.html               # Contact page
├── styles.css                 # Main stylesheet
│
├── php/                       # ALL PHP FILES GO HERE
│   ├── login.php              # User login
│   ├── logout.php             # Logout script
│   ├── register.php           # Player registration
│   ├── connect.php            # Database connection
│   ├── dashboard.php          # User dashboard
│   ├── profile.php            # User profile management
│   │
│   ├── admin/                 # Admin panel
│   │   ├── dashboard.php      # Admin dashboard
│   │   ├── users.php          # User management
│   │   ├── create_user.php    # Create new user
│   │   ├── edit_user.php      # Edit user
│   │   ├── players.php        # Player management
│   │   ├── create_player_profile.php  # Create player profile
│   │   ├── edit_player.php    # Edit player profile
│   │   
│   │
│   ├── includes/              # Authentication & security
│   │   ├── auth_check.php     # Authentication check
│   │   └── admin_check.php    # Admin role check
│   │
│   ├── models/                # Database models
│   │   ├── UserModel.php      # User CRUD operations
│   │   └── PlayerModel.php    # Player profile management
│   │
│   └── tables/                # Database table structures
│       ├── users_table.php
│       ├── players_table.php
│       ├── agents_table.php
│       └── club_managers_table.php
│
├── images/                    # Website images
│   ├── logo.png
│   ├── img1.jpg, img2.jpg, etc.
│   └── p1.jpg, p2.jpg, p3.jpg (player images)
│
└── README.md                  # This file
👥 User Roles & Permissions
🛠️ Admin
Full system access and user management

Create, read, update, delete all users

Player profile management

Database export functionality

System statistics and overview

⚽ Player
Personal dashboard with statistics

Profile viewing and basic editing

Player registration system

View assigned agent information

🤝 Agent
Player management capabilities

Client portfolio overview

Contract negotiation tools

Player statistics tracking

🏢 Club Manager
Team management interface

Player scouting features

Contract offer management

Club information management

🔧 Technical Implementation
Database Schema
sql
-- Main tables structure
users (id, username, email, password, role, first_name, last_name, phone, address, ...)
players (id, user_id, position, height, weight, current_club, agent_id, stats, video_url, ...)
agents (id, user_id, license_number, years_experience, ...)
club_managers (id, user_id, club_name, club_location, ...)
Security Features
Password Hashing: Using password_hash() and password_verify()

SQL Injection Prevention: Prepared statements with MySQLi

XSS Protection: Input sanitization and output escaping

Session Management: Secure session handling with role checks

Role-based Access: Different dashboards and permissions per role

Key PHP Features
Object-oriented programming with Models

MVC-like architecture

Form validation and error handling

File upload handling (for future expansion)

Responsive design with CSS Grid/Flexbox

🎮 How to Use the System
For Admins:
Login at php/login.php

Access Admin Dashboard from user dashboard

Manage Users through the user management interface

Create Player Profiles for player users

Export Database for assignment submission

For Players:
Register at php/register.php or login with existing account

Access Dashboard to view personal information

Update Profile with contact information

View Player Stats and assigned agent

Important URLs:
Login: http://localhost/your-project/php/login.php

User Dashboard: http://localhost/your-project/php/dashboard.php

Admin Panel: http://localhost/your-project/php/admin/dashboard.php

Player Management: http://localhost/your-project/php/admin/players.php

For Assignment Submission:
Login as Admin and go to Admin Dashboard

Click "Export Database" to download SQL file

Include in submission:

All source code files

Database export SQL file

Screenshots of working system

This README file

📊 Database Features
Sample Data Included
2 Admin users

2 Player users with detailed profiles

2 Agent users

2 Club Manager users

Player Statistics Tracking
Goals, assists, matches played

Yellow/red cards

Physical attributes (height, weight)

Position and preferred foot

Video highlights URLs

Agent assignments

🔄 CRUD Operations Demo
User Management (Admin)
✅ Create: Add new users with different roles

✅ Read: View all users in table format

✅ Update: Edit user information and roles

✅ Delete: Remove users with confirmation

Player Profile Management
✅ Create: Add detailed football profiles to players

✅ Read: View players in table or card view

✅ Update: Edit player stats and information

✅ Delete: Remove player profiles

🛡️ Security Measures
Authentication System

Session-based login

Password hashing

Automatic logout

Authorization

Role-based access control

Admin-only areas protected

User-specific data isolation

Input Validation

Form data sanitization

SQL injection prevention

XSS protection

Error Handling

User-friendly error messages

No sensitive data exposure

Proper logging (for production)

🌐 Browser Compatibility
✅ Chrome 90+

✅ Firefox 88+

✅ Safari 14+

✅ Edge 90+

✅ Mobile responsive design

📝 Assignment Submission Checklist
All source code files included

Database export SQL file generated

System works with demo accounts

CRUD operations functional

Role-based access working

No errors or warnings

Documentation complete

🐛 Troubleshooting
Common Issues:
Database Connection Error

Check XAMPP/WAMP is running

Verify database credentials in php/connect.php

Login Not Working

Ensure using correct demo credentials

Check session is enabled in PHP

Access login via php/login.php

Page Not Found

Verify file paths and URLs

All PHP files should be in php/ folder

Permission Errors

Ensure file permissions are correct

Check database user privileges

Important Notes:
All PHP files are in the php/ folder

Access login via php/login.php not root login.php

HTML files remain in root directory

📞 Contact & Support
Football Agent Sierra Leone
📍 15 Goderich Street, Freetown, Sierra Leone
📧 nelson@footballagent.sl
📞 +232 79 826-564

Developed for Educational Purposes
© 2025 Football Agent Sierra Leone - Assignment 2 Submission
