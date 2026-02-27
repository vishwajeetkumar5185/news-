<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';
$step = $_GET['step'] ?? 'login'; // login, create_pin, enter_pin, forgot_password, reset_password, forgot_pin, reset_pin
$user_id = $_SESSION['temp_user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getConnection();
    
    if ($step == 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        if (empty($username)) {
            $error = "❌ Please enter your username!";
        } elseif (empty($password)) {
            $error = "❌ Please enter your password!";
        } else {
        
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if ($password == $user['password']) {
                $_SESSION['temp_user_id'] = $user['id'];
                $_SESSION['temp_username'] = $user['username'];
                
                // Check if user has PIN
                if (empty($user['pin'])) {
                    // First time login - redirect to create PIN
                    header('Location: login.php?step=create_pin');
                    exit;
                } else {
                    // User has PIN - redirect to enter PIN
                    header('Location: login.php?step=enter_pin');
                    exit;
                }
            } else {
                $error = "❌ Wrong password! Please check your password and try again.";
            }
        } else {
            $error = "❌ Username not found! Please check your username and try again.";
        }
        }
        
    } elseif ($step == 'create_pin') {
        $pin = trim($_POST['pin'] ?? '');
        $confirm_pin = trim($_POST['confirm_pin'] ?? '');
        
        if (empty($pin)) {
            $error = "❌ Please enter a PIN!";
        } elseif (empty($confirm_pin)) {
            $error = "❌ Please confirm your PIN!";
        } elseif (strlen($pin) != 4 || !ctype_digit($pin)) {
            $error = "❌ PIN must be exactly 4 numbers only! Please enter 4 digits.";
        } elseif ($pin != $confirm_pin) {
            $error = "❌ PINs do not match! Please enter the same PIN in both fields.";
        } else {
            // Save PIN and login
            $stmt = $conn->prepare("UPDATE admin_users SET pin = ?, pin_created_at = NOW(), last_pin_login = NOW() WHERE id = ?");
            $stmt->bind_param("si", $pin, $_SESSION['temp_user_id']);
            
            if ($stmt->execute()) {
                $_SESSION['admin_id'] = $_SESSION['temp_user_id'];
                $_SESSION['admin_username'] = $_SESSION['temp_username'];
                unset($_SESSION['temp_user_id'], $_SESSION['temp_username']);
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "❌ Error creating PIN! Please try again.";
            }
        }
        
    } elseif ($step == 'enter_pin') {
        $pin = trim($_POST['pin'] ?? '');
        
        if (empty($pin)) {
            $error = "❌ Please enter your PIN!";
        } elseif (strlen($pin) != 4 || !ctype_digit($pin)) {
            $error = "❌ PIN must be exactly 4 numbers only! Please enter 4 digits.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM admin_users WHERE id = ? AND pin = ?");
            $stmt->bind_param("is", $_SESSION['temp_user_id'], $pin);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->fetch_assoc()) {
                // Update last PIN login
                $stmt = $conn->prepare("UPDATE admin_users SET last_pin_login = NOW() WHERE id = ?");
                $stmt->bind_param("i", $_SESSION['temp_user_id']);
                $stmt->execute();
                
                $_SESSION['admin_id'] = $_SESSION['temp_user_id'];
                $_SESSION['admin_username'] = $_SESSION['temp_username'];
                unset($_SESSION['temp_user_id'], $_SESSION['temp_username']);
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "❌ Wrong PIN! Please check your PIN and try again.";
            }
        }
        
    } elseif ($step == 'forgot_password') {
        $username = trim($_POST['username'] ?? '');
        $pin = trim($_POST['pin'] ?? '');
        
        if (empty($username)) {
            $error = "❌ Please enter your username!";
        } elseif (empty($pin)) {
            $error = "❌ Please enter your PIN!";
        } elseif (strlen($pin) != 4 || !ctype_digit($pin)) {
            $error = "❌ PIN must be exactly 4 numbers only! Please enter 4 digits.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND pin = ?");
            $stmt->bind_param("ss", $username, $pin);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($user = $result->fetch_assoc()) {
                $_SESSION['temp_user_id'] = $user['id'];
                $_SESSION['temp_username'] = $user['username'];
                header('Location: login.php?step=reset_password');
                exit;
            } else {
                $error = "❌ Wrong username or PIN! Please check your details and try again.";
            }
        }
        
    } elseif ($step == 'reset_password') {
        $new_password = trim($_POST['new_password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');
        
        if (empty($new_password)) {
            $error = "❌ Please enter a new password!";
        } elseif (empty($confirm_password)) {
            $error = "❌ Please confirm your new password!";
        } elseif (strlen($new_password) < 6) {
            $error = "❌ Password must be at least 6 characters long! Please enter a longer password.";
        } elseif ($new_password != $confirm_password) {
            $error = "❌ Passwords do not match! Please enter the same password in both fields.";
        } else {
            $stmt = $conn->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_password, $_SESSION['temp_user_id']);
            
            if ($stmt->execute()) {
                $_SESSION['admin_id'] = $_SESSION['temp_user_id'];
                $_SESSION['admin_username'] = $_SESSION['temp_username'];
                unset($_SESSION['temp_user_id'], $_SESSION['temp_username']);
                $success = "Password updated successfully! You are now logged in.";
                header('refresh:2;url=dashboard.php');
            } else {
                $error = "❌ Error updating password! Please try again.";
            }
        }
        
    } elseif ($step == 'forgot_pin') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        if (empty($username)) {
            $error = "❌ Please enter your username!";
        } elseif (empty($password)) {
            $error = "❌ Please enter your password!";
        } else {
        
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            $_SESSION['temp_user_id'] = $user['id'];
            $_SESSION['temp_username'] = $user['username'];
            header('Location: login.php?step=reset_pin');
            exit;
        } else {
            $error = "❌ Wrong username or password! Please check your details and try again.";
        }
        }
        
    } elseif ($step == 'reset_pin') {
        $new_pin = trim($_POST['new_pin'] ?? '');
        $confirm_pin = trim($_POST['confirm_pin'] ?? '');
        
        if (empty($new_pin)) {
            $error = "❌ Please enter a new PIN!";
        } elseif (empty($confirm_pin)) {
            $error = "❌ Please confirm your new PIN!";
        } elseif (strlen($new_pin) != 4 || !ctype_digit($new_pin)) {
            $error = "❌ PIN must be exactly 4 numbers only! Please enter 4 digits.";
        } elseif ($new_pin != $confirm_pin) {
            $error = "❌ PINs do not match! Please enter the same PIN in both fields.";
        } else {
            $stmt = $conn->prepare("UPDATE admin_users SET pin = ?, pin_created_at = NOW() WHERE id = ?");
            $stmt->bind_param("si", $new_pin, $_SESSION['temp_user_id']);
            
            if ($stmt->execute()) {
                $_SESSION['admin_id'] = $_SESSION['temp_user_id'];
                $_SESSION['admin_username'] = $_SESSION['temp_username'];
                unset($_SESSION['temp_user_id'], $_SESSION['temp_username']);
                $success = "PIN updated successfully! You are now logged in.";
                header('refresh:2;url=dashboard.php');
            } else {
                $error = "❌ Error updating PIN! Please try again.";
            }
        }
    }
}

