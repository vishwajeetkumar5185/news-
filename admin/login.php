<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getConnection();
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        // Direct password comparison (no hash)
        if ($password == $user['password']) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error = "Invalid credentials!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Live 18 India</title>
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(229, 57, 53, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(229, 57, 53, 0.1) 0%, transparent 50%);
        }
        
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        
        .login-left {
            background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .brand-logo {
            position: relative;
            z-index: 2;
            margin-bottom: 30px;
        }
        
        .logo-display {
            display: inline-flex;
            align-items: center;
            font-size: 64px;
            font-weight: 900;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .logo-live {
            background: #000;
            color: #fff;
            padding: 15px 25px;
        }
        
        .logo-18 {
            background: #fff;
            color: #e53935;
            padding: 15px 25px;
        }
        
        .brand-tagline {
            position: relative;
            z-index: 2;
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .brand-description {
            position: relative;
            z-index: 2;
            text-align: center;
            font-size: 16px;
            opacity: 0.95;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .features-list {
            position: relative;
            z-index: 2;
            list-style: none;
            margin-top: 20px;
        }
        
        .features-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 15px;
        }
        
        .features-list li::before {
            content: '✓';
            width: 28px;
            height: 28px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            flex-shrink: 0;
        }
        
        .login-right {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-header {
            margin-bottom: 40px;
        }
        
        .login-header h2 {
            font-size: 32px;
            color: #1a1a1a;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 15px;
        }
        
        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #e53935;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error-message::before {
            content: '⚠️';
            font-size: 20px;
        }
        
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-group label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #999;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: inherit;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #e53935;
            box-shadow: 0 0 0 4px rgba(229, 57, 53, 0.1);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: -10px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #666;
        }
        
        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .forgot-password {
            color: #e53935;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .login-button {
            background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
            color: #fff;
            padding: 16px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(229, 57, 53, 0.3);
            margin-top: 10px;
        }
        
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(229, 57, 53, 0.4);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .back-to-site {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
        }
        
        .back-to-site a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .back-to-site a:hover {
            color: #e53935;
        }
        
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                margin: 20px;
            }
            
            .login-left {
                padding: 40px 30px;
            }
            
            .logo-display {
                font-size: 48px;
            }
            
            .brand-tagline {
                font-size: 20px;
            }
            
            .login-right {
                padding: 40px 30px;
            }
            
            .login-header h2 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div class="brand-logo">
                <div class="logo-display">
                    <span class="logo-live">LIVE</span>
                    <span class="logo-18">18</span>
                </div>
            </div>
            <h1 class="brand-tagline">India's Trusted News</h1>
            <p class="brand-description">
                Welcome to Live 18 India Admin Portal. Manage your news content, videos, and reach millions of viewers across India.
            </p>
            <ul class="features-list">
                <li>24/7 Live News Coverage</li>
                <li>Real-time Content Management</li>
                <li>Multi-platform Publishing</li>
                <li>Analytics & Insights</li>
            </ul>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-header">
                <h2>🔐 Admin Login</h2>
                <p>Enter your credentials to access the dashboard</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="login-button">
                    Login to Dashboard →
                </button>
            </form>
            
            <div class="back-to-site">
                <a href="../index.php">
                    ← Back to Live 18 India
                </a>
            </div>
        </div>
    </div>
</body>
</html>
