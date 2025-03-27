<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}

// Get full name from session
$full_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "User";

// Get cart items and create appointments
if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $appointments = [];
    foreach($_SESSION['cart'] as $item) {
        $appointments[] = [
            'date' => date('Y-m-d', strtotime('+1 week')), // Default to 1 week from now
            'time' => '10:00 AM',
            'type' => $item['gown_name'],
            'status' => 'Pending'
        ];
    }
} else {
    // Default appointments if no cart items
    $appointments = [
        ['date' => '2024-02-15', 'time' => '10:00 AM', 'type' => 'Wedding Gown', 'status' => 'Confirmed'],
        ['date' => '2024-02-20', 'time' => '2:30 PM', 'type' => 'Ball Gown', 'status' => 'Pending'],
        ['date' => '2024-03-01', 'time' => '11:00 AM', 'type' => 'Pageant Gown', 'status' => 'Completed']
    ];
}

// Function to get status badge HTML
function getStatusBadge($status) {
    switch(strtolower($status)) {
        case 'confirmed':
            return '<span class="px-2 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>';
        case 'pending':
            return '<span class="px-2 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>';
        case 'completed':
            return '<span class="px-2 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">Completed</span>';
        case 'cancelled':
            return '<span class="px-2 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>';
        default:
            return '<span class="px-2 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">' . htmlspecialchars($status) . '</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - S&V Gown Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="font-sans">
    
    <!-- Navigation -->
  <nav class="bg-white/80 backdrop-blur-md shadow-lg fixed w-full z-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center py-4">
                    <img src="img/logo.png" alt="Logo" class="h-12 w-12 mr-2">
                    <span class="text-gray-800 font-bold text-2xl font-['Playfair_Display']">S&V Gown Rental</span>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-rose-500 font-medium transition-colors duration-300">Home</a>
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-rose-500 font-medium flex items-center transition-colors duration-300">
                            Gown
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white/90 backdrop-blur-md rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <a href="wedding2.php" class="block px-4 py-3 text-gray-700 hover:bg-rose-50 hover:text-rose-500 rounded-t-xl transition-colors duration-300">Wedding Collection</a>
                            <a href="ball2.php" class="block px-4 py-3 text-gray-700 hover:bg-rose-50 hover:text-rose-500 transition-colors duration-300">Ball Collection</a>
                            <a href="raffles2.php" class="block px-4 py-3 text-gray-700 hover:bg-rose-50 hover:text-rose-500 transition-colors duration-300">Raffles Collection</a>
                            <a href="pageant2.php" class="block px-4 py-3 text-gray-700 hover:bg-rose-50 hover:text-rose-500 rounded-b-xl transition-colors duration-300">Pageant Gowns</a>
                        </div>
                    </div>
                    <a href="booking.php" class="text-gray-700 hover:text-rose-500 font-medium transition-colors duration-300">Book Now</a>
                    <a href="contact2.php" class="text-gray-700 hover:text-rose-500 font-medium transition-colors duration-300">Contact</a>
                    <a href="appointments.php" class="text-gray-700 hover:text-rose-500 font-medium transition-colors duration-300">Appointment History</a>
                </div>
                
                <!-- User Profile & Cart -->
                <div class="flex items-center space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="relative group">
                            <div class="flex items-center space-x-2 cursor-pointer">
                                <span class="text-gray-700 font-medium"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="absolute right-0 mt-2 w-48 bg-white/90 backdrop-blur-md rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <div class="px-4 py-3 text-sm text-gray-600 border-b border-gray-200">
                                    <div class="font-medium"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
                                    <div class="truncate"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                                </div>
                                <a href="logout.php" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-xl transition-colors duration-300">
                                    Logout
                                </a>
                            </div>
                        </div>
                        <a href="cart.php" class="text-gray-700 hover:text-rose-500 transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <button onclick="window.location.href='login.php'" class="bg-gradient-to-r from-rose-400 to-pink-500 text-white px-6 py-3 rounded-full font-medium hover:shadow-lg hover:scale-105 transition-all duration-300">
                            Login
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Appointment History Section -->
    <section class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-900 text-center mb-4">Your Appointment History</h2>
            <p class="text-gray-600 text-center mb-12">View and manage your scheduled appointments</p>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-lg rounded-xl overflow-hidden">
                    <thead class="bg-gradient-to-r from-purple-500 to-pink-400">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Time</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Gown Type</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        foreach ($appointments as $appointment) {
                            echo '<tr class="hover:bg-gray-50 transition duration-150">';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-gray-700">' . date('F j, Y', strtotime($appointment['date'])) . '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-gray-700">' . date('g:i A', strtotime($appointment['time'])) . '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-gray-700">' . htmlspecialchars($appointment['type']) . '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-gray-700">' . getStatusBadge($appointment['status']) . '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap space-x-2">';
                            if ($appointment['status'] !== 'Cancelled' && $appointment['status'] !== 'Completed') {
                                echo '<button onclick="editAppointment(this)" class="bg-gradient-to-r from-purple-500 to-pink-400 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg hover:scale-105 transition-all duration-300 mr-2">Edit</button>';
                                echo '<button onclick="cancelAppointment(this)" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg hover:scale-105 transition-all duration-300">Cancel</button>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Edit Appointment Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border w-96 shadow-2xl rounded-2xl bg-white">
            <div class="mt-3">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Edit Appointment</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                        <input type="date" id="editDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Time</label>
                        <input type="time" id="editTime" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
                <div class="mt-8 space-y-3">
                    <button id="saveEdit" class="w-full bg-gradient-to-r from-purple-500 to-pink-400 text-white py-3 rounded-lg font-bold hover:shadow-lg hover:scale-105 transition-all duration-300">
                        Save Changes
                    </button>
                    <button onclick="closeEditModal()" class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg font-bold hover:bg-gray-300 transition-all duration-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Book New Appointment Section -->
    <section class="py-20 bg-gradient-to-r from-purple-500 to-pink-400">
        <div class="max-w-3xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold text-white mb-6">Ready to Book Another Appointment?</h2>
            <p class="text-white/90 mb-8">Let us help you find your perfect dress for your special occasion.</p>
            <a href="booking.php" class="inline-block bg-white text-gray-800 px-8 py-3 rounded-full font-bold hover:shadow-lg hover:scale-105 transition-all duration-300">
                Book Your Appointment
            </a>
        </div>
    </section>

    <script>
    let currentRow = null;

    function editAppointment(button) {
        currentRow = button.closest('tr');
        const dateCell = currentRow.cells[0].textContent;
        const timeCell = currentRow.cells[1].textContent;
        
        // Convert date to YYYY-MM-DD format for input
        const date = new Date(dateCell);
        const formattedDate = date.toISOString().split('T')[0];
        
        // Convert time to 24-hour format for input
        const time = new Date(`2000/01/01 ${timeCell}`);
        const formattedTime = time.toTimeString().slice(0,5);
        
        document.getElementById('editDate').value = formattedDate;
        document.getElementById('editTime').value = formattedTime;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        currentRow = null;
    }

    document.getElementById('saveEdit').addEventListener('click', function() {
        if (currentRow) {
            const newDate = new Date(document.getElementById('editDate').value);
            const newTime = document.getElementById('editTime').value;
            
            // Format date for display
            const formattedDate = newDate.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
            
            // Format time for display
            const timeDate = new Date(`2000/01/01 ${newTime}`);
            const formattedTime = timeDate.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            
            currentRow.cells[0].textContent = formattedDate;
            currentRow.cells[1].textContent = formattedTime;
            
            closeEditModal();
        }
    });

    function cancelAppointment(button) {
        if (confirm('Are you sure you want to cancel this appointment?')) {
            const row = button.closest('tr');
            const statusCell = row.querySelector('td:nth-child(4)');
            statusCell.innerHTML = getStatusBadgeHTML('Cancelled');
            button.parentElement.innerHTML = '';
        }
    }

    function getStatusBadgeHTML(status) {
        return '<span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>';
    }
    </script>
</body>
</html>
