<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add to cart (automatically add when dress_id is passed)
if (isset($_POST['dress_id'])) {
    $gown_id = $_POST['dress_id'];
    $gown_name = $_POST['dress_name']; 
    $price = $_POST['dress_price'];
    
    $item = array(
        'gown_id' => $gown_id,
        'gown_name' => $gown_name,
        'price' => $price,
        'quantity' => 1
    );
    
    $_SESSION['cart'][] = $item;
    
    // Redirect back to previous page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

// Remove from cart
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
    }
}

// Get cart count
$cart_count = count($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - S&V Gown Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="bg-gray-100">

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
                        <a href="cart.php" class="text-gray-700 hover:text-rose-500 transition-colors duration-300 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <?php if($cart_count > 0): ?>
                                <span class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                    <?php echo $cart_count; ?>
                                </span>
                            <?php endif; ?>
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
    
    <!-- Main Content -->
    <div class="container mx-auto px-6 py-24">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">Your Shopping Cart (<?php echo $cart_count; ?> items)</h1>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="bg-white rounded-xl shadow-lg p-8 text-center max-w-2xl mx-auto">
                <div class="w-20 h-20 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold mb-4">Your Cart is Empty</h2>
                <p class="text-gray-600 mb-8">Start exploring our beautiful collection of gowns!</p>
                <a href="index2.php" class="inline-block px-8 py-3 bg-pink-500 text-white rounded-full hover:bg-pink-600 transition duration-300">
                    Browse Collection
                </a>
            </div>
        <?php else: ?>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="md:col-span-2">
                    <?php 
                    $total = 0;
                    foreach($_SESSION['cart'] as $index => $item): 
                        $total += $item['price'];
                    ?>
                        <div class="bg-white rounded-xl shadow-sm mb-4 p-6 hover:shadow-md transition duration-300">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($item['gown_name']); ?></h3>
                                    <p class="text-pink-500 font-medium mt-2">₱<?php echo number_format($item['price'], 2); ?></p>
                                </div>
                                <a href="?remove=<?php echo $index; ?>" class="text-gray-400 hover:text-red-500 transition duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                        <h3 class="text-xl font-semibold mb-6">Order Summary</h3>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Subtotal (<?php echo $cart_count; ?> items)</span>
                            <span class="font-medium">₱<?php echo number_format($total, 2); ?></span>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-lg font-semibold">Total</span>
                            <span class="text-xl font-bold text-pink-500">₱<?php echo number_format($total, 2); ?></span>
                        </div>
                        <a href="booking.php" class="block w-full py-3 px-4 bg-pink-500 text-white text-center rounded-full font-medium hover:bg-pink-600 transition duration-300">
                            Proceed to Checkout
                        </a>
                        <a href="index2.php" class="block w-full mt-4 py-3 px-4 bg-gray-100 text-gray-700 text-center rounded-full font-medium hover:bg-gray-200 transition duration-300">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">S&V Gown Rental</h3>
                    <p class="text-gray-400">Making your special moments unforgettable with our premium gown collection.</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Contact Info</h4>
                    <p class="text-gray-400">Zone 8 Ordnance, Camp Evangelista, Patag<br>
                    Cagayan de Oro City, Philippines<br>
                    Phone: 0975 320 6018</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Follow Us</h4>
                    <a href="https://www.facebook.com/profile.php?id=100090762784236" class="text-gray-400 hover:text-white">
                        Facebook
                    </a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> S&V Gown Rental. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
