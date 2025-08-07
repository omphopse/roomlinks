<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../helpers/auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles/tailwind.css" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/hide-scrollbar.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" />

    <title>Privacy Policy | RoomLinks | Offical Website</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />

</head>

<body class="flex flex-col min-h-screen ">



    <?php include 'partials/header.php' ?>

    <main class="flex-1 container mx-auto px-4 py-8 max-w-3xl">
        <h1 class="text-3xl font-bold mb-6">Privacy Policy – RoomLinks.in</h1>
        <p class="mb-4">RoomLinks.in (“we,” “our,” or “us”) respects your privacy and is committed to protecting your personal data. This Privacy Policy explains how we collect, use, and safeguard your information.</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">1. Information We Collect</h2>
        <p class="mb-2">We may collect the following information from users:</p>
        <ul class="list-disc list-inside mb-4">
            <li>Name, contact number, and email address.</li>
            <li>Room details if you are listing a property.</li>
            <li>Usage data like device, browser, and location (for better platform experience).</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">2. How We Use Your Information</h2>
        <p class="mb-2">We use your information to:</p>
        <ul class="list-disc list-inside mb-4">
            <li>Provide and improve our zero-commission rental services.</li>
            <li>Connect room owners with potential tenants.</li>
            <li>Send notifications or updates about listings or platform changes.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">3. Data Sharing & Security</h2>
        <ul class="list-disc list-inside mb-4">
            <li>We do not sell or trade your personal data to third parties.</li>
            <li>Data is shared only if required by law or for fraud prevention.</li>
            <li>We use standard security measures to protect user information.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">4. Cookies</h2>
        <ul class="list-disc list-inside mb-4">
            <li>RoomLinks.in may use cookies to enhance the user experience.</li>
            <li>Users can choose to disable cookies in their browser settings.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">5. Data Retention</h2>
        <ul class="list-disc list-inside mb-4">
            <li>We retain user data as long as necessary to provide services.</li>
            <li>Users can request deletion of their data by contacting us.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">6. Policy Updates</h2>
        <ul class="list-disc list-inside mb-4">
            <li>This Privacy Policy may be updated from time to time.</li>
            <li>Our current model is valid for 2025; changes may occur after 31st December 2025.</li>
        </ul>

        <div class="mt-8">
            <p class="mb-1">📧 <strong>Contact:</strong> <a href="mailto:roomlinks.in@gmail.com" class="text-blue-600 underline">roomlinks.in@gmail.com</a></p>
            <p>📞 <strong>Phone:</strong> <a href="tel:8788091144" class="text-blue-600 underline">8788091144</a> / <a href="tel:9623908967" class="text-blue-600 underline">9623908967</a></p>
        </div>
    </main>

    <?php include 'partials/footer.php' ?>

</body>

</html>