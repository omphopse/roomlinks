<?php require_once __DIR__ . '/../config/constants.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles/tailwind.css" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/hide-scrollbar.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" />

    <title>Add newlisting | RoomLinks | Offical Website</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />

</head>

<body class="flex flex-col min-h-screen ">
    <?php include 'partials/header.php' ?>
    <div class="flex-grow flex items-center justify-center px-4  min-h-[120vh] md:min-h-[80vh] ">
        <!-- Add New Listing Start -->
        <div class="w-full max-w-3xl mx-auto text-center mt-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">New Requirements</h1>
            <p class="text-gray-600 mb-8">Post your requirements for rooms or roommates and discover the ideal fit for you.
            </p>
            <div class="flex flex-col md:flex-row gap-6 justify-center items-center">
                <!-- Need Room/Flat Box -->
                <a href="<?php echo APP_URL; ?>/room"
                    class="flex-1 bg-[#A6F1E0] rounded-xl p-10 shadow-2xl hover:shadow-2xl transition-all duration-200 flex flex-col items-center min-w-[250px] max-w-xs min-h-[340px] max-h-[360px]">
                    <div class="text-left w-full mb-6">
                        <span class="block text-lg font-semibold text-gray-800">Need Room/Flat</span>
                        <span class="block text-sm text-gray-600">with roommate &gt;</span>
                    </div>
                    <img src="assets/listing-banner/home.png" alt="Need Room/Flat"
                        class="w-56 h-56 object-contain mx-auto" />
                </a>
                <!-- Need Roommate Box -->
                <a href="<?php echo APP_URL; ?>/roommate"
                    class="flex-1 bg-yellow-100 rounded-xl p-10 shadow-2xl hover:shadow-2xl transition-all duration-200 flex flex-col items-center min-w-[250px] max-w-xs min-h-[340px] max-h-[360px]">
                    <div class="text-left w-full mb-6">
                        <span class="block text-lg font-semibold text-gray-800">Need Roommate</span>
                        <span class="block text-sm text-gray-600">for your room &gt;</span>
                    </div>
                    <img src="assets/listing-banner/roomates.png" alt="Need Roommate"
                        class="w-56 h-56 object-contain mx-auto" />
                </a>
            </div>
            <div class="flex justify-center">
                <button class="mt-10 px-8 py-3 bg-blue-600 text-white font-semibold rounded-full shadow-lg hover:bg-blue-700 transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-exclamation-triangle text-yellow-300 text-xl"></i>
                    Be aware of scams
                </button>
            </div>
        </div>
        <!-- Add New Listing End -->
    </div>

    <?php include 'partials/footer.php' ?>
</body>

</html>