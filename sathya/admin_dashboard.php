<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gown_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_user = mysqli_real_escape_string($conn, $_POST['username']);
    $admin_pass = $_POST['password'];
    
    // Hash the password before comparing
    $hashed_password = hash('sha256', $admin_pass);
    
    $sql = "SELECT * FROM admins WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $admin_user, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin'] = $admin_user;
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password";
        header("Location: admin_login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white/80 backdrop-blur-md shadow-lg fixed w-full z-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <img src="img/logo.png" alt="Logo" class="h-12 w-12 mr-2">
                    <span class="text-gray-800 font-bold text-2xl font-['Playfair_Display']">Admin Dashboard</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative group">
                        <div class="flex items-center space-x-2 cursor-pointer">
                            <span class="text-gray-700 font-medium"><?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Admin'; ?></span>
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="absolute right-0 mt-2 w-48 bg-white/90 backdrop-blur-md rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <a href="logout.php" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-colors duration-300">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 pt-24">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h5 class="text-xl font-bold text-gray-800 mb-2">Manage Rentals</h5>
                        <p class="text-gray-600">View and manage all gown rentals</p>
                    </div>
                    <div class="text-rose-500 text-3xl">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <a href="manage_rentals.php" class="inline-block w-full bg-gradient-to-r from-rose-400 to-pink-500 text-white px-6 py-2 rounded-lg font-medium text-center hover:shadow-lg transition-all duration-300">View Rentals</a>
            </div>
            
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h5 class="text-xl font-bold text-gray-800 mb-2">Manage Gowns</h5>
                        <p class="text-gray-600">Add, edit, or remove gowns</p>
                    </div>
                    <div class="text-rose-500 text-3xl">
                        <i class="fas fa-tshirt"></i>
                    </div>
                </div>
                <a href="manage_gown.php" class="inline-block w-full bg-gradient-to-r from-rose-400 to-pink-500 text-white px-6 py-2 rounded-lg font-medium text-center hover:shadow-lg transition-all duration-300">Manage Gowns</a>
            </div>
            
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h5 class="text-xl font-bold text-gray-800 mb-2">Manage Users</h5>
                        <p class="text-gray-600">View and manage accounts</p>
                    </div>
                    <div class="text-rose-500 text-3xl">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <a href="manage_users.php" class="inline-block w-full bg-gradient-to-r from-rose-400 to-pink-500 text-white px-6 py-2 rounded-lg font-medium text-center hover:shadow-lg transition-all duration-300">Manage Users</a>
            </div>

            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h5 class="text-xl font-bold text-gray-800 mb-2">View Reports</h5>
                        <p class="text-gray-600">Generate business reports</p>
                    </div>
                    <div class="text-rose-500 text-3xl">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <a href="view_report.php" class="inline-block w-full bg-gradient-to-r from-rose-400 to-pink-500 text-white px-6 py-2 rounded-lg font-medium text-center hover:shadow-lg transition-all duration-300">View Reports</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-xl font-bold text-gray-800">Recent Rentals</h5>
                    <div class="text-rose-500 text-xl">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-4 text-gray-700">ID</th>
                                <th class="text-left py-3 px-4 text-gray-700">Customer</th>
                                <th class="text-left py-3 px-4 text-gray-700">Gown</th>
                                <th class="text-left py-3 px-4 text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM rentals ORDER BY rental_date DESC LIMIT 5";
                            $result = $conn->query($sql);
                            
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr class='border-b hover:bg-gray-50'>";
                                    echo "<td class='py-3 px-4'>".$row['gown_id']."</td>";
                                    echo "<td class='py-3 px-4'>".$row['customer_name']."</td>";
                                    echo "<td class='py-3 px-4'>".$row['gown_name']."</td>";
                                    echo "<td class='py-3 px-4'>".$row['status']."</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-xl font-bold text-gray-800">System Statistics</h5>
                    <div class="text-rose-500 text-xl">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <?php
                $total_gowns = $conn->query("SELECT COUNT(*) as count FROM gowns")->fetch_assoc()['count'];
                $total_rentals = $conn->query("SELECT COUNT(*) as count FROM rentals")->fetch_assoc()['count'];
                $total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
                ?>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-rose-500 text-2xl mb-2">
                            <i class="fas fa-dress"></i>
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <p class="text-gray-600 text-sm">Total Gowns</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo $total_gowns; ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-rose-500 text-2xl mb-2">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <p class="text-gray-600 text-sm">Total Rentals</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo $total_rentals; ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-rose-500 text-2xl mb-2">
                            <i class="fas fa-clock"></i>
                        </div>
                        <p class="text-gray-600 text-sm">Active Rentals</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-rose-500 text-2xl mb-2">
                            <i class="fas fa-users"></i>
                        </div>
                        <p class="text-gray-600 text-sm">Total Users</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
