<?php
session_start(); // Start session to access session variables

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $full_name = $_POST['name']; // Changed from full_name to name to match form field
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Validate inputs
    if (empty($full_name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email format']);
        exit;
    }

    // Store in session
    $_SESSION['full_name'] = $full_name;
    $_SESSION['email'] = $email;
    $_SESSION['message'] = $message;

    try {
        // Connect to database
        $conn = new mysqli('localhost', 'root', '', 'gown_rental');

        // Check connection
        if ($conn->connect_error) {
            throw new Exception('Connection failed: ' . $conn->connect_error);
        }

        // Check if table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'contact'");
        if ($table_check->num_rows == 0) {
            // Create table if it doesn't exist
            $create_table = "CREATE TABLE IF NOT EXISTS contact (
                id INT AUTO_INCREMENT PRIMARY KEY,
                full_name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                message TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            if (!$conn->query($create_table)) {
                throw new Exception('Error creating table: ' . $conn->error);
            }
        }

        // Prepare and execute statement
        $stmt = $conn->prepare("INSERT INTO contact (full_name, email, message) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param("sss", $full_name, $email, $message); // Changed $name to $full_name
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception($stmt->error);
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// Get cart count if needed
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Get user's full name for display
$full_name = isset($_SESSION['user_fullname']) ? $_SESSION['user_fullname'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - S&V Gown Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="font-sans bg-gray-100">
   <!-- Navigation -->
   <nav class="bg-white/80 backdrop-blur-md shadow-lg fixed w-full z-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center py-4">
                    <img src="img/logo.png" alt="Logo" class="h-12 w-12">
                    <span class="text-gray-800 font-bold text-2xl ml-2 font-['Playfair_Display']">S&V Gown Rental</span>
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
                            <a href="wedding-collection.php" class="block px-4 py-3 text-gray-700 hover:bg-rose-50 hover:text-rose-500 rounded-t-xl transition-colors duration-300">Wedding Collection</a>
                            <a href="ball-collection.php" class="block px-4 py-3 text-gray-700 hover:bg-rose-50 hover:text-rose-500 transition-colors duration-300">Ball Collection</a>
                            <a href="raffles-collection.php" class="block px-4 py-3 text-gray-700 hover:bg-rose-50 hover:text-rose-500 transition-colors duration-300">Raffles Collection</a>
                            <a href="pageant-collection.php" class="block px-4 py-3 text-gray-700 hover:bg-rose-50 hover:text-rose-500 rounded-b-xl transition-colors duration-300">Pageant Gowns</a>
                        </div>
                    </div>
                    <a href="login.php" class="text-gray-700 hover:text-rose-500 font-medium transition-colors duration-300">Book Now</a>
                    <a href="contact.php" class="text-gray-700 hover:text-rose-500 font-medium transition-colors duration-300">Contact</a>
                </div>
                
                <!-- Login Button -->
                <div class="flex items-center">
                    <button onclick="window.location.href='login.php'" class="bg-gradient-to-r from-rose-400 to-pink-500 text-white px-6 py-3 rounded-full font-medium hover:shadow-lg hover:scale-105 transition-all duration-300">
                        Login
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contact Section -->
    <section class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
                <p class="text-gray-600">Get in touch with us for any inquiries or assistance</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-lg p-8 transform hover:scale-105 transition duration-300">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Contact Information</h2>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="bg-pink-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-800">Address</h3>
                                <p class="text-gray-600">Zone 8 Ordnance, Camp Evangelista, Patag<br>Cagayan de Oro City, Philippines</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-pink-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-800">Phone</h3>
                                <p class="text-gray-600">0975 320 6018</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-pink-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-800">Email</h3>
                                <p class="text-gray-600">sathyakilat@icloud.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-xl shadow-lg p-8 transform hover:scale-105 transition duration-300">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Send us a Message</h2>
                    <form id="contactForm" class="space-y-4" method="POST">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Name</label>
                            <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                            <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Message</label>
                            <textarea name="message" id="message" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-pink-500 text-white px-6 py-3 rounded-lg hover:bg-pink-600 transition-colors duration-300">
                            Send Message
                        </button>
                    </form>

                    <!-- Success Message -->
                    <div id="successMessage" class="hidden mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        Your message has been sent successfully! We'll get back to you soon.
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="hidden mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                    <li><a href="about.php" class="text-gray-400 hover:text-white">About Us</a></li>
                    <li><a href="services.php" class="text-gray-400 hover:text-white">Services</a></li>
                    <li><a href="gallery.php" class="text-gray-400 hover:text-white">Gowns</a></li>
                    <li><a href="contact.php" class="text-gray-400 hover:text-white">Contact</a></li>
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
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('contact2.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    this.reset();
                    document.getElementById('successMessage').classList.remove('hidden');
                    document.getElementById('errorMessage').classList.add('hidden');
                    setTimeout(() => {
                        document.getElementById('successMessage').classList.add('hidden');
                    }, 5000);
                } else {
                    document.getElementById('errorMessage').textContent = data.error || 'Error sending message. Please try again.';
                    document.getElementById('errorMessage').classList.remove('hidden');
                    document.getElementById('successMessage').classList.add('hidden');
                    setTimeout(() => {
                        document.getElementById('errorMessage').classList.add('hidden');
                    }, 5000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('errorMessage').textContent = 'Error sending message. Please try again.';
                document.getElementById('errorMessage').classList.remove('hidden');
                document.getElementById('successMessage').classList.add('hidden');
            });
        });
    </script>
</body>
</html>
