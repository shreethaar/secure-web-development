<?php
// auth.php
include 'dbconnection.php';

function login($email, $password) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, md5($password)]);  // md5 is just an example, use proper hashing in production
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        return true;
    } else {
        return false;
    }
}

function logout() {
    session_start();
    session_destroy();
    header("Location: login.php");
}
?>

