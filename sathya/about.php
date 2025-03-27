<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Gown Rental System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navigation -->
    <nav class="bg-indigo-600 text-white py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <h1 class="text-2xl font-bold">S&V Gown Rental</h1>
            <div>
                <a href="index.php" class="px-4 hover:underline">Home</a>
                <a href="about.php" class="px-4 hover:underline">About</a>
                <a href="contact.php" class="px-4 hover:underline">Contact</a>
                <a href="login.php" class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-semibold">Login</a>
            </div>
        </div>
    </nav>

    <!-- About Section -->
    <section class="py-12">
        <div class="container mx-auto text-center px-6">
            <h2 class="text-4xl font-bold text-indigo-700 mb-4">About S&V Gown Rental</h2>
            <p class="text-gray-700 max-w-3xl mx-auto text-lg">
                S&V Gown Rental is your premier destination for elegant and affordable gowns. 
                Whether you're attending a wedding, prom, or any special occasion, we provide 
                high-quality gowns at a fraction of the retail price. Our mission is to help 
                every customer feel beautiful and confident without breaking the bank.
            </p>
        </div>
    </section>

    <!-- Our Services -->
    <section class="bg-white py-12">
        <div class="container mx-auto px-6">
            <h3 class="text-3xl font-bold text-center text-gray-800 mb-6">Our Services</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="p-6 border rounded-lg shadow-lg">
                    <h4 class="text-xl font-semibold text-indigo-600">Gown Rental</h4>
                    <p class="text-gray-600 mt-2">Choose from a wide collection of premium gowns for any occasion.</p>
                </div>
                <div class="p-6 border rounded-lg shadow-lg">
                    <h4 class="text-xl font-semibold text-indigo-600">Customization</h4>
                    <p class="text-gray-600 mt-2">Get alterations to make sure your gown fits perfectly.</p>
                </div>
                <div class="p-6 border rounded-lg shadow-lg">
                    <h4 class="text-xl font-semibold text-indigo-600">Accessories</h4>
                    <p class="text-gray-600 mt-2">Complete your look with our stunning collection of accessories.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-indigo-600 text-white text-center py-6 mt-12">
        <p>&copy; <?php echo date("Y"); ?> S&V Gown Rental. All rights reserved.</p>
    </footer>

</body>
</html>
