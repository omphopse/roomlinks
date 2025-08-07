<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../helpers/auth.php';
checkAuth();
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

    <title>More | RoomLinks | Offical Website</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />

</head>

<body class="flex flex-col min-h-screen ">


    <?php include 'partials/header.php' ?>

    <div class="flex flex-col items-center justify-start min-h-[80vh] bg-white py-10 px-5 my-10">
        <div class="w-full max-w-2xl">
            <div class="flex flex-col gap-6">
                <a href="<?php echo APP_URL; ?>/mylistings" class="block hover:bg-gray-50 rounded-lg transition">
                    <div class="flex items-center gap-4">
                        <span class="bg-gray-100 rounded-full p-3 text-xl text-gray-600"><i class="fas fa-edit"></i></span>
                        <div>
                            <div class="font-semibold text-lg">My Listing</div>
                            <div class="text-sm text-gray-500">Manage your listing details.</div>
                        </div>
                    </div>
                </a>
                <a href="<?php echo APP_URL; ?>/vibes" class="block hover:bg-gray-50 rounded-lg transition">
                    <div class="flex items-center gap-4">
                        <span class="bg-gray-100 rounded-full p-3 text-xl text-gray-600"><i class="fas fa-heart"></i></span>
                        <div>
                            <div class="font-semibold text-lg">My Vibes</div>
                            <div class="text-sm text-gray-500">It shows other what kind of flatmate you prefer.</div>
                        </div>
                    </div>
                </a>
                <a href="<?php echo APP_URL; ?>/profile" class="block hover:bg-gray-50 rounded-lg transition">
                    <div class="flex items-center gap-4">
                        <span class="bg-gray-100 rounded-full p-3 text-xl text-gray-600"><i class="fas fa-user-circle"></i></span>
                        <div>
                            <div class="font-semibold text-lg">My Profile</div>
                            <div class="text-sm text-gray-500">View or edit your profile details.</div>
                        </div>
                    </div>
                </a>
                <a href="<?php echo APP_URL; ?>/feedback" class="block hover:bg-gray-50 rounded-lg transition">
                    <div class="flex items-center gap-4">
                        <span class="bg-gray-100 rounded-full p-3 text-xl text-gray-600"><i class="fas fa-comment-dots"></i></span>
                        <div>
                            <div class="font-semibold text-lg">Write a FeedBack</div>
                            <div class="text-sm text-gray-500">We are always open for Suggestions.</div>
                        </div>
                    </div>
                </a>
                <a href="<?php echo APP_URL; ?>/contact" class="block hover:bg-gray-50 rounded-lg transition">
                    <div class="flex items-center gap-4">
                        <span class="bg-gray-100 rounded-full p-3 text-xl text-gray-600"><i class="fas fa-envelope"></i></span>
                        <div>
                            <div class="font-semibold text-lg">Need Help?</div>
                            <div class="text-sm text-gray-500">We are always here for you.</div>
                        </div>
                    </div>
                </a>
                <a href="<?php echo APP_URL; ?>/logout" class="block hover:bg-gray-50 rounded-lg transition">
                    <div class="flex items-center gap-4">
                        <span class="bg-red-100 rounded-full p-3 text-xl text-red-600"><i class="fas fa-sign-out-alt"></i></span>
                        <div>
                            <div class="font-semibold text-lg text-red-500">Logout</div>
                            <div class="text-sm text-red-500">You can login anytime to your account.</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <?php include 'partials/footer.php' ?>

</body>

</html>