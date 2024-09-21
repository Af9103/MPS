<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Forbidden</title>
    <link href="../asset/img/k-logo.jpg" rel="icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .icon-lock {
            font-size: 48px;
            color: #007bff;
        }
    </style>
</head>

<?php
session_start(); // Ensure the session is started

// Clear specific session variables
$_SESSION['id'] = '';
$_SESSION['npk'] = '';
$_SESSION['password'] = '';
$_SESSION['name'] = '';
$_SESSION['level'] = '';

// Unset specific session variables
unset($_SESSION['id']);
unset($_SESSION['npk']);
unset($_SESSION['password']);
unset($_SESSION['name']);
unset($_SESSION['level']);

// Optionally, destroy the entire session if you want to clear all session variables
// session_destroy();
?>

<body>
    <div class="container">
        <span class="icon-lock">🔒</span>
        <h1>Access Forbidden</h1>
        <p>You do not have permission to access this page.</p>
        <a href="../index.php" class="btn">Back to Home</a>
    </div>
</body>

</html>