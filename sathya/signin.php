<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S&V Gown Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    
</head>
<body class="font-sans">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center">
                        <div class="flex items-center items-start justify-start py-2">
                            <img src="img/logo.png" alt="Logo" class="h-10 w-10">
                            <span class="text-gray-700 font-bold text-xl">S&V Gown Rental</span>
                        </div>
                <div class="flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                        Home
                    </a>
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200 flex items-center">
                            Gown
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="wedding-collection.php" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Wedding Collection</a>
                            <a href="ball-collection.php" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Ball Collection</a>
                            <a href="raffles-collection.php" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Raffles Collection</a>
                            <a href="pageant-collection.php" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Pageant Gowns Collection</a>
                        </div>
                    </div>
                    <a href="booking.php" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                        Book Now
                    </a>
                    <a href="contact.php" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                        Contact
                    </a>
                </div>  
                <div class="flex justify-end">
                        <button onclick="window.location.href='login.php'" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200 border border-blue-500 rounded-md px-4 py-2 bg-blue-500 hover:bg-blue-100 text-white">
                            Login
                        </button>
                    </div>
            </div>
        </div>
    </nav>
    
    <section class="bg-gradient-to-r from-violet-500 via-fuchsia-400 to-pink-200 text-white py-24 px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-6 font-['Arial']">S&V Gown Rental</h1>
        <p class="text-xl mb-8"></p>
    </section>

<!-- Popular Categories Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Our Collections</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Wedding Gowns -->
            <div class="relative group overflow-hidden rounded-lg">
                <a href="wedding-collection.php">
                    <img src="img/wedding.jpg" alt="Wedding Gowns" class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white">Wedding Gowns</h3>
                            <p class="text-gray-200 mt-2">Make your special day unforgettable</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Ball Gowns -->
            <div class="relative group overflow-hidden rounded-lg">
                <a href="ball-collection.php">
                    <img src="img/ballgown.jpg" alt="Ball Gowns" class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white">Ball Gowns</h3>
                            <p class="text-gray-200 mt-2">Elegant designs for special occasions</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Raffles Gowns -->
            <div class="relative group overflow-hidden rounded-lg">
                <a href="raffles-collection.php">
                    <img src="img/raffles.jpg" alt="Raffles Gowns" class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white">Raffles Gowns</h3>
                            <p class="text-gray-200 mt-2">Perfect for any formal event</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pageant Gowns -->
            <div class="relative group overflow-hidden rounded-lg">
                <a href="pageant-collection.php">
                    <img src="img/pageantgown.jpg" alt="Pageant Gowns" class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white">Pageant Gowns</h3>
                            <p class="text-gray-200 mt-2">For any pageant event</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>

    <!-- Why Choose Us Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto mb-4 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Quality Assurance</h3>
                    <p class="text-gray-600">Premium gowns maintained to perfection</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto mb-4 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Flexible Rental Period</h3>
                    <p class="text-gray-600">Convenient rental durations</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto mb-4 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Affordable Prices</h3>
                    <p class="text-gray-600">Competitive rates for all budgets</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto mb-4 text-indigo-600">
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
    <section class="py-20 bg-gradient-to-r from-violet-500 via-fuchsia-400 to-pink-200">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Find Your Perfect Dress?</h2>
            <p class="text-white text-xl mb-8">Book an appointment today and let us help you find the perfect gown for your special occasion.</p>
            <a href="booking.php" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold hover:-translate-y-1 transition-transform duration-300">
                Book Appointment
            </a>
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
</body>
</html>