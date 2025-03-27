<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pageant Collection - S&V Gown Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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

    <!-- Hero Section -->
    <section class="relative min-h-[70vh] flex items-center justify-center bg-gradient-to-br from-rose-100 via-pink-100 to-violet-100 overflow-hidden">
        <div class="absolute inset-0 bg-[url('img/pattern.svg')] opacity-10"></div>
        <div class="relative text-center px-4 pt-20">
            <h1 class="text-5xl font-bold text-gray-800 mb-6 leading-tight">Pageant Collection</h1>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">Discover your dream pageant dress from our exquisite collection.</p>
            <a href="#collection" class="inline-block bg-gradient-to-r from-rose-400 to-pink-500 text-white px-8 py-3 rounded-full font-bold hover:shadow-xl hover:scale-105 transition-all duration-300">
                Explore Collection
            </a>
        </div>
    </section>

<!-- Collection Grid -->
<section id="collection" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <?php
            $pageant_dresses = [
                ['id' => 1, 'name' => 'Pageant Gown 1', 'price' => 1000, 'image' => 'img/pageantgown1.jpeg'],
                ['id' => 2, 'name' => 'Pageant Gown 2', 'price' => 1000, 'image' => 'img/pageantgown2.jpeg'],
                ['id' => 3, 'name' => 'Pageant Gown 3', 'price' => 500, 'image' => 'img/pageantgown3.jpeg'],
                ['id' => 4, 'name' => 'Pageant Gown 4', 'price' => 500, 'image' => 'img/pageantgown4.jpeg'],
                ['id' => 5, 'name' => 'Pageant Gown 5', 'price' => 500, 'image' => 'img/pageantgown5.jpeg'],
                ['id' => 6, 'name' => 'Pageant Gown 6', 'price' => 500, 'image' => 'img/pageantgown6.jpeg'],
                ['id' => 7, 'name' => 'Pageant Gown 7', 'price' => 500, 'image' => 'img/pageantgown7.jpeg'],
            ];
                foreach ($pageant_dresses as $index => $dress) {
                    echo '<div class="gown-card bg-white rounded-2xl overflow-hidden shadow-lg">';
                    echo '<div class="cursor-pointer relative" onclick="openModal(\'' . $dress['image'] . '\')">';
                    echo '<img src="' . $dress['image'] . '" alt="' . $dress['name'] . '" class="w-full h-72 object-cover">'; // Reduced height from h-96 to h-72
                    echo '<div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-8">';
                    echo '<h3 class="text-2xl font-bold text-white font-[\'Playfair_Display\']">' . $dress['name'] . '</h3>';
                    echo '<p class="text-rose-200 mt-2">₱' . number_format($dress['price']) . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="p-6 flex justify-end">';
                    echo '<a href="login.php" class="bg-gradient-to-r from-rose-400 to-pink-500 text-white px-6 py-3 rounded-full hover:shadow-lg hover:scale-105 transition-all duration-300">';
                    echo 'Book Now';
                    echo '</a>';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div id="imageModal" class="modal" onclick="closeModal()">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage" style="max-width: 60%; max-height: 60vh;">
</div>

<!-- Booking Process -->
<section class="py-16 bg-gradient-to-br from-rose-50 to-pink-50">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">How to Book Your Dream Dress</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-rose-400 to-pink-500 rounded-xl flex items-center justify-center text-white text-xl font-bold mx-auto mb-6">1</div>
                <h3 class="text-lg font-bold mb-2 text-gray-800">Browse Collection</h3>
                <p class="text-gray-600 text-sm">Explore our wedding dress collection</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-rose-400 to-pink-500 rounded-xl flex items-center justify-center text-white text-xl font-bold mx-auto mb-6">2</div>
                <h3 class="text-lg font-bold mb-2 text-gray-800">Book Appointment</h3>
                <p class="text-gray-600 text-sm">Schedule your fitting session</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-rose-400 to-pink-500 rounded-xl flex items-center justify-center text-white text-xl font-bold mx-auto mb-6">3</div>
                <h3 class="text-lg font-bold mb-2 text-gray-800">Try & Select</h3>
                <p class="text-gray-600 text-sm">Find your perfect fit</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-rose-400 to-pink-500 rounded-xl flex items-center justify-center text-white text-xl font-bold mx-auto mb-6">4</div>
                <h3 class="text-lg font-bold mb-2 text-gray-800">Confirm Booking</h3>
                <p class="text-gray-600 text-sm">Secure your dress</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-16 bg-gradient-to-br from-rose-400 via-pink-500 to-purple-500">
    <div class="max-w-3xl mx-auto text-center px-4">
        <h2 class="text-3xl font-bold text-white mb-6">Ready to Find Your Perfect Dress?</h2>
        <p class="text-white/90 mb-8">Let us help you make your special day magical.</p>
        <a href="login.php" class="inline-block bg-white text-gray-800 px-8 py-3 rounded-full font-bold hover:shadow-lg hover:scale-105 transition-all duration-300">
            Book Your Appointment
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <h3 class="text-xl font-bold mb-4">S&V Gown Rental</h3>
            <p class="text-gray-400">Making your special moments unforgettable with our premium gown collection.</p>
        </div>
        <div>
            <h4 class="text-lg font-bold mb-4">Contact Info</h4>
            <ul class="space-y-2 text-gray-400">
                <li>Zone 8 Ordnance, Camp Evangelista, Patag</li>
                <li>Cagayan de Oro City, Philippines</li>
                <li>Phone: 0975 320 6018</li>
            </ul>
        </div>
        <div>
            <h4 class="text-lg font-bold mb-4">Follow Us</h4>
            <a href="https://www.facebook.com/profile.php?id=100090762784236" class="text-gray-400 hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 mt-8 pt-6 border-t border-gray-800 text-center text-gray-400">
        <p>&copy; <?php echo date('Y'); ?> S&V Gown Rental. All rights reserved.</p>
    </div>
</footer>

<script>
    function openModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = "flex";
        modalImg.src = imageSrc;
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = "none";
    }
</script>
</body>
</html>