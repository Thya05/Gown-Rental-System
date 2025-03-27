<?php
session_start();
require_once "conn.php";

// Ensure database connection exists
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 1) {
        header("Location: admin_dashboard.php"); // Admin
    } else {
        header("Location: index2.php"); // Customer
    }
    exit();
}

$error = "";

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user data
    $stmt = $conn->prepare("SELECT id, full_name, email, password, role_id FROM users WHERE email = ?;");
    
    if (!$stmt) {
        die("Error in prepare statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['full_name'];

            // Redirect based on role
            if ($user['role_id'] == 1) {
                header("Location: admin_dashboard.php"); // Admin
            } else {
                header("Location: index2.php"); // Customer
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No account found. Please register first.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-700 via-pink-600 to-orange-400 flex items-center justify-center min-h-screen p-4">
    <div class="glass-effect p-6 rounded-2xl shadow-2xl flex w-[700px] max-w-full">
        <div class="w-1/2 flex flex-col items-center justify-center bg-gradient-to-br from-white/50 to-white/30 rounded-xl p-6 mr-6">
            <img src="img/logo.png" alt="Logo" class="h-32 w-32 hover:scale-110 transition-transform duration-500 drop-shadow-xl"> 
            <h1 class="text-gray-800 font-bold text-3xl mt-4 text-center leading-tight bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                S&V Gown<br>Rental
            </h1>
        </div>

        <div class="w-1/2 p-4">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Welcome Back</h2>
            <p class="text-gray-600 mb-6 text-base">Please sign in to your account</p>
            
            <?php if ($error) echo "<p class='text-red-500 mb-4 bg-red-50 p-2 rounded-lg text-sm font-medium border border-red-200'>$error</p>"; ?>
            
            <form method="POST" action="" class="space-y-4">
                <input type="hidden" name="login" value="1">
                <div class="text-left">
                    <label class="block font-semibold text-gray-700 mb-1 text-sm">Email Address</label>
                    <input type="email" name="email" required 
                           class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                </div>
                <div class="relative text-left">
                    <label class="block font-semibold text-gray-700 mb-1 text-sm">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required 
                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                        <button type="button" onclick="togglePassword()" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                            <i id="eyeIcon" class="far fa-eye text-base"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2 rounded-lg hover:opacity-95 font-semibold text-base shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                    Sign In
                </button>
            </form>

            <div class="mt-6 p-4 border border-gray-200 rounded-xl bg-white/50">
                <p class="text-gray-600 mb-2 text-sm">Don't have an account yet?</p>
                <a href="register.php" 
                   class="text-purple-600 font-bold hover:text-purple-800 text-sm transition-colors duration-200">
                   Create an Account →
                </a>
            </div>

            <div class="mt-4">
                <a href="index.php" 
                   class="w-full block text-center bg-gray-700 text-white py-2 rounded-lg hover:bg-gray-800 font-medium text-sm transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'far fa-eye-slash text-base';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'far fa-eye text-base';
            }
        }
    </script>
</body>
</html>
