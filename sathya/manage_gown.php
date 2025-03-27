<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

require_once 'conn.php';

// Handle gown deletion
if (isset($_POST['delete_gown'])) {
    $gown_id = $_POST['gown_id'];
    $stmt = $conn->prepare("DELETE FROM gowns WHERE id = ?");
    $stmt->bind_param("i", $gown_id);
    $stmt->execute();
}

// Handle gown addition
if (isset($_POST['add_gown'])) {
    $gown_name = $_POST['gown_name'];
    $price = $_POST['price'];
    
    $stmt = $conn->prepare("INSERT INTO gowns (gown_name, price) VALUES (?, ?)");
    $stmt->bind_param("sd", $gown_name, $price);
    $stmt->execute();
}

// Fetch all gowns
$result = $conn->query("SELECT * FROM gowns ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gowns - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <img src="img/logo.png" alt="Logo" class="h-12 w-12 rounded-full shadow-md">
                    <span class="text-gray-800 font-bold text-2xl bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent">
                        Manage Gowns
                    </span>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="admin_dashboard.php" class="flex items-center space-x-2 text-gray-600 hover:text-purple-600 transition-colors duration-200">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="logout.php" class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-2 rounded-full hover:from-red-600 hover:to-pink-600 transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-dress text-purple-500 mr-3"></i>
                        Gown Management
                    </h2>
                    <div class="flex items-center space-x-4">
                        <div class="bg-purple-50 text-purple-600 px-4 py-2 rounded-full text-sm font-medium">
                            Total Gowns: <?php echo $result->num_rows; ?>
                        </div>
                        <button onclick="document.getElementById('addGownModal').classList.remove('hidden')" 
                                class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-2 rounded-full hover:from-purple-600 hover:to-pink-600 transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Add Gown</span>
                        </button>
                    </div>
                </div>
                
                <!-- Add Gown Modal -->
                <div id="addGownModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3 text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Gown</h3>
                            <form method="POST" class="mt-4">
                                <div class="mb-4">
                                    <input type="text" name="gown_name" placeholder="Gown Name" required
                                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                </div>
                                <div class="mb-4">
                                    <input type="number" name="price" placeholder="Price" required step="0.01" min="0"
                                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="document.getElementById('addGownModal').classList.add('hidden')"
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
                                    <button type="submit" name="add_gown"
                                            class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">Add Gown</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Gown Modal -->
                <div id="editGownModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3 text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Gown</h3>
                            <form method="POST" class="mt-4">
                                <input type="hidden" name="edit_gown_id" id="edit_gown_id">
                                <div class="mb-4">
                                    <input type="text" name="edit_gown_name" id="edit_gown_name" placeholder="Gown Name" required
                                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                </div>
                                <div class="mb-4">
                                    <input type="number" name="edit_price" id="edit_price" placeholder="Price" required step="0.01" min="0"
                                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="document.getElementById('editGownModal').classList.add('hidden')"
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
                                    <button type="submit" name="edit_gown"
                                            class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-50 to-pink-50">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gown Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Added Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Added Time</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php while ($gown = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold mr-3">
                                            <?php echo strtoupper(substr($gown['name'], 0, 1)); ?>
                                        </div>
                                        <?php echo htmlspecialchars($gown['name']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">₱<?php echo number_format($gown['price'], 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    <?php echo date('M d, Y', strtotime($gown['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    <?php echo date('h:i A', strtotime($gown['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <button onclick="editGown(<?php echo $gown['id']; ?>, '<?php echo htmlspecialchars($gown['name']); ?>', <?php echo $gown['price']; ?>)" 
                                                class="flex items-center text-blue-500 hover:text-blue-700 transition-colors duration-200">
                                            <i class="fas fa-edit mr-2"></i>
                                            Edit
                                        </button>
                                        <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this gown?');">
                                            <input type="hidden" name="gown_id" value="<?php echo $gown['id']; ?>">
                                            <button type="submit" name="delete_gown" class="flex items-center text-red-500 hover:text-red-700 transition-colors duration-200">
                                                <i class="fas fa-trash-alt mr-2"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editGown(id, name, price) {
            document.getElementById('edit_gown_id').value = id;
            document.getElementById('edit_gown_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('editGownModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
