<?php
class AuthController {
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    // Handle user login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Validate login
            $user = $this->userModel->validateLogin($username, $password);

            if ($user) {
                // Start session and redirect to dashboard
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header('Location: /dashboard');
                exit;
            } else {
                // Invalid credentials
                $error = "Invalid username or password.";
                include __DIR__ . '/../views/auth/login.php';
            }
        } else {
            // Show login form
            include __DIR__ . '/../views/auth/login.php';
        }
    }

    // Handle user logout
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }
}
?>