// Get user info for PIN steps
if (in_array($step, ['create_pin', 'enter_pin', 'reset_password', 'reset_pin']) && $user_id) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT username FROM admin_users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_info = $result->fetch_assoc();
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
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
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
        
        .forgot-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
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
        
        /* ===== RESPONSIVE DESIGN FOR ALL DEVICES ===== */
        
        /* Large Desktops (1440px+) */
        @media (min-width: 1440px) {
            .login-container {
                max-width: 1200px;
            }
            
            .login-left {
                padding: 80px 60px;
            }
            
            .login-right {
                padding: 80px 70px;
            }
            
            .logo-display {
                font-size: 72px;
            }
            
            .brand-tagline {
                font-size: 28px;
            }
            
            .login-header h2 {
                font-size: 36px;
            }
        }
        
        /* Standard Desktops (1200px - 1439px) */
        @media (min-width: 1200px) and (max-width: 1439px) {
            .login-container {
                max-width: 1000px;
                margin: 20px;
            }
            
            .login-left {
                padding: 60px 45px;
            }
            
            .login-right {
                padding: 60px 50px;
            }
        }
        
        /* Small Desktops & Large Laptops (992px - 1199px) */
        @media (min-width: 992px) and (max-width: 1199px) {
            .login-container {
                max-width: 900px;
                margin: 20px;
            }
            
            .login-left {
                padding: 50px 40px;
            }
            
            .login-right {
                padding: 50px 45px;
            }
            
            .logo-display {
                font-size: 58px;
            }
            
            .brand-tagline {
                font-size: 22px;
            }
            
            .login-header h2 {
                font-size: 30px;
            }
        }
        
        /* Standard Laptops (768px - 991px) */
        @media (min-width: 768px) and (max-width: 991px) {
            body {
                padding: 15px;
            }
            
            .login-container {
                max-width: 750px;
                margin: 0;
                border-radius: 16px;
            }
            
            .login-left {
                padding: 45px 35px;
            }
            
            .login-right {
                padding: 45px 40px;
            }
            
            .logo-display {
                font-size: 52px;
            }
            
            .brand-tagline {
                font-size: 20px;
            }
            
            .login-header h2 {
                font-size: 28px;
            }
        }
        
        /* Large Tablets Portrait (600px - 767px) */
        @media (min-width: 600px) and (max-width: 767px) {
            body {
                padding: 10px;
                align-items: center;
                padding-top: 20px;
            }
            
            .login-container {
                grid-template-columns: 1fr;
                max-width: 500px;
                border-radius: 15px;
            }
            
            .login-left {
                display: none; /* Hide branding section on tablets */
            }
            
            .login-right {
                padding: 40px 30px;
            }
            
            .login-header {
                margin-bottom: 35px;
                text-align: center;
            }
            
            .login-header h2 {
                font-size: 26px;
            }
            
            .form-group input {
                font-size: 16px;
            }
        }
        
        /* Standard Mobile Phones (480px - 599px) */
        @media (min-width: 480px) and (max-width: 599px) {
            body {
                padding: 15px;
                align-items: center;
            }
            
            .login-container {
                grid-template-columns: 1fr;
                max-width: 450px;
                border-radius: 12px;
            }
            
            .login-left {
                display: none; /* Hide branding section on mobile */
            }
            
            .login-right {
                padding: 35px 25px;
            }
            
            .login-header {
                text-align: center;
                margin-bottom: 30px;
            }
            
            .login-header h2 {
                font-size: 24px;
            }
            
            .form-group input {
                padding: 14px 14px 14px 45px;
                font-size: 16px;
            }
            
            .input-icon {
                font-size: 17px;
                left: 13px;
            }
            
            .remember-forgot {
                flex-direction: column;
                gap: 12px;
                align-items: center;
            }
            
            .forgot-links {
                align-items: center;
                gap: 6px;
            }
            
            .login-button {
                padding: 16px;
                font-size: 16px;
            }
        }
        
        /* Large Mobile Phones (414px - 479px) */
        @media (min-width: 414px) and (max-width: 479px) {
            body {
                padding: 15px;
                align-items: center;
            }
            
            .login-container {
                grid-template-columns: 1fr;
                max-width: 400px;
                border-radius: 12px;
            }
            
            .login-left {
                display: none; /* Hide branding section */
            }
            
            .login-right {
                padding: 30px 25px;
            }
            
            .login-header {
                text-align: center;
                margin-bottom: 25px;
            }
            
            .login-header h2 {
                font-size: 22px;
            }
            
            .form-group input {
                padding: 14px 14px 14px 42px;
                font-size: 16px;
            }
            
            .input-icon {
                font-size: 16px;
                left: 12px;
            }
            
            .remember-forgot {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
            
            .forgot-links {
                align-items: center;
                gap: 6px;
            }
            
            .login-button {
                padding: 16px;
                font-size: 15px;
                border-radius: 8px;
            }
        }
        
        /* Standard Mobile Phones (375px - 413px) */
        @media (min-width: 375px) and (max-width: 413px) {
            body {
                padding: 15px;
                align-items: center;
            }
            
            .login-container {
                grid-template-columns: 1fr;
                max-width: 380px;
                border-radius: 12px;
            }
            
            .login-left {
                display: none; /* Hide branding section */
            }
            
            .login-right {
                padding: 30px 25px;
            }
            
            .login-header {
                text-align: center;
                margin-bottom: 25px;
            }
            
            .login-header h2 {
                font-size: 21px;
            }
            
            .login-header p {
                font-size: 13px;
            }
            
            .form-group input {
                padding: 12px 12px 12px 40px;
                font-size: 16px;
            }
            
            .input-icon {
                font-size: 15px;
                left: 11px;
            }
            
            .remember-forgot {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
            
            .forgot-links {
                align-items: center;
                gap: 6px;
            }
            
            .remember-me {
                font-size: 13px;
            }
            
            .forgot-password {
                font-size: 13px;
            }
            
            .login-button {
                padding: 16px;
                font-size: 15px;
            }
            
            .back-to-site a {
                font-size: 13px;
            }
        }
        
        /* Small Mobile Phones (360px - 374px) */
        @media (min-width: 360px) and (max-width: 374px) {
            body {
                padding: 15px;
                align-items: center;
            }
            
            .login-container {
                grid-template-columns: 1fr;
                max-width: 350px;
                border-radius: 10px;
            }
            
            .login-left {
                display: none; /* Hide branding section */
            }
            
            .login-right {
                padding: 25px 20px;
            }
            
            .login-header {
                text-align: center;
                margin-bottom: 20px;
            }
            
            .login-header h2 {
                font-size: 20px;
            }
            
            .login-header p {
                font-size: 12px;
            }
            
            .form-group input {
                padding: 11px 11px 11px 38px;
                font-size: 16px;
            }
            
            .input-icon {
                left: 10px;
                font-size: 14px;
            }
            
            .remember-forgot {
                flex-direction: column;
                gap: 8px;
                align-items: center;
            }
            
            .remember-me {
                font-size: 12px;
            }
            
            .forgot-password {
                font-size: 12px;
            }
            
            .login-button {
                padding: 14px;
                font-size: 14px;
            }
            
            .back-to-site a {
                font-size: 12px;
            }
        }
        
        /* Very Small Mobile Phones (320px - 359px) */
        @media (min-width: 320px) and (max-width: 359px) {
            body {
                padding: 10px;
                align-items: center;
            }
            
            .login-container {
                grid-template-columns: 1fr;
                max-width: 320px;
                border-radius: 10px;
            }
            
            .login-left {
                display: none; /* Hide branding section */
            }
            
            .login-right {
                padding: 25px 18px;
            }
            
            .login-header {
                text-align: center;
                margin-bottom: 20px;
            }
            
            .login-header h2 {
                font-size: 19px;
            }
            
            .login-header p {
                font-size: 12px;
            }
            
            .form-group {
                gap: 6px;
            }
            
            .form-group label {
                font-size: 12px;
            }
            
            .form-group input {
                padding: 10px 10px 10px 36px;
                font-size: 16px;
            }
            
            .input-icon {
                left: 9px;
                font-size: 13px;
            }
            
            .remember-forgot {
                flex-direction: column;
                gap: 8px;
                align-items: center;
            }
            
            .remember-me {
                font-size: 12px;
            }
            
            .forgot-password {
                font-size: 12px;
            }
            
            .login-button {
                padding: 14px;
                font-size: 14px;
            }
            
            .back-to-site {
                margin-top: 15px;
                padding-top: 15px;
            }
            
            .back-to-site a {
                font-size: 12px;
            }
        }
        
        /* Ultra Small Screens (below 320px) */
        @media (max-width: 319px) {
            body {
                padding: 8px;
                align-items: center;
            }
            
            .login-container {
                grid-template-columns: 1fr;
                max-width: 300px;
                border-radius: 8px;
            }
            
            .login-left {
                display: none; /* Hide branding section */
            }
            
            .login-right {
                padding: 20px 15px;
            }
            
            .login-header {
                text-align: center;
                margin-bottom: 18px;
            }
            
            .login-header h2 {
                font-size: 18px;
            }
            
            .login-header p {
                font-size: 11px;
            }
            
            .form-group {
                gap: 5px;
            }
            
            .form-group label {
                font-size: 11px;
            }
            
            .form-group input {
                padding: 9px 9px 9px 34px;
                font-size: 16px;
            }
            
            .input-icon {
                left: 8px;
                font-size: 12px;
            }
            
            .remember-forgot {
                flex-direction: column;
                gap: 6px;
                align-items: center;
            }
            
            .remember-me {
                font-size: 11px;
            }
            
            .forgot-password {
                font-size: 11px;
            }
            
            .login-button {
                padding: 12px;
                font-size: 13px;
            }
            
            .back-to-site a {
                font-size: 11px;
            }
        }
        
        /* Landscape Mode for Mobile Phones */
        @media (max-width: 767px) and (orientation: landscape) {
            body {
                padding: 10px;
                align-items: center;
            }
            
            .login-container {
                grid-template-columns: 1fr;
                max-width: 500px;
                max-height: none;
            }
            
            .login-left {
                display: none; /* Hide branding section in landscape */
            }
            
            .login-right {
                padding: 20px 25px;
            }
            
            .login-header {
                margin-bottom: 20px;
                text-align: center;
            }
            
            .login-header h2 {
                font-size: 20px;
            }
            
            .login-header p {
                font-size: 12px;
            }
            
            .form-group {
                gap: 6px;
            }
            
            .form-group input {
                padding: 12px 12px 12px 40px;
            }
            
            .remember-forgot {
                flex-direction: row;
                justify-content: space-between;
                gap: 10px;
            }
            
            .login-button {
                padding: 14px;
                margin-top: 10px;
            }
            
            .back-to-site {
                margin-top: 15px;
                padding-top: 15px;
            }
        }
        
        /* High DPI Displays */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .login-container {
                box-shadow: 0 25px 70px rgba(0,0,0,0.5);
            }
            
            .login-button {
                box-shadow: 0 6px 25px rgba(229, 57, 53, 0.4);
            }
        }
        
        /* Reduced Motion for Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            
            .login-left::before {
                animation: none;
            }
            
            .login-button:hover {
                transform: none;
            }
        }
        
        /* Focus styles for better accessibility */
        .login-button:focus,
        .form-group input:focus,
        .forgot-password:focus,
        .back-to-site a:focus {
            outline: 2px solid #e53935;
            outline-offset: 2px;
        }
        
        /* PIN Modal Styles */
        .pin-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            align-items: center;
            justify-content: center;
        }
        
        .pin-modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 400px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        
        .pin-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .pin-modal-header h3 {
            color: #1a1a1a;
            font-size: 20px;
            margin: 0;
        }
        
        .pin-close {
            color: #999;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .pin-close:hover {
            color: #e53935;
        }
        
        .pin-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .pin-forgot {
            text-align: center;
            margin-top: 15px;
        }
        
        .pin-forgot a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        
        .pin-forgot a:hover {
            color: #e53935;
            text-decoration: underline;
        }
        
        /* PIN Input Styling */
        input[name="pin"], input[name="confirm_pin"] {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 8px;
        }
        
        /* Success Message */
        .success-message {
            background: #e8f5e8;
            color: #2e7d32;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #4caf50;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .success-message::before {
            content: '✅';
            font-size: 20px;
        }
        
        /* Mobile PIN Modal */
        @media (max-width: 480px) {
            .pin-modal-content {
                padding: 25px 20px;
                margin: 20px;
                max-width: none;
                width: calc(100% - 40px);
            }
            
            .pin-modal-header h3 {
                font-size: 18px;
            }
            
            .pin-close {
                font-size: 24px;
            }
        }
        
        /* Print styles */
        @media print {
            body {
                background: white;
            }
            
            .login-container {
                box-shadow: none;
                border: 1px solid #ccc;
            }
            
            .login-left {
                background: #f5f5f5;
                color: #333;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding (Hidden on mobile) -->
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
        
        <!-- Right Side - Login Forms -->
        <div class="login-right">
            
            <?php if ($step == 'login'): ?>
            <!-- Regular Login Form -->
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
                    <div class="forgot-links">
                        <a href="javascript:void(0)" onclick="showForgotPassword()" class="forgot-password">Forgot Password?</a>
                        <a href="javascript:void(0)" onclick="showForgotPin()" class="forgot-password">Forgot PIN?</a>
                    </div>
                </div>
                
                <button type="submit" class="login-button">
                    Login to Dashboard →
                </button>
            </form>
            
            <?php elseif ($step == 'create_pin'): ?>
            <!-- Create PIN Form -->
            <div class="login-header">
                <h2>🔢 Create Your PIN</h2>
                <p>Welcome <strong><?php echo htmlspecialchars($user_info['username'] ?? ''); ?></strong>! Create a 4-digit PIN for quick access</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="pin">Create 4-Digit PIN</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔢</span>
                        <input type="password" id="pin" name="pin" placeholder="Enter 4-digit PIN" maxlength="4" pattern="[0-9]{4}" required autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_pin">Confirm PIN</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✅</span>
                        <input type="password" id="confirm_pin" name="confirm_pin" placeholder="Confirm your PIN" maxlength="4" pattern="[0-9]{4}" required>
                    </div>
                </div>
                
                <button type="submit" class="login-button">
                    Create PIN & Login →
                </button>
            </form>
            
            <?php elseif ($step == 'enter_pin'): ?>
            <!-- Enter PIN Form -->
            <div class="login-header">
                <h2>🔢 Enter Your PIN</h2>
                <p>Welcome back <strong><?php echo htmlspecialchars($user_info['username'] ?? ''); ?></strong>! Enter your 4-digit PIN</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="pin">Enter Your PIN</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔢</span>
                        <input type="password" id="pin" name="pin" placeholder="Enter 4-digit PIN" maxlength="4" pattern="[0-9]{4}" required autofocus>
                    </div>
                </div>
                
                <div class="remember-forgot">
                    <a href="login.php" class="forgot-password">← Back to Login</a>
                </div>
                
                <button type="submit" class="login-button">
                    Login with PIN →
                </button>
            </form>
            
            <?php elseif ($step == 'reset_password'): ?>
            <!-- Reset Password Form -->
            <div class="login-header">
                <h2>🔑 Create New Password</h2>
                <p>Hello <strong><?php echo htmlspecialchars($user_info['username'] ?? ''); ?></strong>! Create your new password</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔑</span>
                        <input type="password" id="new_password" name="new_password" placeholder="Enter new password" minlength="6" required autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✅</span>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" minlength="6" required>
                    </div>
                </div>
                
                <button type="submit" class="login-button">
                    Update Password & Login →
                </button>
            </form>
            
            <?php elseif ($step == 'reset_pin'): ?>
            <!-- Reset PIN Form -->
            <div class="login-header">
                <h2>🔢 Create New PIN</h2>
                <p>Hello <strong><?php echo htmlspecialchars($user_info['username'] ?? ''); ?></strong>! Create your new 4-digit PIN</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="new_pin">New 4-Digit PIN</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔢</span>
                        <input type="password" id="new_pin" name="new_pin" placeholder="Enter new PIN" maxlength="4" pattern="[0-9]{4}" required autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_pin">Confirm PIN</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✅</span>
                        <input type="password" id="confirm_pin" name="confirm_pin" placeholder="Confirm new PIN" maxlength="4" pattern="[0-9]{4}" required>
                    </div>
                </div>
                
                <button type="submit" class="login-button">
                    Update PIN & Login →
                </button>
            </form>
            
            <?php elseif ($step == 'forgot_password'): ?>
            <!-- Forgot Password Form -->
            <div class="login-header">
                <h2>🔑 Forgot Password</h2>
                <p>Enter your username and PIN to reset your password</p>
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
                    <label for="pin">Your 4-Digit PIN</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔢</span>
                        <input type="password" id="pin" name="pin" placeholder="Enter your PIN" maxlength="4" pattern="[0-9]{4}" required>
                    </div>
                </div>
                
                <button type="submit" class="login-button">
                    Reset Password →
                </button>
                
                <div class="back-to-site" style="margin-top: 20px; text-align: center;">
                    <a href="login.php" style="color: #666; text-decoration: none; font-size: 14px;">
                        ← Back to Login
                    </a>
                </div>
            </form>
            
            <?php elseif ($step == 'forgot_pin'): ?>
            <!-- Forgot PIN Form -->
            <div class="login-header">
                <h2>🔢 Forgot PIN</h2>
                <p>Enter your username and password to reset your PIN</p>
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
                
                <button type="submit" class="login-button">
                    Reset PIN →
                </button>
                
                <div class="back-to-site" style="margin-top: 20px; text-align: center;">
                    <a href="login.php" style="color: #666; text-decoration: none; font-size: 14px;">
                        ← Back to Login
                    </a>
                </div>
            </form>
            
            <?php endif; ?>
            
            <div class="back-to-site">
                <a href="../index.php">
                    ← Back to Live 18 India
                </a>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="pin-modal" style="display: none;">
        <div class="pin-modal-content">
            <div class="pin-modal-header">
                <h3>🔑 Forgot Password</h3>
                <span class="pin-close" onclick="closeForgotPassword()">&times;</span>
            </div>
            
            <p style="margin-bottom: 20px; color: #666; text-align: center;">Enter your username and PIN to reset your password</p>
            
            <form method="POST" action="login.php?step=forgot_password" class="pin-form">
                <div class="form-group">
                    <label for="forgot_username">Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="forgot_username" name="username" placeholder="Enter username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="forgot_pin">Your 4-Digit PIN</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔢</span>
                        <input type="password" id="forgot_pin" name="pin" placeholder="Enter your PIN" maxlength="4" pattern="[0-9]{4}" required>
                    </div>
                </div>
                
                <button type="submit" class="login-button">
                    Reset Password →
                </button>
                
                <div class="pin-forgot">
                    <a href="javascript:void(0)" onclick="closeForgotPassword()">Back to Login</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Forgot PIN Modal -->
    <div id="forgotPinModal" class="pin-modal" style="display: none;">
        <div class="pin-modal-content">
            <div class="pin-modal-header">
                <h3>🔢 Forgot PIN</h3>
                <span class="pin-close" onclick="closeForgotPin()">&times;</span>
            </div>
            
            <p style="margin-bottom: 20px; color: #666; text-align: center;">Enter your username and password to reset your PIN</p>
            
            <form method="POST" action="login.php?step=forgot_pin" class="pin-form">
                <div class="form-group">
                    <label for="pin_reset_username">Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="pin_reset_username" name="username" placeholder="Enter username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="pin_reset_password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="pin_reset_password" name="password" placeholder="Enter password" required>
                    </div>
                </div>
                
                <button type="submit" class="login-button">
                    Reset PIN →
                </button>
                
                <div class="pin-forgot">
                    <a href="javascript:void(0)" onclick="closeForgotPin()">Back to Login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showForgotPassword() {
            window.location.href = 'login.php?step=forgot_password';
        }
        
        function closeForgotPassword() {
            document.getElementById('forgotPasswordModal').style.display = 'none';
        }
        
        function showForgotPin() {
            window.location.href = 'login.php?step=forgot_pin';
        }
        
        function closeForgotPin() {
            document.getElementById('forgotPinModal').style.display = 'none';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const modals = ['forgotPasswordModal', 'forgotPinModal'];
            modals.forEach(function(modalId) {
                const modal = document.getElementById(modalId);
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        }
        
        // Auto-format PIN inputs to numbers only
        document.addEventListener('DOMContentLoaded', function() {
            const pinInputs = document.querySelectorAll('input[name="pin"], input[name="confirm_pin"], input[name="new_pin"]');
            pinInputs.forEach(function(input) {
                input.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                });
            });
            
            // Add form validation
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const inputs = form.querySelectorAll('input[required]');
                    let hasError = false;
                    
                    inputs.forEach(function(input) {
                        if (!input.value.trim()) {
                            hasError = true;
                            input.style.borderColor = '#e53935';
                            input.placeholder = 'This field is required!';
                        } else {
                            input.style.borderColor = '#e0e0e0';
                        }
                        
                        // PIN specific validation
                        if (input.name === 'pin' || input.name === 'confirm_pin' || input.name === 'new_pin') {
                            if (input.value && (input.value.length !== 4 || !/^\d{4}$/.test(input.value))) {
                                hasError = true;
                                input.style.borderColor = '#e53935';
                                input.value = '';
                                input.placeholder = 'Enter exactly 4 numbers!';
                            }
                        }
                        
                        // Password specific validation
                        if (input.name === 'new_password' || input.name === 'confirm_password') {
                            if (input.value && input.value.length < 6) {
                                hasError = true;
                                input.style.borderColor = '#e53935';
                                input.placeholder = 'Password must be 6+ characters!';
                            }
                        }
                    });
                    
                    if (hasError) {
                        e.preventDefault();
                        return false;
                    }
                });
            });
        });
    </script>
</body>
</html>