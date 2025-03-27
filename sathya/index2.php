<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if 'full_name' exists before using it
$full_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "User";

// Initialize cart total items if not set
if (!isset($_SESSION['cart_total'])) {
    $_SESSION['cart_total'] = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S&V Gown Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="font-sans bg-gray-50">

<style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            backdrop-filter: blur(5px);
        }
        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80vh;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(255,255,255,0.2);
        }
        .close {
            position: absolute;
            right: 25px;
            top: 10px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .close:hover {
            transform: rotate(90deg);
        }
        .gown-card {
            transition: all 0.5s ease;
        }
        .gown-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
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
                                <?php
                                if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                                    echo '<span class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">' . count($_SESSION['cart']) . '</span>';
                                }
                                ?>
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

    <!-- Hero Section -->
    <section class="relative min-h-[70vh] flex items-center justify-center bg-[url('img/GOWN.jpg')] bg-cover bg-center bg-no-repeat overflow-hidden">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative max-w-7xl mx-auto px-4">
            <div class="text-center md:text-left pt-20 md:pt-0">
                <h1 class="text-5xl font-bold text-white mb-6 leading-tight">S&V Gown Rental</h1>
                <p class="text-lg text-white/90 mb-8 max-w-2xl">Find the perfect dress for every occasion</p>
            </div>
        </div>
    </section>

    <!-- Popular Categories Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Our Collections</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $collections = [
                    [
                        "Wedding Gowns",
                        "img/wedding.jpg",
                        "wedding2.php", // Updated link
                        "Elegant and timeless wedding gowns perfect for your special day. From classic designs to modern styles, find the dress that makes you feel like a beautiful bride."
                    ],
                    [
                        "Ball Gowns",
                        "img/ballgown.jpg",
                        "ball2.php", // Updated link
                        "Stunning ball gowns for formal events and special occasions. Features full skirts, fitted bodices, and luxurious fabrics to make you feel like royalty."
                    ],
                    [
                        "Raffles Gowns",
                        "img/raffles.jpg",
                        "raffles2.php", // Updated link
                        "Sophisticated and stylish raffles gowns perfect for corporate events and formal gatherings. Elegant designs that combine comfort with professional appeal."
                    ],
                    [
                        "Pageant Gowns",
                        "img/pageantgown.jpg",
                        "pageant2.php", // Updated link
                        "Glamorous pageant gowns designed to make you stand out. Features intricate details, flattering silhouettes, and show-stopping elements for competition success."
                    ]
                ];

                foreach ($collections as $collection) {
                    echo '<div class="relative group overflow-hidden rounded-lg">';
                    echo '<a href="' . $collection[2] . '">';
                    echo '<img src="' . $collection[1] . '" alt="' . $collection[0] . '" class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-105">';
                    echo '<div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-end p-6">';
                    echo '<h3 class="text-xl font-bold text-white mb-2">' . $collection[0] . '</h3>';
                    echo '<p class="text-sm text-white opacity-90">' . $collection[3] . '</p>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto mb-4 text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Quality Assurance</h3>
                    <p class="text-gray-600">Premium gowns maintained to perfection</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto mb-4 text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Flexible Rental Period</h3>
                    <p class="text-gray-600">Convenient rental durations</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto mb-4 text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Affordable Prices</h3>
                    <p class="text-gray-600">Competitive rates for all budgets</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto mb-4 text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Always here to help you</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">What Our Customers Say</h2>
            <div class="relative">
                <div class="overflow-hidden">
                    <div id="testimonialSlider" class="flex transition-transform duration-500">
                        <?php
                        $testimonials = [
                            [
                                'name' => 'Jackilyn Zaluaga Acobo',
                                'role' => 'Filipiniana Gown',
                         'comment' => 'Amazing and memorable experienced!!!So elegant and comfy  Gown 😍,Thank You  S&V Fashion Botique for my Moderned Filipiniana Outfit during our SelIers Award 2023.I highly recommend to anyone looking for quality Gown 👏👏 100% Satisfied here😍♥️
The Best List of Positive Review Response Examples - Usersnap',
                                'image' => 'img/event1.jpg'
                            ],
                            [
                                'name' => 'Audrey Malacaste',
                                'role' => 'Pageant Gown',
                                'comment' => 'Very nice designs and gowns affordable !!❤️🫶',
                                'image' => 'img/event3.jpg'
                            ],
                            [
                                'name' => 'Jhaneil Sarmiento',
                                'role' => 'Ball Gown',
                                'comment' => 'very affordable gown,  mabait ang owner very accommodated, at magaganda yung mga gowns😊',
                                'image' => 'img/testimonial3.jpg'
                            ],
                            [
                                'name' => 'Lorrhea Mae Laurie',
                                'role' => 'Pageant Gown',
                                'comment' => 'uper nice ang mga gowns and but an ang owner!! Dili jud mo mag mahay if diri mo mag rent kay affordable kaayo ila gowns here. Thank you antee alonaa!! 🩷',
                                'image' => 'img/event2.jpg'
                            ]
                        ];
                        foreach ($testimonials as $testimonial) {
                            echo '<div class="w-full flex-shrink-0 px-4">';
                            echo '<div class="bg-white p-6 rounded-lg shadow-lg">';
                            echo '<div class="flex items-center mb-4">';
                            echo '<img src="' . $testimonial['image'] . '" alt="' . $testimonial['name'] . '" class="w-12 h-12 rounded-full object-cover mr-4">';
                            echo '<div>';
                            echo '<h3 class="font-bold">' . htmlspecialchars($testimonial['name']) . '</h3>';
                            echo '<p class="text-gray-600 text-sm">' . htmlspecialchars($testimonial['role']) . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '<p class="text-gray-600">"' . htmlspecialchars($testimonial['comment']) . '"</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                
                <button onclick="moveSlide(-1)" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                
                <button onclick="moveSlide(1)" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <div class="flex justify-center mt-4 space-x-2">
                <?php
                for($i = 0; $i < count($testimonials); $i++) {
                    echo '<button onclick="goToSlide(' . $i . ')" class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400" data-slide="' . $i . '"></button>';
                }
                ?>
            </div>
        </div>

        <script>
            let currentSlide = 0;
            const slides = document.querySelectorAll('#testimonialSlider > div');
            const dots = document.querySelectorAll('[data-slide]');
            
            function updateSlider() {
                const offset = currentSlide * -100;
                document.getElementById('testimonialSlider').style.transform = `translateX(${offset}%)`;
                
                // Update dots
                dots.forEach((dot, index) => {
                    if(index === currentSlide) {
                        dot.classList.add('bg-gray-600');
                    } else {
                        dot.classList.remove('bg-gray-600');
                    }
                });
            }

            function moveSlide(direction) {
                currentSlide = (currentSlide + direction + slides.length) % slides.length;
                updateSlider();
            }

            function goToSlide(index) {
                currentSlide = index;
                updateSlider();
            }

            // Auto advance slides every 5 seconds
            setInterval(() => moveSlide(1), 5000);

            // Initialize first dot as active
            updateSlider();
        </script>
    </section>

    <!-- Call to Action Section -->
    <section class="py-20 bg-gradient-to-r from-purple-500 to-pink-400 text-white text-center">
        <h2 class="text-4xl font-bold">Ready to Find Your Perfect Dress?</h2>
        <p class="text-lg mt-4">Book an appointment today and let us help you find the perfect gown.</p>
        <a href="booking.php" class="mt-6 inline-block bg-white text-indigo-600 px-6 py-3 rounded-lg font-bold shadow-lg hover:scale-105 transition-transform">
            Book Appointment
        </a>
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
            
</body>
</html>