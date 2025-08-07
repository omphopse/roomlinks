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

    <title>Terms and Conditions | RoomLinks | Offical Website</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />

</head>

<body class="flex flex-col min-h-screen ">




    <?php include 'partials/header.php' ?>

    <main class="flex-1 container mx-auto px-4 py-8 max-w-3xl">
        <h1 class="text-3xl font-bold mb-6">Terms &amp; Conditions – RoomLinks.in</h1>
        <p class="mb-4">By using RoomLinks.in, you agree to the following terms:</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">1. About Our Services</h2>
        <ul class="list-disc list-inside mb-4">
            <li>RoomLinks.in is a zero-commission rental platform for 2025.</li>
            <li>Owners can list rooms for rent, and tenants can rent them without any charges or brokerage.</li>
            <li>We may change our business model after 31st December 2025.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">2. User Responsibilities</h2>
        <ul class="list-disc list-inside mb-4">
            <li>All information provided must be accurate and genuine.</li>
            <li>Room owners are responsible for property legality and safety.</li>
            <li>Tenants must verify property details before finalizing any rental.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">3. Limitation of Liability</h2>
        <ul class="list-disc list-inside mb-4">
            <li>RoomLinks.in is only a listing platform and does not guarantee room quality, availability, or safety.</li>
            <li>We are not responsible for disputes, fraud, or damages between users.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">4. Zero Commission Policy</h2>
        <ul class="list-disc list-inside mb-4">
            <li>No commission or fees are charged in 2025.</li>
            <li>Payments are done directly between owners and tenants.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">5. Modifications</h2>
        <ul class="list-disc list-inside mb-4">
            <li>We may modify our services, policies, and fees after 2025.</li>
        </ul>

        <div class="mt-8">
            <p class="mb-1">📧 <strong>Contact:</strong> <a href="mailto:roomlinks.in@gmail.com" class="text-blue-600 underline">roomlinks.in@gmail.com</a></p>
            <p>📞 <strong>Phone:</strong> <a href="tel:8788091144" class="text-blue-600 underline">8788091144</a> / <a href="tel:9623908967" class="text-blue-600 underline">9623908967</a></p>
        </div>
    </main>

    <?php include 'partials/footer.php' ?>

</body>

</html>