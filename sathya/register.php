<?php
session_start();
require_once "conn.php"; // Include the database connection file

$error = "";

// Handle Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role_id = 2; // Default role for customers

    // Validate password confirmation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already exists. Please log in.";
        } else {
            // Securely hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $email, $hashed_password, $role_id);

            if ($stmt->execute()) {
                // Redirect user to login page
                header("Location: login.php?registered=1");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
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
            <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Create Account</h2>
            <p class="text-gray-600 mb-6 text-base">Please fill in your details</p>
            
            <?php if ($error) echo "<p class='text-red-500 mb-4 bg-red-50 p-2 rounded-lg text-sm font-medium border border-red-200'>$error</p>"; ?>
            
            <form method="POST" action="" class="space-y-4">
                <input type="hidden" name="register" value="1">
                <div class="text-left">
                    <label class="block font-semibold text-gray-700 mb-1 text-sm">Full Name</label>
                    <input type="text" name="name" required 
                           class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                </div>
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
                <div class="relative text-left">
                    <label class="block font-semibold text-gray-700 mb-1 text-sm">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="confirm_password" id="confirm_password" required 
                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                        <button type="button" onclick="toggleConfirmPassword()" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                            <i id="confirmEyeIcon" class="far fa-eye text-base"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2 rounded-lg hover:opacity-95 font-semibold text-base shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                    Register
                </button>
            </form>

            <div class="mt-6 p-4 border border-gray-200 rounded-xl bg-white/50">
                <p class="text-gray-600 mb-2 text-sm">Already have an account?</p>
                <a href="login.php" 
                   class="text-purple-600 font-bold hover:text-purple-800 text-sm transition-colors duration-200">
                   Sign In →
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
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function toggleConfirmPassword() {
            const confirmPasswordInput = document.getElementById('confirm_password');
            const confirmEyeIcon = document.getElementById('confirmEyeIcon');
            
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                confirmEyeIcon.classList.remove('fa-eye');
                confirmEyeIcon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                confirmEyeIcon.classList.remove('fa-eye-slash');
                confirmEyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
