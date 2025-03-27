<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

require_once 'conn.php';



// Fetch recent bookings
$createbooking = $conn->query("SELECT b.*, u.full_name, g.gown_name 
                                FROM bookings b
                                JOIN users u ON b.user_id = u.id 
                                JOIN gowns g ON b.gown_id = g.id
                                ORDER BY b.created_at DESC LIMIT 10");

// Create bookings table if not exists
$create_booking = "CREATE TABLE IF NOT EXISTS booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    gown_id INT NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid',
    pickup_date DATE,
    pickup_time TIME,
    return_date DATE,
    return_time TIME,
    total_amount DECIMAL(10,2),
    gcash_reference VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (gown_id) REFERENCES gowns(id)
)";

$conn->query($create_booking);

    
// Get current active report type (default to booking)
$report_type = isset($_GET['report']) ? $_GET['report'] : 'booking';

// Handle date filtering
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01'); // First day of current month
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t'); // Last day of current month

// Handle export functionality
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    // Export logic would go here
    // For now, we'll just show an alert
    echo "<script>alert('Export to Excel feature will be implemented here.');</script>";
}

if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
    // Export logic would go here
    // For now, we'll just show an alert
    echo "<script>alert('Export to PDF feature will be implemented here.');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Dashboard - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .tab-active {
            border-bottom: 3px solid #8b5cf6;
            color: #8b5cf6;
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
                        Reports Dashboard
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
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Bookings</p>
                        <h3 class="text-3xl font-bold text-gray-800"><?php echo isset($total_bookings) ? $total_bookings : 0; ?></h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-calendar text-purple-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Monthly Revenue</p>
                        <h3 class="text-3xl font-bold text-gray-800">₱<?php echo isset($monthly_revenue) ? number_format($monthly_revenue, 2) : '0.00'; ?></h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Yearly Revenue</p>
                        <h3 class="text-3xl font-bold text-gray-800">₱<?php echo isset($yearly_revenue) ? number_format($yearly_revenue, 2) : '0.00'; ?></h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Controls -->
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">
                    <i class="fas fa-file-alt text-purple-500 mr-2"></i> Report Generator
                </h2>
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <a href="?export=excel&report=<?php echo $report_type; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-file-excel mr-2"></i> Export to Excel
                    </a>
                    <a href="?export=pdf&report=<?php echo $report_type; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-file-pdf mr-2"></i> Export to PDF
                    </a>
                </div>
            </div>

            <!-- Filter Controls -->
            <form action="" method="GET" class="mb-6">
                <input type="hidden" name="report" value="<?php echo $report_type; ?>">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Start Date</label>
                        <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">End Date</label>
                        <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors duration-200">
                            <i class="fas fa-filter mr-2"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>

            <!-- Report Type Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <div class="flex overflow-x-auto">
                    <a href="?report=booking" class="px-6 py-3 text-sm font-medium <?php echo $report_type == 'booking' ? 'tab-active' : 'text-gray-500 hover:text-gray-700'; ?>">
                        Booking Reports
                    </a>
                    <a href="?report=payment" class="px-6 py-3 text-sm font-medium <?php echo $report_type == 'payment' ? 'tab-active' : 'text-gray-500 hover:text-gray-700'; ?>">
                        Payment Reports
                    </a>
                    <a href="?report=customer" class="px-6 py-3 text-sm font-medium <?php echo $report_type == 'customer' ? 'tab-active' : 'text-gray-500 hover:text-gray-700'; ?>">
                        Customer Reports
                    </a>
                    <a href="?report=inventory" class="px-6 py-3 text-sm font-medium <?php echo $report_type == 'inventory' ? 'tab-active' : 'text-gray-500 hover:text-gray-700'; ?>">
                        Inventory Reports
                    </a>
                    <a href="?report=revenue" class="px-6 py-3 text-sm font-medium <?php echo $report_type == 'revenue' ? 'tab-active' : 'text-gray-500 hover:text-gray-700'; ?>">
                        Revenue Reports
                    </a>
                </div>
            </div>

            <!-- Report Content -->
            <?php if ($report_type == 'booking'): ?>
                <!-- Booking Reports -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-50 to-pink-50">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Booking ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gown Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pickup Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Return Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">GCash Reference</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php
                            $booking_query = "SELECT b.*, u.full_name, g.gown_name 
                                              FROM bookings b
                                              JOIN users u ON b.user_id = u.id 
                                              JOIN gowns g ON b.gown_id = g.id
                                              WHERE b.created_at BETWEEN '$start_date' AND '$end_date 23:59:59'
                                              ORDER BY b.created_at DESC";
                            $booking_result = $conn->query($booking_query);
                            
                            while ($booking = $booking_result->fetch_assoc()):
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo $booking['id']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($booking['gown_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php 
                                    if (!empty($booking['pickup_date']) && !empty($booking['pickup_time'])) {
                                        echo date('M d, Y', strtotime($booking['pickup_date'])) . ' at ' . 
                                             date('h:i A', strtotime($booking['pickup_time']));
                                    } else {
                                        echo "Not set";
                                    }
                                    ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php 
                                    if (!empty($booking['return_date']) && !empty($booking['return_time'])) {
                                        echo date('M d, Y', strtotime($booking['return_date'])) . ' at ' . 
                                             date('h:i A', strtotime($booking['return_time']));
                                    } else {
                                        echo "Not set";
                                    }
                                    ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        <?php 
                                        switch($booking['status']) {
                                            case 'completed': echo 'bg-green-100 text-green-800'; break;
                                            case 'confirmed': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                            default: echo 'bg-yellow-100 text-yellow-800';
                                        }
                                        ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        <?php 
                                        switch($booking['payment_status']) {
                                            case 'paid': echo 'bg-green-100 text-green-800'; break;
                                            case 'partial': echo 'bg-blue-100 text-blue-800'; break;
                                            default: echo 'bg-red-100 text-red-800';
                                        }
                                        ?>">
                                        <?php echo ucfirst($booking['payment_status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">₱<?php echo number_format($booking['total_amount'] ?? 0, 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo !empty($booking['gcash_reference']) ? $booking['gcash_reference'] : 'N/A'; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($report_type == 'payment'): ?>

<!-- Payment Reports -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr class="bg-gradient-to-r from-purple-50 to-pink-50">
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment ID</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment Method</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reference Number</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            <?php while ($payments = $payments_result->fetch_assoc()): ?>
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo $payments['id']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($payments['full_name']); ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo date('M d, Y', strtotime($payments['payment_date'])); ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">₱<?php echo number_format($payments['amount'], 2); ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo ucfirst($payments['payment_method']); ?></td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        <?php 
                        switch($payments['status']) {
                            case 'completed': echo 'bg-green-100 text-green-800'; break;
                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                            default: echo 'bg-red-100 text-red-800';
                        }
                        ?>">
                        <?php echo ucfirst($payments['status']); ?>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo !empty($payments['reference_number']) ? $payments['reference_number'] : 'N/A'; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

            <?php elseif ($report_type == 'customer'): ?>
                <!-- Customer Reports -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-50 to-pink-50">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact Number</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Bookings</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Spent</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Booking</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php
                            $customers_query = "SELECT u.id, u.full_name, u.phone, u.email, 
                                                COUNT(b.id) as total_bookings,
                                                SUM(p.amount) as total_spent,
                                                MAX(b.created_at) as last_booking
                                                FROM users u
                                                LEFT JOIN bookings b ON u.id = b.user_id
                                                LEFT JOIN payments p ON b.id = p.booking_id
                                                WHERE u.role = 0
                                                GROUP BY u.id
                                                ORDER BY total_bookings DESC";
                            $customers_result = $conn->query($customers_query);
                            
                            while ($customer = $customers_result->fetch_assoc()):
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo $customer['id']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($customer['full_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($customer['email']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo $customer['total_bookings']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">₱<?php echo number_format($customer['total_spent'] ?? 0, 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php echo !empty($customer['last_booking']) ? date('M d, Y', strtotime($customer['last_booking'])) : 'Never'; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($report_type == 'inventory'): ?>

                <!-- Inventory Reports -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-50 to-pink-50">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gown ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gown Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rental Price</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Bookings</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Rented</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php
                            $inventory_query = "SELECT g.id, g.gown_name, g.category, g.rental_price, g.status,
                                               COUNT(b.id) as total_bookings,
                                               MAX(b.created_at) as last_rented
                                               FROM gowns g
                                               LEFT JOIN bookings b ON g.id = b.gown_id
                                               GROUP BY g.id
                                               ORDER BY total_bookings DESC";
                            $inventory_result = $conn->query($inventory_query);
                            
                            while ($gown = $inventory_result->fetch_assoc()):
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo $gown['id']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($gown['gown_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($gown['category']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">₱<?php echo number_format($gown['rental_price'], 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo $gown['total_bookings']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        <?php 
                                        switch($gown['status']) {
                                            case 'available': echo 'bg-green-100 text-green-800'; break;
                                            case 'rented': echo 'bg-blue-100 text-blue-800'; break;
                                            default: echo 'bg-yellow-100 text-yellow-800';
                                        }
                                        ?>">
                                        <?php echo ucfirst($gown['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php echo !empty($gown['last_rented']) ? date('M d, Y', strtotime($gown['last_rented'])) : 'Never'; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    </div>
            <?php elseif ($report_type == 'revenue'): ?>
                <!-- Revenue Reports -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-50 to-pink-50">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment Method</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php
                            $revenue_query = "SELECT DATE(payment_date) as date, SUM(amount) as revenue, payment_method, payment_status
                                              FROM payments
                                              WHERE payment_date BETWEEN '$start_date' AND '$end_date 23:59:59'
                                              GROUP BY DATE(payment_date), payment_method, payment_status
                                              ORDER BY date DESC";
                            $revenue_result = $conn->query($revenue_query);
                            
                            while ($revenue = $revenue_result->fetch_assoc()):
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo date('M d, Y', strtotime($revenue['date'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">₱<?php echo number_format($revenue['revenue'] ?? 0, 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo ucfirst(str_replace('_', ' ', $revenue['payment_method'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        <?php 
                                        switch($revenue['payment_status']) {
                                            case 'paid': echo 'bg-green-100 text-green-800'; break;
                                            case 'partial': echo 'bg-blue-100 text-blue-800'; break;
                                            default: echo 'bg-red-100 text-red-800';
                                        }
                                        ?>">
                                                <?php echo ucfirst($revenue['payment_status']); ?>
                                        </span>
                                    </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>