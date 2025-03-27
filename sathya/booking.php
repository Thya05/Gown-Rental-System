<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gown_rental";

// Establish Database Connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $contact_number = $_POST['contact_number'];
    $payment_amount = (float)$_POST['payment_amount']; // Convert to float
    $pickup_date = $_POST['pickup_date'];
    $pickup_time = $_POST['pickup_time'];
    $return_date = $_POST['return_date'];
    $return_time = $_POST['return_time'];
    $gcash_references = $_POST['gcash_references'];
    $notes = $_POST['notes'];
    $status = 'pending';
    $payment_status = 'unpaid';

    if (isset($_POST['gown_id']) && !empty($_POST['gown_id'])) {
        foreach ($_POST['gown_id'] as $gown_id) {
            if (!empty($gown_id)) {
                // Check if gown exists before inserting
                $check_gown = $conn->prepare("SELECT id FROM gowns WHERE id = ?");
                $check_gown->bind_param("i", $gown_id);
                $check_gown->execute();
                $result = $check_gown->get_result();
    
                if ($result->num_rows > 0) {
                    $stmt_item = $conn->prepare("INSERT INTO booking_items (booking_id, gown_id) VALUES (?, ?)");
                    $stmt_item->bind_param("ii", $booking_id, $gown_id);
                    $stmt_item->execute();
                    $stmt_item->close();
                } else {
                    echo "<script>alert('Error: Selected gown does not exist!');</script>";
                }
                $check_gown->close();
            }
        }
    }
    
        // Begin transaction
        $conn->begin_transaction();

        try {
            // Create booking record
            $stmt = $conn->prepare("INSERT INTO booking 
                (user_id, pickup_date, pickup_time, return_date, return_time, status, payment_status, payment_amount, gcash_references, notes, contact_number)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("isssssssdss", $user_id, $pickup_date, $pickup_time, $return_date, $return_time, $status, $payment_status, $payment_amount, $gcash_references, $notes, $contact_number);

            if (!$stmt->execute()) {
                throw new Exception("Error executing booking query: " . $stmt->error);
            }

            $booking_id = $conn->insert_id;
            $stmt->close(); // Close after execution

            // Insert each gown into booking_items
            $stmt_item = $conn->prepare("INSERT INTO booking_items (booking_id, gown_id) VALUES (?, ?)");

            foreach ($_POST['gown_id'] as $gown_id) {
                if (!empty($gown_id)) {
                    $stmt_item->bind_param("ii", $booking_id, $gown_id);
                    if (!$stmt_item->execute()) {
                        throw new Exception("Error inserting booking item: " . $stmt_item->error);
                    }
                }
            }
            $stmt_item->close();

            // Create appointment record
            $appointment_date = date('Y-m-d');
            $appointment_time = date('H:i:s');
            $appointment_type = 'Gown Rental';
            $appointment_status = 'confirmed';

            $stmt_appointment = $conn->prepare("INSERT INTO appointments 
                (user_id, booking_id, appointment_date, appointment_time, appointment_type, status, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt_appointment->bind_param("iisssss", $user_id, $booking_id, $appointment_date, $appointment_time, $appointment_type, $appointment_status, $notes);

            if (!$stmt_appointment->execute()) {
                throw new Exception("Error inserting appointment: " . $stmt_appointment->error);
            }
            $stmt_appointment->close();

            // Commit transaction
            $conn->commit();

            // Store booking details in session for appointment history
            $_SESSION['last_booking'] = [
                'id' => $booking_id,
                'pickup_date' => $pickup_date,
                'return_date' => $return_date,
                'status' => $status
            ];

            // Redirect with success message
            echo "<script>
                alert('Booking submitted successfully! You will be redirected to your appointments.');
                window.location.href='appointments.php';
            </script>";
            exit();
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('Please select at least one gown');</script>";
    }


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Gown - S&V Gown Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .date-input::-webkit-calendar-picker-indicator {
            background-color: #fff;
            padding: 5px;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body class="font-sans bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md shadow-lg fixed w-full z-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center py-4">
                    <img src="img/logo.png" alt="Logo" class="h-12 w-12 mr-2">
                    <span class="text-gray-800 font-bold text-2xl font-['Playfair_Display']">S&V Gown Rental</span>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="index2.php" class="text-gray-700 hover:text-rose-500 font-medium transition-colors duration-300">Home</a>
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
                    <a href="booking.php" class="text-rose-500 font-medium transition-colors duration-300">Book Now</a>
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
                                <a href="login.php?logout=1" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-xl transition-colors duration-300">
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

    <!-- Booking Form Section -->
    <section class="pt-32 pb-20 bg-gradient-to-b from-pink-50 to-white">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-900 text-center mb-4">Book Your Dream Gown</h2>
            <p class="text-gray-600 text-center mb-12">Fill out the form below to reserve your perfect gown for your special occasion</p>
            
            <!-- Success Message -->
            <div id="successMessage" class="hidden mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
                Your booking has been submitted successfully! Redirecting to your appointments...
            </div>

            <!-- Error Message -->
            <div id="errorMessage" class="hidden mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
            </div>
            
            <!-- Payment Instructions -->
            <div class="mb-8 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                <h3 class="font-bold text-yellow-800 mb-2">Important Payment Information</h3>
                <p class="text-yellow-700 mb-2">Please note that a ₱500 downpayment is required before your booking can be accepted.</p>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="font-medium text-gray-700">GCash Payment Details:</p>
                    <p class="text-gray-600">Account Number: <span class="font-semibold">09753206018</span></p>
                    <p class="text-gray-600">Account Name: <span class="font-semibold">Flordeluna Kilat</span></p>
                </div>
            </div>
            
            <div class="glass-effect p-8 rounded-2xl shadow-xl">
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Customer Name</label>
                            <input type="text" name="customer_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition-colors duration-200" value="<?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : ''; ?>" readonly>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Contact Number</label>
                            <input type="tel" name="contact_number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition-colors duration-200" placeholder="Enter your contact number" required>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Payment Amount</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                <input type="number" step="0.01" name="payment_amount" class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition-colors duration-200" required>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Minimum downpayment: ₱500</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <div class="gown-selection">
                                <label class="block text-gray-700 font-medium mb-2">Select Gown</label>
                                <div class="flex items-center space-x-2">
                                    <select name="gown_id[]" class="gown-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition-colors duration-200">
                                        <option value="">-- Select a Gown --</option>
                                        <!-- Wedding Gowns -->
                                        <option value="1" data-category="wedding" data-image="img/wedding1.jpeg" data-price="1000">Wedding Gown 1 - ₱1,000.00</option>
                                        <option value="2" data-category="wedding" data-image="img/wedding2.jpeg" data-price="1500">Wedding Gown 2 - ₱1,500.00</option>
                                        <option value="3" data-category="wedding" data-image="img/wedding3.jpeg" data-price="500">Wedding Gown 3 - ₱500.00</option>
                                        <option value="4" data-category="wedding" data-image="img/wedding4.jpeg" data-price="500">Wedding Gown 4 - ₱500.00</option>
                                        <option value="5" data-category="wedding" data-image="img/wedding5.jpeg" data-price="500">Wedding Gown 5 - ₱500.00</option>
                                        <option value="6" data-category="wedding" data-image="img/wedding6.jpeg" data-price="500">Wedding Gown 6 - ₱500.00</option>
                                        <option value="7" data-category="wedding" data-image="img/wedding7.jpeg" data-price="500">Wedding Gown 7 - ₱500.00</option>
                                        
                                        <!-- Ball Gowns -->
                                        <option value="6" data-category="ball" data-image="img/ballgown1.jpeg" data-price="1500">Ball Gown 1 - ₱1,500.00</option>
                                        <option value="7" data-category="ball" data-image="img/ballgown2.jpeg" data-price="1500">Ball Gown 2 - ₱1,500.00</option>
                                        <option value="8" data-category="ball" data-image="img/ballgown3.jpeg" data-price="1500">Ball Gown 3 - ₱1,500.00</option>
                                        <option value="9" data-category="ball" data-image="img/ballgown4.jpeg" data-price="2000">Ball Gown 4 - ₱2,000.00</option>
                                        <option value="10" data-category="ball" data-image="img/ballgown5.jpeg" data-price="2000">Ball Gown 5 - ₱2,000.00</option>
                                        <option value="11" data-category="ball" data-image="img/ballgown6.jpeg" data-price="2000">Ball Gown 6 - ₱1,500.00</option>
                                        <option value="12" data-category="ball" data-image="img/ballgown7.jpeg" data-price="2000">Ball Gown 7 - ₱1,500.00</option>  
                                        <option value="13" data-category="ball" data-image="img/ballgown8.jpeg" data-price="500">Ball Gown 8 - ₱800.00</option>
                                        <option value="14" data-category="ball" data-image="img/ballgown9.jpeg" data-price="500">Ball Gown 9 - ₱800.00</option>
                                        <option value="15" data-category="ball" data-image="img/ballgown10.jpeg" data-price="500">Ball Gown 10 - ₱1,500.00</option>
                                        <option value="16" data-category="ball" data-image="img/ballgown11.jpeg" data-price="500">Ball Gown 11 - ₱800.00</option>
                                        <option value="17" data-category="ball" data-image="img/ballgown12.jpeg" data-price="1500">Ball Gown 12 - ₱1,500.00</option>
                                        <option value="18" data-category="ball" data-image="img/ballgown13.jpeg" data-price="1500">Ball Gown 13 - ₱1,800.00</option>
                                        <option value="19" data-category="ball" data-image="img/ballgown14.jpeg" data-price="1500">Ball Gown 14 - ₱1,500.00</option>
                                        <option value="20" data-category="ball" data-image="img/ballgown15.jpeg" data-price="500">Ball Gown 15 - ₱1,500.00</option>
                                        <option value="21" data-category="ball" data-image="img/ballgown16.jpeg" data-price="500">Ball Gown 16 - ₱2,000.00</option>
                                        <option value="22" data-category="ball" data-image="img/ballgown17.jpeg" data-price="500">Ball Gown 17 - ₱1,000.00</option>
                                        <option value="23" data-category="ball" data-image="img/ballgown18.jpeg" data-price="500">Ball Gown 18 - ₱1,500.00</option>
                                        <option value="24" data-category="ball" data-image="img/ballgown19.jpeg" data-price="500">Ball Gown 19 - ₱2,000.00</option>
                                        
                                        <!-- Raffles Gowns -->
                                        <option value="11" data-category="raffles" data-image="img/raffles1.jpeg" data-price="500">Raffles Gown 1 - ₱1,000.00</option>
                                        <option value="12" data-category="raffles" data-image="img/raffles2.jpeg" data-price="500">Raffles Gown 2 - ₱1,500.00</option>
                                        <option value="13" data-category="raffles" data-image="img/raffles3.jpeg" data-price="500">Raffles Gown 3 - ₱1,500.00</option>
                                        <option value="14" data-category="raffles" data-image="img/raffles4.jpeg" data-price="500">Raffles Gown 4 - ₱1,500.00</option>
                                        <option value="15" data-category="raffles" data-image="img/raffles5.jpeg" data-price="500">Raffles Gown 5 - ₱2,000.00</option>
                                        <option value="16" data-category="raffles" data-image="img/raffles6.jpeg" data-price="500">Raffles Gown 6 - ₱1,500.00</option>
                                        <option value="17" data-category="raffles" data-image="img/raffles7.jpeg" data-price="500">Raffles Gown 7 - ₱500.00</option>
                                        
                                        <!-- Pageant Gowns -->
                                        <option value="16" data-category="pageant" data-image="img/pageantgown1.jpeg" data-price="500">Pageant Gown 1 - ₱500.00</option>
                                        <option value="17" data-category="pageant" data-image="img/pageantgown2.jpeg" data-price="500">Pageant Gown 2 - ₱800.00</option>
                                        <option value="18" data-category="pageant" data-image="img/pageantgown3.jpeg" data-price="500">Pageant Gown 3 - ₱500.00</option>
                                        <option value="19" data-category="pageant" data-image="img/pageantgown4.jpeg" data-price="500">Pageant Gown 4 - ₱500.00</option>
                                        <option value="20" data-category="pageant" data-image="img/pageantgown5.jpeg" data-price="500">Pageant Gown 5 - ₱1,500.00</option>
                                        <option value="21" data-category="pageant" data-image="img/pageantgown6.jpeg" data-price="500">Pageant Gown 6 - ₱500.00</option>
                                        <option value="22" data-category="pageant" data-image="img/pageantgown7.jpeg" data-price="500">Pageant Gown 7 - ₱800.00</option>
                                    </select>
                                    <button type="button" id="view-gown-btn" class="bg-blue-500 text-white px-3 py-3 rounded-lg hover:bg-blue-600 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button type="button" id="add-gown-btn" class="bg-green-500 text-white px-3 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Gown Category Filter -->
                            <div class="mt-4 mb-6">
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" class="gown-category-btn px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 border-2 border-transparent focus:outline-none transition-colors duration-200" data-category="wedding">Wedding</button>
                                    <button type="button" class="gown-category-btn px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 border-2 border-transparent focus:outline-none transition-colors duration-200" data-category="ball">Ball</button>
                                    <button type="button" class="gown-category-btn px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 border-2 border-transparent focus:outline-none transition-colors duration-200" data-category="raffles">Raffles</button>
                                    <button type="button" class="gown-category-btn px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 border-2 border-transparent focus:outline-none transition-colors duration-200" data-category="pageant">Pageant</button>
                                </div>
                            </div>
                            
                            <!-- Gown Preview Modal -->
                            <div id="gown-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
                                <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-auto">
                                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                                        <h3 id="modal-gown-name" class="text-xl font-bold text-gray-800"></h3>
                                        <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="p-6">
                                        <img id="modal-gown-image" src="" alt="Gown Preview" class="w-full h-auto max-h-[50vh] object-contain mb-4 rounded-lg">
                                        <div class="flex justify-between items-center">
                                            <p id="modal-gown-category" class="text-gray-600 bg-gray-100 px-3 py-1 rounded-full text-sm"></p>
                                            <p id="modal-gown-price" class="text-xl font-bold text-rose-500"></p>
                                        </div>
                                    </div>
                                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                                        <button id="select-this-gown" class="w-full bg-rose-500 text-white py-2 px-4 rounded-lg hover:bg-rose-600 transition-colors duration-200">
                                            Add This Gown
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="gowns-container">
                                <!-- Selected gowns will be added here -->
                            </div>
                            
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">Total Amount:</span>
                                    <span id="total-amount" class="text-xl font-bold text-rose-500">₱0.00</span>
                                </div>
                            </div>
                            
                            <div id="selected-gown-preview" class="mt-4 hidden">
                                <div class="bg-white p-4 rounded-lg shadow-md">
                                    <img id="gown-preview-image" src="" alt="Selected Gown" class="w-full h-auto max-h-[200px] object-contain rounded-md mb-2">
                                    <h3 id="gown-preview-name" class="text-lg font-semibold text-gray-800"></h3>
                                    <p id="gown-preview-price" class="text-rose-500 font-medium"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Pickup Date</label>
                            <input type="date" name="pickup_date" class="date-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition-colors duration-200" required>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Pickup Time</label>
                            <input type="time" name="pickup_time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition-colors duration-200" required>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Return Date</label>
                            <input type="date" name="return_date" class="date-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition-colors duration-200" required>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Return Time</label>
                            <input type="time" name="return_time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition-colors duration-200" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">GCash Reference Number</label>
                        <input type="text" name="gcash_references" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition-colors duration-200" placeholder="Enter your GCash reference number">
                        <p class="text-sm text-gray-500 mt-1">Please enter the reference number from your GCash payment</p>
                    </div>

                    <!-- Late Return Notice -->
            <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                <h3 class="font-bold text-red-800 mb-2">Late Return Policy</h3>
                <p class="text-red-700">Please be advised that late returns will incur additional charges. The exact amount will be determined by the owner based on the duration of delay.</p>
            </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Special Requests or Notes</label>
                        <textarea name="notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition-colors duration-200" placeholder="Any special requests or additional information..."></textarea>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-gradient-to-r from-rose-400 to-pink-500 text-white py-3 px-6 rounded-lg font-medium hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                            Submit Booking Request
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-8 text-center text-gray-600">
                <p>Need help with your booking? <a href="contact2.php" class="text-rose-500 hover:underline">Contact our support team</a></p>
            </div>
        </div>
    </section>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gown category buttons
            const categoryButtons = document.querySelectorAll('.gown-category-btn');
            const gownSelect = document.querySelector('.gown-select');
            const previewContainer = document.getElementById('selected-gown-preview');
            const previewImage = document.getElementById('gown-preview-image');
            const previewName = document.getElementById('gown-preview-name');
            const previewPrice = document.getElementById('gown-preview-price');
            const totalAmountDisplay = document.getElementById('total-amount');
            
            // Modal elements
            const gownModal = document.getElementById('gown-modal');
            const modalGownName = document.getElementById('modal-gown-name');
            const modalGownImage = document.getElementById('modal-gown-image');
            const modalGownCategory = document.getElementById('modal-gown-category');
            const modalGownPrice = document.getElementById('modal-gown-price');
            const closeModalBtn = document.getElementById('close-modal');
            const selectThisGownBtn = document.getElementById('select-this-gown');
            const viewGownBtn = document.getElementById('view-gown-btn');
            const addGownBtn = document.getElementById('add-gown-btn');
            
            // Initialize total amount
            let totalAmount = 0;
            let currentlyViewedGown = null;
            
            // Update total amount display
            function updateTotalAmount() {
                totalAmountDisplay.textContent = '₱' + totalAmount.toFixed(2);
            }
            
            // Show gown preview when selected
            gownSelect.addEventListener('change', function() {
                if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    const imageUrl = selectedOption.dataset.image;
                    const gownName = selectedOption.text.split(' - ')[0];
                    const gownPrice = selectedOption.text.split(' - ')[1];
                    
                    previewImage.src = imageUrl;
                    previewName.textContent = gownName;
                    previewPrice.textContent = gownPrice;
                    previewContainer.classList.remove('hidden');
                } else {
                    previewContainer.classList.add('hidden');
                }
            });
            
            // View gown button click handler
            viewGownBtn.addEventListener('click', function() {
                const select = document.querySelector('.gown-select');
                if (!select.value) {
                    alert('Please select a gown first to view details');
                    return;
                }
                
                const selectedOption = select.options[select.selectedIndex];
                currentlyViewedGown = {
                    id: select.value,
                    name: selectedOption.text.split(' - ')[0],
                    price: parseFloat(selectedOption.dataset.price),
                    image: selectedOption.dataset.image,
                    category: selectedOption.dataset.category
                };
                
                // Populate modal
                modalGownName.textContent = currentlyViewedGown.name;
                modalGownImage.src = currentlyViewedGown.image;
                modalGownCategory.textContent = currentlyViewedGown.category.charAt(0).toUpperCase() + currentlyViewedGown.category.slice(1);
                modalGownPrice.textContent = '₱' + currentlyViewedGown.price.toFixed(2);
                
                // Show modal
                gownModal.classList.remove('hidden');
            });
            
            // Add gown button click handler
            addGownBtn.addEventListener('click', function() {
                const select = document.querySelector('.gown-select');
                if (!select.value) {
                    alert('Please select a gown first to add it');
                    return;
                }
                
                const selectedOption = select.options[select.selectedIndex];
                const gown = {
                    id: select.value,
                    name: selectedOption.text.split(' - ')[0],
                    price: parseFloat(selectedOption.dataset.price),
                    image: selectedOption.dataset.image,
                    category: selectedOption.dataset.category
                };
                
                // Add the gown to selection
                addGownToSelection(gown);
            });
            
            // Close modal button
            closeModalBtn.addEventListener('click', function() {
                gownModal.classList.add('hidden');
            });
            
            // Select this gown button in modal
            selectThisGownBtn.addEventListener('click', function() {
                if (currentlyViewedGown) {
                    // Add the gown to selection
                    addGownToSelection(currentlyViewedGown);
                    // Close modal
                    gownModal.classList.add('hidden');
                }
            });
            
            // Add gown to selection
            function addGownToSelection(gown) {
                // Create new gown element
                const gownElement = document.createElement('div');
                gownElement.className = 'selected-gown mt-4 p-4 bg-white rounded-lg shadow-sm flex items-center';
                gownElement.dataset.price = gown.price;
                gownElement.dataset.id = gown.id;
                
                gownElement.innerHTML = `
                    <img src="${gown.image}" alt="${gown.name}" class="w-12 h-12 object-cover rounded-md mr-4">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-800">${gown.name}</h4>
                        <p class="text-rose-500">₱${gown.price.toFixed(2)}</p>
                    </div>
                    <input type="hidden" name="gown_id[]" value="${gown.id}">
                    <input type="hidden" name="gown_price[]" value="${gown.price}">
                    <button type="button" class="text-red-500 hover:text-red-700" onclick="removeGown(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                `;
                
                // Add to container
                document.getElementById('gowns-container').appendChild(gownElement);
                
                // Update total
                totalAmount += gown.price;
                updateTotalAmount();
                
                // Reset select
                gownSelect.value = '';
                previewContainer.classList.add('hidden');
            }
            
            // Remove gown function
            window.removeGown = function(button) {
                const gownElement = button.closest('.selected-gown');
                const gownPrice = parseFloat(gownElement.dataset.price);
                
                // Update total
                totalAmount -= gownPrice;
                updateTotalAmount();
                
                // Remove element
                gownElement.remove();
            };
            
            // Filter gowns by category if category buttons exist
            if (categoryButtons.length > 0) {
                categoryButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const category = this.dataset.category;
                        
                        // Reset all buttons
                        categoryButtons.forEach(btn => {
                            btn.classList.remove('border-rose-500');
                            btn.classList.add('border-transparent');
                        });
                        
                        // Highlight selected button
                        this.classList.remove('border-transparent');
                        this.classList.add('border-rose-500');
                        
                        // Filter dropdown options
                        Array.from(gownSelect.options).forEach(option => {
                            if (option.value === '' || option.dataset.category === category) {
                                option.style.display = '';
                            } else {
                                option.style.display = 'none';
                            }
                        });
                        
                        // Reset selection
                        gownSelect.value = '';
                        previewContainer.classList.add('hidden');
                    });
                });
            }
            
            // Date validation
            const pickupDateInput = document.querySelector('input[name="pickup_date"]');
            const returnDateInput = document.querySelector('input[name="return_date"]');
            
            if (pickupDateInput && returnDateInput) {
                // Set minimum date to today
                const today = new Date().toISOString().split('T')[0];
                pickupDateInput.min = today;
                
                pickupDateInput.addEventListener('change', function() {
                    returnDateInput.min = this.value;
                    if (returnDateInput.value && returnDateInput.value < this.value) {
                        returnDateInput.value = this.value;
                    }
                });
            }
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === gownModal) {
                    gownModal.classList.add('hidden');
                }
            });
        });
    </script>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">S&V Gown Rental</h3>
                <p class="text-gray-400">Making your special moments unforgettable with our premium gown collection.</p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="about2.php" class="text-gray-400 hover:text-white">About Us</a></li>
                    <li><a href="services2.php" class="text-gray-400 hover:text-white">Services</a></li>
                    <li><a href="gallery2.php" class="text-gray-400 hover:text-white">Gowns</a></li>
                    <li><a href="contact2.php" class="text-gray-400 hover:text-white">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4">Contact Info</h4>
                <ul class="space-y-2 text-gray-400">
                    <li>Zone 8 Ordnance, Camp Evangelista, Patag</li>
                    <li>Cagayan de Oro City, Philippines</li>
                    <li>Phone: 0975 320 6018</li>
                    <li>Email: sathyakilat@icloud.com</li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4">Follow Us</h4>
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/profile.php?id=100090762784236" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 mt-8 pt-8 border-t border-gray-800 text-center text-gray-400">
            <p>&copy; <?php echo date('Y'); ?> S&V Gown Rental. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Add date validation to ensure return date is after pickup date
        document.addEventListener('DOMContentLoaded', function() {
            const pickupDateInput = document.querySelector('input[name="pickup_date"]');
            const returnDateInput = document.querySelector('input[name="return_date"]');
            
            pickupDateInput.addEventListener('change', function() {
                returnDateInput.min = this.value;
                if(returnDateInput.value && returnDateInput.value < this.value) {
                    returnDateInput.value = this.value;
                }
            });
            
            // Set minimum pickup date to today
            const today = new Date().toISOString().split('T')[0];
            pickupDateInput.min = today;
            if(!pickupDateInput.value) {
                returnDateInput.min = today;
            }
        });
    </script>
</body>
</html>
