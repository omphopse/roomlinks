<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles/tailwind.css" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" />

    <title>RoomLinks | Offical Website</title>

</head>

<body class="flex flex-col min-h-screen">
    <div class="flex-grow">

        <?php include __DIR__ . '/../partials/header.php'; ?>

        <!-- form section starts -->
        <section class="form-section mt-10 p-5 mb-10 text-center" id="write">

            <?php
            session_start();
            if (isset($_SESSION['success'])) {
                echo '<p class="text-green-600 font-semibold mb-4">' . htmlspecialchars($_SESSION['success']) . '</p>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<p class="text-red-600 font-semibold mb-4">' . htmlspecialchars($_SESSION['error']) . '</p>';
                unset($_SESSION['error']);
            }
            ?>

            <form id="userlogin" action="<?php echo APP_URL; ?>/admin-login" method="POST"
                class="max-w-md mx-auto bg-white p-6 rounded-[20px] border-2 border-blue-300 shadow-md">
                <div class="mb-4 text-left">
                    <label for="email" class="block mb-2 font-semibold text-gray-700">Email</label>
                    <input type="text" id="email" name="email" required
                        class="w-full px-4 py-2 border-2 border-blue-300 rounded-[20px] focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        placeholder="Enter your email" />
                </div>

				<div class="mb-4 text-left">
                    <label for="password" class="block mb-2 font-semibold text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border-2 border-blue-300 rounded-[20px] focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        placeholder="Enter your password" />
                </div>
              
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-[20px] hover:bg-blue-700 transition-colors duration-300 font-semibold">
                    Submit
                </button>
            </form>
        </section>
    </div>

    

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    
</body>

</html>